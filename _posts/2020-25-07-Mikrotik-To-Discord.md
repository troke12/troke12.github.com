---
layout: post
title:  "Mikrotik To Discord"
tag: source-code project tutorial
---

Sebuah eksperimen yang berawal dari kegabutan saya, nah sebenernya kita pernah mendengar hal yang namanya mengirim pesan melalui mikrotik ke telegram bukan? harusnya itu sudah banyak tutorial yang beredar di google, dulu juga saya menggunakannya untuk mendedeteksi siapa saja yang login melalui hotspot mikrotik saya lalu dikirim ke Telegram, namun karena jarang banget buka Telegram dan lebih berfokus menggunakan aplikasi yang bernama Discord, yap Discord adalah sebuah platform yang memungkinkan penggunanya untuk saling mengirim chat, gambar, audio, video, bahkan hingga share screen. 

### Discord

Discord sangat memahami betul keperluan bagi Developer, fitur-fitur yang diberikan oleh Discord kepada Developers pun sangat banyak namun memiliki batasan setiap fiturnya, tapi salah satu fitur yang bernama **Webhook** membuat saya tertarik, fitur ini memudahkan kita mengirim pesan dalam bentuk payload json yang akan dikirim ke channel yang sudah kita buat.

Disini saya tidak akan menjelaskan bagaimana cara membuat webhook discord secara lengkap, namun saya akan mereferensikan kalian untuk melihat dokumentasi pembuatan discord webhook [disini](https://support.discord.com/hc/en-us/articles/228383668-Intro-to-Webhooks)

Oke lanjut, tentu jika kalian sudah memahami dan melakukan testing bagaimana cara kerja webhook, kalian mungkin akan melakukan testing pertama kali menggunakan metode **cURL**, yap mungkin seorang developer sudah sangat familiar dengan ini, jadi cURL ini adalah sebuah command yang sebagian besar ada di sistem berbasis unix, tentu jika kalian menggunakan windows kalian harus mengunduh dan memasangnya sendiri, cURL berfungsi untuk mengecek status dari sebuah URL dan juga bisa untuk transfer data, untuk mengetahui lebih lengkap contoh cURL bisa di cek [disini](https://devhints.io/curl#examples)

### MikroTik

MikroTik OS / RouterOS sendiri aslinya adalah unix dengan menggunakan base Linux v2.6 kernel, namun dengan beberapa perubahan-perubahan tentunya agar memudahkan pemasangan RouterOS ke device tertentu agar lebih fleksibel untuk kedepannya. Jadi jika command yang akan kita gunakan untuk testing webhook ialah cURL, namun di RouterOS sendiri berbeda, command yang digunakan ialah **fetch** yang berada pada section tools, kalau versi GUI kita tidak akan melihatnya, karena ini hanya tersedia dalam CLI (Command Line Interface).

![image](https://user-images.githubusercontent.com/10250068/134517499-3fed91ba-d344-45c8-9a79-b382ea3b7ae6.png)

Bisa dilihat pada screenshot diatas, fetch memiliki beberapa opsi-opsi yang bisa kalian gunakan, kalian bisa mengetahui petunjuk penggunaan opsi tersebut di halaman [Wikipedia mikrotik/Tools/Fetch](https://wiki.mikrotik.com/wiki/Manual:Tools/Fetch#Properties)

### Testing Webhook

Mari kita melakukan testing! sebelum itu kalian harus mempersiapkan webhook discord terlebih dahulu, sesudah itu mari kita pahami struktur dari sebuah discord webhook

![image](https://user-images.githubusercontent.com/10250068/134519861-72f1b457-e283-4376-8ba9-10039da613a7.png)

Dari screenshot diatas hal yang paling penting adalah `token` dan `id` , tentunya URL Webhook ini tidak boleh disebar kemana-mana / exposed ke public, jika URL Webhook kalian terexpose pastikan kalian cepat-cepat menghapusnya!

Lalu bagaimana cara mengirimnya? sebelum mengirim kita harus memahami struktur payload dari webhook ini

```json
{
  "username": "Webhook",
  "avatar_url": "https://i.imgur.com/4M34hi2.png",
  "content": "Text message. Up to 2000 characters.",
  "embeds": [
    {
      "author": {
        "name": "Birdie♫",
        "url": "https://www.reddit.com/r/cats/",
        "icon_url": "https://i.imgur.com/R66g1Pe.jpg"
      },
      "title": "Title",
      "url": "https://google.com/",
      "description": "Text message. You can use Markdown here. *Italic* **bold** __underline__ ~~strikeout~~ [hyperlink](https://google.com) `code`",
      "color": 15258703,
      "fields": [
        {
          "name": "Text",
          "value": "More text",
          "inline": true
        },
        {
          "name": "Even more text",
          "value": "Yup",
          "inline": true
        },
        {
          "name": "Use `\"inline\": true` parameter, if you want to display fields in the same line.",
          "value": "okay..."
        },
        {
          "name": "Thanks!",
          "value": "You're welcome :wink:"
        }
      ],
      "thumbnail": {
        "url": "https://upload.wikimedia.org/wikipedia/commons/3/38/4-Nature-Wallpapers-2014-1_ukaavUI.jpg"
      },
      "image": {
        "url": "https://upload.wikimedia.org/wikipedia/commons/5/5a/A_picture_from_China_every_day_108.jpg"
      },
      "footer": {
        "text": "Woah! So cool! :smirk:",
        "icon_url": "https://i.imgur.com/fKL31aD.jpg"
      }
    }
  ]
}
```

Hasil outputnya akan tampil seperti ini:

![image](https://user-images.githubusercontent.com/10250068/134521030-ae480b90-6d3b-4231-920f-89a8490ed392.png)

*Referensi dari [birdie0 Discord Webhook Guide](https://birdie0.github.io/discord-webhooks-guide/discord_webhook.html)*

Wow kebanyakan ya? yap memang panjang banget, jika kalian adalah orang yang sangat simpel atau gak mau ribet, mungkin kalian bisa mengikuti cara saya, cukup ambil bagian ini

```json
{"content":"test"}
```

Hasil outputnya akan jadi seperti ini 

![image](https://user-images.githubusercontent.com/10250068/134522052-2e044065-6685-477f-a7da-5e299ac22665.png)

Lalu bagaimana kita menjalankannya pada mikrotik? sangat mudah cukup ikuti command ini dan jalankan,

```bash
/tool fetch http-method=post http-header-field="Content-Type: application/json" http-data="{\"content\":\"test\"}" url="MASUKIN_DISCORD_WEBHOOK_URL_DISINI"
```

Kenapa kok `{"content":"test"}` berubah menjadi `{\"content\":\"test\"}` ? ini namanya escape json, jadi karena payloadnya ini ada di dalem quotes `"` dan isinya quotes lagi otomatis jadi bentrok (cmiiw) / otomatis dia jadinya special character, saya udah coba single quote `'` atau backtick ``` ` ``` hasilnya ga bisa, jadi mikrotik sendiri harus pake quotes `"`, terus biar gampang gimana? pake tools online [escape json converter](https://onlinejsontools.com/escape-json) tinggal copy-paste saja lalu dia nanti otomatis ke convert.

![image](https://user-images.githubusercontent.com/10250068/134544594-e5f361dc-f3fd-4189-bb90-c07a2e6b1f0d.png)

dilihat dari `status` itu menandakan sudah selesai mengirim pesan dan hasilnya akan jadi seperti ini :

![image](https://user-images.githubusercontent.com/10250068/134544791-d305e9bb-85fb-47ed-ad76-4a4de78a7b43.png)

### Netwatch

Lanjut kita akan mengimplementasikannya ke **Netwatch**, dimana semua device yang sudah kita catat terpantau disana, berikut ini adalah screenshotnya

![image](https://user-images.githubusercontent.com/10250068/134600484-67fba1d3-82c4-46c6-b5d9-7813d3160e1b.png)

Jadi saya disini menerapkannya pada INDIHOME saya, pada bagian `content` saya ubah dengan `INDIHOME HIDUP` dan hasilnya akan jadi seperti ini

![image](https://user-images.githubusercontent.com/10250068/134600590-767630d0-c2ee-4b6d-b578-c22d56c1c346.png)

Pada bagian content kalian bisa ubah sesuka hati, misalnya jadi seperti ini

![image](https://user-images.githubusercontent.com/10250068/134603444-d96d7db4-b891-4eff-955a-33553f9d861e.png)

```json
{
  "content": "Memanggil paduka <@176829603321610240>",
  "embeds": [
    {
      "author": {
        "name": "OCHI WIFI",
        "url": "https://www.troke.id/",
        "icon_url": "https://cdn.discordapp.com/attachments/874251888357441537/890757320466911252/pngegg.png"
      },
      "color": 15258703,
      "fields": [
        {
          "name": "Status Perangkat INDIHOME",
          "value": "HIDUP"
        }
      ]
    }
  ]
}
```

![image](https://user-images.githubusercontent.com/10250068/134604226-78b85d3a-552c-47eb-8a30-0ab5d1d4cd77.png)


- Pada bagian `<@176829603321610240>` itu adalah ID discord saya, jadi nanti bakalan di mention otomatis
- Perlu diperhatikan jika kalian melakukan converter, untuk bagian URL yang ada `"url": "https://www.troke.id/"` akan berubah menjadi `"url\": \"https:\/\/www.troke.id\/\"` karena akibat convert, jika kalian tidak mengubahnya, tentu mikrotik tidak akan bisa melakukan pengiriman message, ini dikarenakan mikrotik tidak bisa membacanya / error, oleh karena itu segeralah ganti menjadi `"url\": \"https://www.troke.id/\"` dan ini berlaku bagi semua URL, jika ada URL lain segera ganti / samakan dengan URL sebelum di convert.
- Untuk Color kalian bisa gunakan [SpyColor](https://spycolor.com) untuk mendapatkan HEX Numbernya


### Kesimpulan

Memang agak ribet, tapi jika kita mau belajar memahami dan mencoba tentunya yang namanya ribet itu pasti gak ada! Saya sendiri sudah mencoba beberapa kali bereksperimen dan hasilnya sudah bbrp kali gagal, gagal karena saya ga baca :D, jadi penting banget jika kalian ingin memahami Discord Webhook dan MikroTik kalian harus benar-benar giat membaca, dari apa yang saya hasilkan ini saya bisa mengakalinya dengan cara membuat script simple dalam bentuk PHP yang sudah di hosting ke Heroku, kalian bisa membaca di project [MikrotikToDiscord](https://github.com/troke12/MikrotikToDiscord), sekian terima kasih! semoga bermanfaat!
