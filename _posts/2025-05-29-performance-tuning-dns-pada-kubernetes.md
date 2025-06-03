---
layout: post
title:  "Performance Tuning DNS pada Kubernetes Cluster"
tags: tutorial kubernetes dns
---
Balik lagi saya menulis mengenai permasalahan utama ketika melakukan beberapa observation di beberapa microservices yang memang di deploy pada kubernetes cluster, untuk kubernetes cluster yang saya gunakan adalah Rancher Kubernetes Engine 2 (RKE2). 

Disini saya sering ketika daily stand-up mendengar issue dari team QA terjadi timeout di backend dan saya team infra disini bertanggung jawab untuk menelusuri permasalahan tersebut, beruntungnya dari team backend sendiri membantu saya dan melihat log pada sisi grafana terjadi error unknown host, dimana backend tersebut melakukan request ke external endpoint (example.com/api) namun terjadi unknown host, yang mengakibatkan backend tersebut gagal menerima payload dari endpoint external tersebut.

![sentry log](https://troke.id/assets/images/image-1.png)

Ketika issue tersebut muncul segera saya mencari permasalahn tersebut, dan mengetahui ada beberapa orang yang mempunyai issue sama, pada umumnya orang tidak mengetahui banyak kenapa ini bisa terjadi, karena memang untuk urusan service to service jarang terjadi masalah, namun jika service to external yang memiliki intensitas request paling banyak bisa jadi bermasalah.

### Troubleshoot

Untuk mengetahui hal tersebut, kita perlu melakukan benchmark pada cluster Kubernetes menggunakan DNSPerf

Kalian bisa menggunakan yaml ini :
```yml
---
apiVersion: v1
kind: ConfigMap
metadata:
  name: dns-records-config
  namespace: default
data:
  records.txt: |-
    www.google.com A
    www.amazon.com A
    kubernetes.default.svc.cluster.local A
    kube-dns.kube-system.svc.cluster.local A

---
apiVersion: apps/v1
kind: Deployment
metadata:
  labels:
    app: dnsperf
  name: dnsperf
  namespace: default
spec:
  replicas: 1
  selector:
    matchLabels:
      app: dnsperf
  template:
    metadata:
      labels:
        app: dnsperf
    spec:
      containers:
      - name: dnsperf
        image: guessi/dnsperf
        imagePullPolicy: Always
        env:
        - name: DNSPERF_RECORDS_INPUT
          value: "/opt/records.txt"
        - name: EXTRA_ARGS
          value: "-c 1 -T 1 -l 30 -t 5 -Q 100000"
        volumeMounts:
        - mountPath: /opt/
          name: dns-records-volume
          readOnly: true
      dnsPolicy: ClusterFirst
      restartPolicy: Always
      volumes:
      - configMap:
          defaultMode: 420
          name: dns-records-config
        name: dns-records-volume
```

Dalam yaml ini terdapat beberapa keterangan yaitu DNSPerf akan melakukan stresstest request DNS ke external dan local dns kubernetes.

### Output:

![Bench1](https://troke.id/assets/images/image-2.png)

Terdapat performance jomplang dari 110ribu request yang tidak berhasil 160 (0.14%)

### Solution

Solusinya bagaimana? Saat mencari solusinya di dokumentasi RKE2 (https://docs.rke2.io/networking/networking_services) saya menemukan inti permasalahan pada komponen CoreDNS nya, by default NodeLocal yang menangani caching DNS pada cluster kubernetes tidak di hidupkan oleh RKE2

![helm](https://troke.id/assets/images/image-3.png)

Terlihat value dari rke2-coredns chart bagian `nodelocal` valuenya masih false.

Lalu ada juga bagian lainnya yaitu forward DNS menggunakan dns dari yang dihasilkan oleh provider (/etc/resolv.conf)

![etcresolv](https://troke.id/assets/images/image-4.png)

Solusinya pada bagian `nodelocal` diubah menjadi `true`

Kemudian value `forward` diubah menjadi `. 8.8.8.8` atau `. 1.1.1.1`

ini dikarenakan dns tersebut bisa meresolve seluruh dns yang ada di dunia, jika masih menggunakan dns dari provider, takutnya ada beberapa domain yang tidak bisa di resolve.

### Hasil stresstest kedua :

![Bench2](https://troke.id/assets/images/image-5.png)

Terlihat pada benchmark kedua ini melebihi request dari sebelumnya yaitu 110ribu menjadi 2 juta request dan total keberhasilannya adalah 100% , tandanya stresstest dengan jutaan request tidak menjadi issue dan sisanya tinggal melakukan tuning dari sisi application.

### Kesimpulan

Penting sekali ketika saat sesudah membuat cluster kita harus menghidupkan fungsi `nodelocal` dan mengubah forward dns agar microservice kita berjalan sangat baik apalagi microservice yang dikelola sudah banyak. 