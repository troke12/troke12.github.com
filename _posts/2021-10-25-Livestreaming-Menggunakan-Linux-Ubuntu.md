---
layout: post
title:  "Livestreaming Menggunakan Linux Ubuntu"
date:   2021-10-25
project: post
excerpt: "Live stream dengan cara menggunakan linux ubuntu server"
comments: true
---

Baik, kali ini kita akan membahas livestreaming, akhir-akhir ini banyak banget yang mencoba untuk meniti karir sebagai streamer, mostly akhir-akhir ini kita sering melihat trend Virtual Youtuber yang makin hari makin banyak bermunculan, tetapi tidak hanya itu juga, kalian mungkin pernah melihat di Youtube "lofi hip hop radio - beats to relax/study to" atau "jazz/lofi hip hop radio🌱chill beats to relax/study to [LIVE 24/7]" kan? nah channel tersebut diketahui sudah melakukan streaming dalam 24 jam bahkan sudah setahun lebih mereka melakukan penyiaran musik di kanal youtube, bagaimana bisa mereka melakukan itu? nah disini kita akan bahas caranya, perlu diperhatikan disini saya cuma akan membahas secara dasar dan tidak lebih karena ini juga hasil daripada eksperiment saya sendiri.

### Streaming 24/7
![image](https://user-images.githubusercontent.com/10250068/138690302-9a2b92d3-29b2-49a8-b816-1a14bb4aaf18.png)

Yap, ss diatas merupakan contoh dari streaming 24 jam.

Secara umum tidak mungkin orang bisa melakukan livestreaming selama 24 jam lebih, kecuali orang tersebut punya niat untuk melakukannya. Cara paling efektif ialah menggunakan server, jika kalian punya server nganggur bisa kalian ikuti cara saya

### Instalasi

**Yang dibutuhkan :**
- Ubuntu Server 18.04 (bebas)
- ffmpeg
- Youtube Account
- Video Pekora Joget-Joget (Kalian bebas pakai apa aja)

Perlu diperhatikan untuk server kalian tidak perlu membutuhkan spesifikasi yang tinggi dan kalau bisa carilah yang gratis, kalian bisa mendapatkannya di AlibabaCloud secara trial selama 12 bulan.
```bash
root@ochirecloud:~# screenfetch
                          ./+o+-       root@ochirecloud
                  yyyyy- -yyyyyy+      OS: Ubuntu 18.04 bionic
               ://+//////-yyyyyyo      Kernel: x86_64 Linux 4.15.0-45-generic
           .++ .:/++++++/-.+sss/`      Uptime: 163d 6h 15m
         .:++o:  /++++++++/:--:/-      Packages: 981
        o:+o+:++.`..```.-/oo+++++/     Shell: bash 4.4.20
       .:+o:+o/.          `+sssoo+/    CPU: Intel Xeon Platinum 8163 @ 2.5GHz
  .++/+:+oo+o:`             /sssooo.   GPU: cirrusdrmfb
 /+++//+:`oo+o               /::--:.   RAM: 525MiB / 985MiB
 \+/+o+++`o++o               ++////.
  .++.o+++oo+:`             /dddhhh.
       .+.o+oo:.          `oddhhhh+
        \+.++o+o``-````.:ohdhhhhh+
         `:o+++ `ohhhhhhhhyo++os:
           .o:`.syhhhhhhh/.oo++o`
               /osyyyyyyo++ooo+++/
                   ````` +oo+++o\:
                          `oo++.
root@ochirecloud:~#
```
Selanjutnya kita akan memulai pemasangan ffmpeg, buat yang belum tau ffmpeg ini adalah sebuah library dan program yang digunakan untuk menghandle video, audio dan media lainnya secara command line (CMIIW) untuk lebih lengkap bisa ke websitenya.

Update dulu guys
```
$ sudo apt-get update # update package dulu guys
```
Kemudian kita install ffmpeg
```
$ sudo apt-get install ffmpeg
```
Tunggu hingga selesai lalu check ffmpeg menggunakan argument version
```
$ ffmpeg -version
```
Output nya akan seperti ini
```
$ ffmpeg -version
ffmpeg version N-58069-gc253b180cb-static https://johnvansickle.com/ffmpeg/  Copyright (c) 2000-2021 the FFmpeg developers
built with gcc 8 (Debian 8.3.0-6)
configuration: --enable-gpl --enable-version3 --enable-static --disable-debug --disable-ffplay --disable-indev=sndio --disable-outdev=sndio --cc=gcc --enable-fontconfig --enable-frei0r --enable-gnutls --enable-gmp --enable-libgme --enable-gray --enable-libaom --enable-libfribidi --enable-libass --enable-libvmaf --enable-libfreetype --enable-libmp3lame --enable-libopencore-amrnb --enable-libopencore-amrwb --enable-libopenjpeg --enable-librubberband --enable-libsoxr --enable-libspeex --enable-libsrt --enable-libvorbis --enable-libopus --enable-libtheora --enable-libvidstab --enable-libvo-amrwbenc --enable-libvpx --enable-libwebp --enable-libx264 --enable-libx265 --enable-libxml2 --enable-libdav1d --enable-libxvid --enable-libzvbi --enable-libzimg
libavutil      57.  2.100 / 57.  2.100
libavcodec     59.  3.102 / 59.  3.102
libavformat    59.  4.101 / 59.  4.101
libavdevice    59.  0.100 / 59.  0.100
libavfilter     8.  0.103 /  8.  0.103
libswscale      6.  0.100 /  6.  0.100
libswresample   4.  0.100 /  4.  0.100
libpostproc    56.  0.100 / 56.  0.100
```
Jika kalian mengalami trouble ketika pemasangan ffmpeg, kalian bisa melakukan manual build dan mengatur PATH nya jika sudah selesai build ffmpeg secara manual.

Selanjutnya kita akan akan pergi menuju Youtube Livestream Dashboard

![image](https://user-images.githubusercontent.com/10250068/138695806-66650a8c-21c4-4e0f-9f12-e31326802e8e.png)

Pada bagian Streaming Settings kalian catat dulu stream key nya nanti format streaming menggunakan ffmpeg akan seperti ini
```
rtmp://a.rtmp.youtube.com/live2/STREAMING_KEY
```
Untuk judul, thumbnail dan lain-lain kalian persiapkan dulu hingga selesai.

Lalu kita akan coba melakukan streaming menggunakan ffmpeg

Ketik ini pada terminal
```
ffmpeg -re -stream_loop -1 -i pekora.mp4 -c copy -f flv -flvflags no_duration_filesize rtmp://a.rtmp.youtube.com/live2/STREAMING_KEY
```

Perlu diperhatikan ini adalah basic command dari ffmpeg untuk melakukan streaming ke youtube, disana terlihat `pekora.mp4` yang sudah saya siapkan dan ada options `-stream_loop` yang artinya video `pekora.mp4` akan diputar secara berulang-ulang, untuk options lainnya masih banyak yang belum saya tau, jadi saya cuma menjelaskan yang saya tau aja 😄

Tampilan outputnya akan jadi seperti ini :

![image](https://user-images.githubusercontent.com/10250068/138698459-da751a2f-dbc1-493c-b977-83b8a7303ddb.png)

![image](https://user-images.githubusercontent.com/10250068/138698846-9f38de81-e52b-409e-b25c-d3bb8398f67f.png)

### Hasil pengujian dari saya

![image](https://user-images.githubusercontent.com/10250068/138699140-5412ab7e-d892-4ccf-a7b1-70c858652528.png)

Pada streaming pertama saya gagal mencapai lebih dari 1 jam, karena pada opsi ffmpeg tidak berisi `no_duration_filesize` kalau ndak isi bakalan mentok di satu jam dan stream otomatis di stop.

Pada streaming kedua saya berhasil mencapai 65 jam dan itu juga di stop otomatis oleh ffmpeg sendiri karena error, errornya sampai sekarang masih belum saya telusuri lagi tapi bagi saya ini sudah lebih dari cukup.

Itu saja mungkin dari saya, semoga artikel ini bermanfaat bagi kalian! terima kasih sudah membaca!
