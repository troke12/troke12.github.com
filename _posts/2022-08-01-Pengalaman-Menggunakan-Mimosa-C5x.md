---
layout: post
title:  "Pengalaman Menggunakan Mimosa C5x"
tag: review network
---

Mikrotik atau Ubiquiti? Bukan! ini adalah [Mimosa](https://mimosa.co), Merk yang satu ini terbilang cukup mahal tapi beberapa produknya bisa dibilang sangat terjamin kualitasnya, oleh karena itu disini saya ingin membahas pengalaman saya menggunakan salah satu produk dari Mimosa ini yaitu [Mimosa C5x](https://mimosa.co/product/c5x)

![gambarmimosa](https://mimosa.co/uploads/C5x-homepage.jpg)

Produk ini memiliki beberapa keunggulan seperti :
- 700Mbps Capacity
- 4.9-6.4 Ghz Frequency
- Modular Antena (Bisa ganti tipe antena)
- Dan masih banyak lagi

***Mimosa C5x*** ini terbilang cukup mahal bagi saya, kalian bisa check di salah satu online shop, beruntungnya saya bekerja disalah satu ISP dan bisa mencobanya tanpa mengeluarkan uang.

![image](https://user-images.githubusercontent.com/10250068/148916034-cb240853-d85f-4ab9-893a-2156e9d5f69e.png)

*Harga pada tanggal 1/8/2022*

# Proses Perakitan

Disini saya akan membahas perakitan Mimosa C5x ini, perakitannya ternyata gampang banget!

![image](https://user-images.githubusercontent.com/10250068/148916923-b1e3d1ba-ec78-4c3e-943a-7f806b35d38b.png)

![image](https://user-images.githubusercontent.com/10250068/148917009-3fbcd95b-46a7-4bab-99e3-88a95a26b471.png)

Kalian bisa lihat sendiri, tidak membutuhkan banyak mur atau baut, pada bagian pemasangan pole nya itu cukup diputer2 aja sampe keras, jadi ini sangat menghemat waktu ketika lagi buru-buru pemasangan di customer baru.

# Setting

Saatnya melakukan proses setting perangkatnya, pada saat melakukan proses setting saya cukup dibikin kesal, kesal karena proses booting yang membutuhkan cukup banyak waktu agar bisa masuk ke Dashboard namun untuk kualitas UI Web dashboardnya sangat mudah dipahami

### Konek ke laptop dulu

![image](https://user-images.githubusercontent.com/10250068/148917948-4b1c22b5-bcf7-4b8d-995a-0a4445939654.png)

### Mimosa start

Setelah menghubungkan laptop dengan perangkat, masuk ke IP (`192.168.1.20`) default mereka, tapi sebelum itu kita harus WAJIB melakukan update firmware terlebih dahulu, silahkan download di websitenya dulu.

![image](https://user-images.githubusercontent.com/10250068/148918846-aab5943e-ffa1-4f86-a5b9-6960fa8b6b9e.png)

Setelah itu unlock perangkat kita ke website [Mimosa Start](https://mimosa.co/start)

![image](https://user-images.githubusercontent.com/10250068/148918348-950e1fbb-9ac6-4d52-b36b-b9a275e06122.png)

Pada halaman website mimosa, kalian diwajibkan untuk melakukan unlock perangkat mimosa, jadi kalian cukup memasukan serial number device mimosa kalian ke websitenya, nanti kalian otomatis akan mendapatkan kode random yang nantinya akan dipakai untuk manual unlock pada perangkat mimosa kalian.

### Mimosa WebUI

Setelah proses unlock otomatis kita masuk ke WebUI nya mimosa, seperti inilah tampilan ketika pertama kali digunakan :

![image](https://cdn.discordapp.com/attachments/874251888357441537/930360529514553344/Screenshot_173.png)

Langsung aja kita masuk ke settingan Wirelessnya :

![gambar](https://cdn.discordapp.com/attachments/874251888357441537/930360529933971476/Screenshot_174.png)

Jadi saya pake PTP agar bisa menggunakan Wireless Mode nya, kalau PTMP tidak bisa, begitu pula di perangkat yang lagi satu juga saya samakan settingannya

# Pemasangan di tower

Oke karena sudah selesai setting, kita lanjut ke proses pemasangannya seperti inilah proses pemasangannya :

![image](https://user-images.githubusercontent.com/10250068/148920168-25035c9b-b5a0-45f8-9941-2c8595635317.png)

Ini saya pasang di kantor saya yg paling tinggi, *Maaf muka saya jelek*

![image](https://user-images.githubusercontent.com/10250068/148920293-e05411d1-1809-435b-85ae-85b4868182a4.png)

Dan ini adalah teman saya melakukan pemasangan di Tower kedua kami, jaraknya sekitar 5.7 Kilometer dari Tower kantor

# Hasil ketika kedua perangkat sudah terhubung

Hasilnya sangat memuaskan, kenapa begitu? karena sebelumnya kami sudah memakai perangkat dari mikrotik yaitu Metal, yg dimana kestabilan signal tidak dapat dipastikan dan selalu drop, capacity kecil dan perbedaannya ketika menggunakan mimosa jauh diatas kata memuaskan, intinya kita pakai mimosa untuk menggantikan perangkat yang sebelumnya.

Hasilnya seperti ini, dengan kapasitas Tx/Rx = 651Mbps/392Mbps, sinyal stabil di 50an

![image](https://user-images.githubusercontent.com/10250068/148921144-e76467b0-44c7-494b-84e1-8f36e6a18476.png)

# Summary

Jadi tunggu apalagi? ayo pakai mimosa kwkwkw, sekian terima kasih, artikel ini hanya sekedar berbagi pengalaman luar biasa ketika bekerja sebagai teknisi jaringan.

