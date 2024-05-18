---
layout: post
title:  "Download File dari GCP Menggunakan Docker"
tags: docker tutorial
---
### Penjelasan
Sudah lama tidak menulis, kali ini saya akan mencoba membuat sebuah tutorial dimana kondisi ini terjadi jika kalian menggunakan Google Cloud Storage (Bucket) dan ingin mendownload file di dalam bucket namun bucket tersebut private.

Bucket GCP ini mirip dengan AWS S3, dimana kita bisa download file tersebut secara langsung, akan tetapi dengan *policy* tertentu membuat bucket itu hanya bisa diakses ketika kita sudah memiliki **secret** dan **access key**.

Kasus di dalam GCP ini sangat bikin saya pusing awalnya, dikarenakan saya sendiri ingin mengunduh sebuah file di dalam bucket namun bucket yang saya punya memiliki policy private, berbeda kondisinya jika bucket nya public saya tidak pusing-pusing amat karena tinggal unduh saja melalui link URL yang tersedia, jadi saya harus menggunakan sebuah Client Desktop seperti CyberDuck atau S3 Browser untuk mengunduhnya.

Namun bagaimana jika memiliki case yang dimana sebuah services membutuhkan file tersebut dan digabungkan di dalam docker? sebagai contoh kasus: saya memiliki sebuah backend service dimana dia membutuhkan sebuah data jika mau dijalankan servicenya, tetapi saya tidak mau ketika melakukan deployment ke server / kubernetes saya harus manual mentransfer data itu, apalagi ketika di deploy ke kubernetes sangat repot sekali jika harus mengunduh dan mengcopy file tersebut ke dalam pods.

### Solusi
Dari kasus itu saya melakukan berbagai macam percobaan mulai dari membuat sebuah aplikasi Golang untuk mengunduh file dari GCP, namun gagal karena authentikasi ke GCP ternyata sangat sulit walaupun mengandalkan API nya, saya akhirnya memutuskan untuk mencoba menggunakan aplikasi buatan mereka yaitu [gsutil](https://cloud.google.com/storage/docs/gsutil). Tool ini berguna untuk mengakses cloud storage kita.

Lalu bagaimana caranya mengintegrasikan dengan docker? caranya cukup mudah ternyata, jika kita perhatikan dokumentasi pada instalasi gsutil, kita bisa menerapkan step tersebut di dalam Dockerfile kita

Hal yang harus kita miliki terlebih dahulu ialah :
- Bucket
- [HMAC Key](https://cloud.google.com/storage/docs/authentication/managing-hmackeys#create)

Memodifikasi Dockerfile yang sudah ada, kali ini saya coba memodifkasi backend golang saya

```Dockerfile
FROM golang
WORKDIR /app
COPY go.mod ./
COPY go.sum ./
RUN go mod download
COPY *.go ./
RUN go build -o /docker-go-web
EXPOSE 8080
CMD [ "/docker-go-web" ]
```

Kita akan menggunakan metode multi-stage pada docker, pastikan `nama_file` dan path sudah sesuai keinginan.

```Dockerfile
FROM ubuntu:latest as download
RUN apt-get update
RUN apt-get install \
    apt-transport-https \
    ca-certificates \
    gnupg \
    nano \
    curl -y
RUN curl https://packages.cloud.google.com/apt/doc/apt-key.gpg | gpg --dearmor -o /usr/share/keyrings/cloud.google.gpg
RUN echo "deb [signed-by=/usr/share/keyrings/cloud.google.gpg] https://packages.cloud.google.com/apt cloud-sdk main" | tee -a /etc/apt/sources.list.d/google-cloud-sdk.list
RUN apt-get update && apt-get install google-cloud-cli -y
COPY .boto /root/.boto
ENV BOTO_CONFIG=/root/.boto
RUN gsutil cp -n gs://bucket_name/folder/nama_file.zip /root/.

FROM golang
WORKDIR /app
COPY go.mod ./
COPY go.sum ./
RUN go mod download
COPY *.go ./
COPY --from=download /root/nama_file.zip /path/to/nama_file.zip
RUN go build -o /docker-go-web
EXPOSE 8080
CMD [ "/docker-go-web" ]
```
Jangan lupa kita membuat `.boto` file yaitu sebuah config gsutil yang nantinya akan di copy ke docker untuk menjalankan gsutil. Pastikan access dan secret sudah dibuat sebelumnya menggunakan HMAC Keys

```
[Credentials]
gs_access_key_id = MASUKAN_ACCESS_KEY
gs_secret_access_key = MASUKAN_SECRET_ACCESS_KEY

[Boto]
https_validate_certificates = True

[GSUtil]
content_language = en
default_api_version = 1
```
Kemudian build docker backend kita lalu coba running dan check hasilnya dengan cara `docker exec -it nama_container ls -l /path/to/file`

### Summary
Sangat mudah bukan mengakali GCP ? ini yang saya rasakan ketika sudah berurusan dengan GCP, memang bagus authentication berlapis lapis tapi itu sangat menyulitkan kita as developer, berbeda dengan S3 AWS, MiniO yang mudah diintegrasikan menggunakan API Library mereka sendiri.

Dengan menggunakan multi-stage tadi juga tidak mengikutsertakan secret key dan access key ke step terakhir, jadi bisa dipastikan aman dan tidak membuat size docker kalian boncos terkecuali file yg tadi di download dari GCP bisa 10GB sama aja boncos hahahaha.

Begitu juga dengan deployment pada kubernetes, tidak perlu manual lagi untuk mengunggah file ke pods.

Sekian terima kasih karena sudah membaca cerita singkat saya, semoga bermanfaat.