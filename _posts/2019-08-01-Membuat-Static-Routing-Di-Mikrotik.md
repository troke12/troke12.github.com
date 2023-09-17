---
layout: post
title:  "Membuat Static Routing Di Mikrotik"
tag: tutorial network
---

### Persiapan
- Internet Connection dari ISP
- Mikrotik
- Kabel LAN

## Penjelasan
Dengan menggunakan metode Static Routing, IP yang didapatkan oleh mikrotik tidak akan berubah-ubah, Umumnya orang lain lebih memilih menggunakan Dynamic Routing daripada menggunakan Static Routing karena lebih gampang menggunakan Dynamic, tapi Static Routing juga sangat gampang dilakukan, oleh karena itu saya disini akan membuat static routing yang mudah dipahami oleh pemula

### Pertama
Siapkan Mikrotik apapun yg kalian punya (Disini saya memakai **RB951Ui-2ND**), nah selanjutnya kabel lan yg kalian sudah siapkan pasang di ether1 (Port1) dan ether2 (Port2), untuk Port1 itu jalur masuknya Internet dari Modem ISP, contoh : kalian punya Modem indihome, kabel lan sisi pertama untuk Indihome di port lan manapun kalian boleh pasang, nah sedangkan sisi kedua kabel lan kalian masuk ke Port1 Mikrotik kalian. Nah untuk Ether2 itu untuk Lokal Kalian, jadi kalian pasang ke mikrotik (ether2) lalu ke laptop.
![gambar1](https://cdn.discordapp.com/attachments/408950289962369025/606289857442217994/IMG_2275.JPG)

### Kedua
Nah Kalian harus masuk ke mikrotiknya terlebih dahulu menggunakan Winbox, pastikan Mikrotiknya sudah dalam keadaan reset total, jadi kita mulai dari 0 dulu. Yang harus dilakukan disini adalah mengetahui IP Modem dan segmentnya, jadi sebelum itu kita melakukan test dulu dari Modem ke laptop, kalau laptop kita dapat ip _192.168.1.3_ berarti nanti di dalam Mikrotik kita Tambahkan IP Address _192.168.1.4_ Interfacenya ether1, sebagai contoh disini IP Modem saya adalah *192.168.50.xxx* jadi saya putuskan di Addresses saya tambah *192.168.50.62/24* (kenapa 62? ~~karena negara kita adalah +62~~ karena disini kita bebas menggunakan berapapun itu asal tidak terjadi bentrok dengan ip yg lain, pastikan ip itu tidak ada yg menggunakannnya.
![gambar2](https://cdn.discordapp.com/attachments/408950289962369025/606302918194495501/Screenshot_96.png)

### Ketiga
Lalu kalian masuk ke IP > Routes, disini sangat penting karena kita akan memasukan Gateway modem kita agar bisa mendapatkan akses dari modem ke mikrotik, simplenya kita masukkan kunci ke gembok. Nah IP Gateway modem saya disini adalah 192.168.50.1 jika segment ip kalian 192.168.1.xxx berarti pakai 192.168.1.1 untuk gateway nya, Pastikan nanti gateway yg kalian masukkan itu reachable karena kalau tidak reachable kalian tidak akan bisa mendapatkan akses internet.
![gambar3](https://cdn.discordapp.com/attachments/408950289962369025/606302919658307606/Screenshot_97.png)

### Keempat
Setelah itu masuk ke IP > Firewall, di Firewall pilih tab NAT lalu tambahkan seperti ini **Chain : srcnat, Out.Interface : ether1** (ini harus teliti, jika tidak ip dari gateway masih akan terblock oleh mikrotik, pastikan disini memilih interface yg dimana interface nya tersebut adalah sumber dari Internet kalian, lalu pada Tab *Action* kalian pilih **masquerade** lalu klik Apply dan OK.
![gambar4](https://cdn.discordapp.com/attachments/408950289962369025/606302925500710912/Screenshot_98.png)
![gambar41](https://cdn.discordapp.com/attachments/408950289962369025/606302930462572564/Screenshot_99.png)

### Kelima
Setelah itu kalian buka New Terminal dan ping 8.8.8.8, jika bisa... artinya kalian sudah bisa memiliki akses internet dari Modem ke mikrotik kalian.
![gambar5](https://cdn.discordapp.com/attachments/408950289962369025/606302935516839947/Screenshot_100.png)

### Keenam
Selanjutnya kita akan menyalurkan internet tersebut ke Laptop, disini Ether2 yg sudah dipasangkan kabel ke Laptop kalian skrng kita setting agar bisa mendapatkan akses internet, Jadi kita kembali lagi ke **IP -> Addresses** disini kalian bebas menggunakan IP apapun, Saya disini menggunakan 192.168.2.1/24 biar lebih simple dan pastikan interface nya adalah **ether2**.
![gambar6](https://cdn.discordapp.com/attachments/408950289962369025/606302940096888872/Screenshot_101.png)

### Ketujuh
Lalu masuk ke **IP -> DHCP Server** disini Fungsi DHCP Server, bagi kalian yang belum tau Fungsi dari DHCP Server ialah membuat IP yang tadi kita buat di Addresses dibagi-bagi/disebar oleh DHCP Server ini, jadi kalian hanya perlu membuatnya dengan mengklik tombol **DHCP Setup** dengan step seperti ini : 
DHCP Setup > interface = ether2 > DHCP Addresses Space Next aja > Gateway DHCP Network Next aja > Addresses to Give out Next aja > DNS Server (bebas isi 8.8.8.8 atau 1.1.1.1 boleh) > Lease Time Next aja > Finish
![gambar7](https://cdn.discordapp.com/attachments/408950289962369025/606302945071464449/Screenshot_102.png)

### Kedelapan
Setelah selesai membuat DHCP Server, kalian boleh mencabut kabel LAN kalian dari Mikrotik ke Laptop lalu pasang kembali, atau dengan cara Disable Enable Interface Network di Control Panel > Network and Internet > Network Connections, disini saya menggunakan disable dan enable saja karena males cabut hehehe.
![gambar8](https://cdn.discordapp.com/attachments/408950289962369025/606302950712934420/Screenshot_103.png)
![gambar81](https://cdn.discordapp.com/attachments/408950289962369025/606302956597280769/Screenshot_104.png)

### Kesembilan
Jika sudah terbuhung tandanya akan seperti digambar ini, nanti di Network and Sharing Center muncul **Internet** yang artinya sudah terhubung dengan koneksi Mikrotik ke laptop dan lakukan test ping di CMD.
![gambar9](https://cdn.discordapp.com/attachments/408950289962369025/606302960816881664/Screenshot_105.png)

### Kesepuluh
Nah itu saja tutorial dari saya, mudah-mudahan bermanfaat bagi kawan-kawan semua dan mohon maaf bila ada salah dan tolong dibenarkan jika saya salah, sekian dan Terima kasih sudah membaca artikel saya :)
