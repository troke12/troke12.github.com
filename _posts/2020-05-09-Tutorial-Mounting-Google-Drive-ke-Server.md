---
layout: post
title:  "Tutorial Mounting Google Drive ke Server"
tag: tutorial
---

Seperti halnya dengan flashdisk, tinggal colok pasang, namun berbeda.

Saya akan memberi cara bagaimana mounting google drive di server atau non-server, disini server saya menggunakan OS Ubuntu 18.04, kalian bebas menggunakan apa aja, lalu yang harus dilakukan pertama adalah menginstall [google-drive-ocamlfuse](https://github.com/astrada/google-drive-ocamlfuse)

```
sudo add-apt-repository ppa:alessandro-strada/ppa
sudo apt-get update
sudo apt-get install google-drive-ocamlfuse
```
jika sudah selesai menginstall gunakan perintah ini
```
google-drive-ocamlfuse -headless -id 202264815644 -secret X4Z3ca8xfWDb1Voo-F9a7ZxJ 
```
setelah itu nanti akan muncul pesan untuk mendapatkan verified code, buka link yang ada di terminal pada browser lalu masukan akun google drive kalian
dan tekan **Allow**, nanti otomatis akan mendapatkan verified code token, copy token tersebut dan masukkan ke terminal.

hasilnya kira2 akan seperti ini :
```bash
root@ochi:~# google-drive-ocamlfuse -headless -id 202264815644 -secret X4Z3ca8xfWDb1Voo-F9a7ZxJ
Please, open the following URL in a web browser: https://accounts.google.com/o/oauth2/auth?client_id=202264815644&redirect_uri=urn%3Aietf%3Awg%3Aoauth%3A2.0%3Aoob&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fdrive&response_type=code&access_type=offline&approval_prompt=force
Please enter the verification code: CODETOKEN YANG TADI
Access token retrieved correctly. <---TOKEN SUKSES
```



lalu kita bikin sebuah direktori sebagai penyimpanan lokal kita
```
mkdir googlebackup
```

saatnya mounting folder kita
```
google-drive-ocamlfuse /root/googlebackup
```

jika sudah bisa cek menggunakan `df -h` 
```bash
root@ochi:~# df -h
Filesystem              Size  Used Avail Use% Mounted on
udev                    960M     0  960M   0% /dev
tmpfs                   195M   21M  175M  11% /run
/dev/sda1                20G  8.3G   11G  43% /
tmpfs                   973M     0  973M   0% /dev/shm
tmpfs                   5.0M     0  5.0M   0% /run/lock
tmpfs                   973M     0  973M   0% /sys/fs/cgroup
/dev/sda15              105M  3.6M  101M   4% /boot/efi
tmpfs                   195M     0  195M   0% /run/user/0
datenshi-pro            1.0P     0  1.0P   0% /root/storage
s3fs                    256T     0  256T   0% /root/dtbackup
google-drive-ocamlfuse   17G  6.5G   11G  39% /root/googlebackup <---GDRIVE KITA
```

selamat mencoba!
