# Panduan Membuka Website MAGSTORE — Termasuk Cara Hosting (Alternatif Permanen)

Dokumen ini menjelaskan **semua cara membuka website Anda**, lalu fokus pada cara
**membuat hosting** supaya website bisa dibuka lewat **link permanen** — sebagai
**alternatif** dari cara yang sekarang (yang butuh laptop menyala).

---

## Ringkasan: 3 cara membuka website

| Cara | Alamat contoh | Butuh laptop nyala? | Stabil & permanen? | Untuk siapa |
|---|---|---|---|---|
| **1. Lokal** | `http://127.0.0.1:8000` | Ya | Tidak (khusus laptop sendiri) | Uji coba / kerja di laptop |
| **2. Tunnel (Cloudflare)** | `https://xxxx.trycloudflare.com` | Ya | Tidak (link berubah tiap restart) | Demo cepat / sementara |
| **3. HOSTING** ⭐ | `https://magstore.infinityfreeapp.com` | **Tidak** | **Ya** | **Pelanggan, link permanen** |

> **Cara 3 (hosting) adalah "alternatif permanen"** yang Anda maksud: walau laptop
> dimatikan, website tetap bisa dibuka siapa pun dari mana saja.

---

## Bagian A — Hosting InfinityFree (manual, disarankan)

> **PENTING — batas InfinityFree:**
> - Ukuran maksimal **10 MB per file**. Paket ZIP (±14 MB) **tidak bisa diupload
>   langsung**. Extract dulu di komputer, lalu upload **isi foldernya** lewat FTP.
> - File **PHP/JS/HTML maksimal 1 MB**. Paket sudah disusun agar aman.
> - **Jangan memakai `install.php` + `magstore-app.zip`** di InfinityFree, karena
>   zip installer ±14 MB dan akan ditolak hosting.
> - PHP di InfinityFree sekarang **8.4 otomatis** — tidak perlu ganti versi.

### Yang perlu disiapkan
- 1 email aktif (untuk verifikasi pendaftaran).
- Aplikasi FTP gratis **FileZilla** → https://filezilla-project.org
- File `paket-hosting.zip` dari folder proyek
  (`C:\Users\ASUS\Documents\kasir\paket-hosting.zip`).
- Panduan langkah-demi-langkah + troubleshooting: `deploy/CARA-INSTALL.md`.

### Langkah 1 — Daftar hosting & buat subdomain
1. Buka https://www.infinityfree.com → **Sign Up** (isi email + password).
2. Verifikasi lewat email yang dikirim InfinityFree, lalu login.
3. Klik **Create Account**.
4. Pilih subdomain gratis, contoh: `magstore`
   → link permanen Anda menjadi: **https://magstore.infinityfreeapp.com**
5. Tunggu status hosting menjadi **Active** (biasanya beberapa menit).
6. Catat data berikut (ada di halaman detail akun):
   - **FTP**: Host, Username, Password (port 21)
   - Tombol **Control Panel** (untuk database, phpMyAdmin, dll)

> **SSL/HTTPS:** subdomain gratis otomatis memakai HTTPS. Gunakan alamat
> **tanpa `www`** (contoh `https://magstore.infinityfreeapp.com`),
> karena `www.` tidak tercakup sertifikat gratisnya.

### Langkah 2 — Buat 1 database
1. Di panel akun → **MySQL Databases** → **Create Database**.
2. Catat **4 data** ini (dipakai di langkah 3):
   - **DB Host** (contoh `sql123.infinityfree.com`)
   - **Nama Database** (contoh `if0_12345678_kasir`)
   - **DB Username** (contoh `if0_12345678`)
   - **DB Password**

### Langkah 3 — Siapkan file `.env`
1. **Extract** `paket-hosting.zip` di komputer.
2. Buka file **`.env`** di dalamnya dengan Notepad (file tersembunyi — aktifkan
   "Show hidden files" di File Explorer).
3. Isi:
   ```
   APP_URL=https://magstore.infinityfreeapp.com
   DB_HOST=sql123.infinityfree.com
   DB_DATABASE=if0_12345678_kasir
   DB_USERNAME=if0_12345678
   DB_PASSWORD=passwordDatabaseAnda
   ```
   Baris `APP_KEY` **biarkan apa adanya**. `APP_ENV=production` dan
   `APP_DEBUG=false` sudah terisi.
4. Simpan file.

### Langkah 4 — Import database
1. Di panel → **phpMyAdmin** → pilih database Anda.
2. Tab **Import** → pilih `database/kasir_db.sql` dari folder hasil extract →
   **Import**. Jika diminta, centang **Drop all tables** dulu.
3. Pastikan muncul **"Import has been successfully finished"**.
   Database hosting ini bersih: 10 tabel + produk, kategori, dan akun admin
   (belum ada pelanggan atau pesanan contoh).

### Langkah 5 — Upload via FTP
1. Buka **FileZilla**, hubungkan ke FTP dari Langkah 1 (port **21**).
   Jika ada peringatan sertifikat → **Trust/OK**.
2. Di sisi server, masuk folder **`htdocs`**. Hapus file bawaan
   (`index2.html`, `default.php`, halaman "coming soon") bila ada.
3. Aktifkan **Server → Force showing hidden files** (agar `.env` & `.htaccess` terlihat).
4. Di sisi komputer, buka folder hasil extract. Pilih **SEMUA isi** (Ctrl+A):
   `app`, `bootstrap`, `config`, `database`, `public`, `resources`, `routes`,
   `storage`, `vendor`, `artisan`, `.env`, `.htaccess`, dst.
   **Jangan** mendrag folder induknya (nanti menjadi `htdocs/kasir/...`).
5. Drag semuanya ke `htdocs`. Jika ditanya **Overwrite** → pilih **Overwrite**.
6. Tunggu selesai. Folder `vendor` berisi ±8.000 file; upload bisa
   **10–30 menit**. Jangan tutup FileZilla.

### Langkah 6 — Uji coba
1. Buka `https://<subdomain-anda>` (tanpa `www`). Jika muncul halaman
   pemeriksaan keamanan InfinityFree, tunggu/refresh.
2. Pastikan halaman depan, gambar produk, dan CSS tampil.
3. Login:
   - **Admin**: `/login` → PIN **20090816**.
   - **Pelanggan**: `/register` → daftar sendiri (username & password pilihan).
     Setelah login, pelanggan bisa mengubah username/password lewat **Akun Saya**.
4. Setelah login admin, buka menu **Akun Admin** dan ganti PIN awal dengan PIN
   rahasia Anda sendiri.
5. Coba buat pesanan + unggah bukti bayar (maks **10 MB**, JPG/PNG/WEBP).

**Selesai!** Website sudah online dan bisa dibuka walau laptop mati.

### Login bawaan
| Peran | Cara login | Kredensial |
|---|---|---|
| Admin | halaman `/login` → tab Admin | **PIN 20090816** |
| Pelanggan | halaman `/register` | Daftar sendiri (username & password pilihan) |

> Simpan PIN admin baik-baik dan jangan dibagikan. PIN bisa diganti kapan saja
> lewat menu **Akun Admin** setelah login.

---

## Bagian B — Kenapa installer otomatis tidak dipakai di InfinityFree?

Installer (`install.php` + `magstore-app.zip`) memang praktis di hosting lain,
tetapi **tidak bisa dipakai di InfinityFree** karena:

- `magstore-app.zip` berukuran ±14 MB, sedangkan InfinityFree membatasi
  **10 MB per file** — file zip akan otomatis ditolak/dihapus.
- InfinityFree juga membatasi file PHP/JS/HTML maksimal **1 MB**.

Karena itu gunakan **Bagian A (upload isi folder via FTP)**. Paket
`paket-hosting.zip` sudah dibuat agar semua file di dalamnya berada di bawah
batas tersebut (Pint dan file Faker yang kebesaran tidak disertakan).

---

## Bagian C — Menjadikan Hosting sebagai "Alternatif Membuka Website"

Setelah hosting aktif, lakukan ini supaya praktis dipakai sehari-hari:

1. **Bookmark** di browser HP & komputer: simpan `https://<subdomain-anda>`.
2. **QR Code**: buat QR dari link tersebut (situs pembuat QR gratis mana pun),
   cetak dan tempel di toko supaya pelanggan tinggal scan.
3. **Bagikan** link itu ke pelanggan (WhatsApp/Instagram) — tidak perlu lagi
   kirim link tunnel yang berubah-ubah.
4. **Dua cara tetap bisa dipakai berdampingan:**
   - Laptop nyala → boleh tetap pakai cara lokal/tunnel untuk uji coba cepat.
   - Laptop mati / mau dibagikan → **pakai link hosting**. Ini yang permanen.
5. **Ganti gambar produk**: login admin → Produk → Edit → unggah gambar baru
   (otomatis tersimpan di hosting).

---

## Bagian D — Troubleshooting Singkat

| Gejala | Solusi |
|---|---|
| **Error 500** | Pastikan folder `storage/` & `bootstrap/cache/` bisa ditulis; cek `.env` (APP_KEY harus ada); lihat `storage/logs/laravel.log`. |
| **"No application encryption key"** | Baris `APP_KEY` di `.env` hilang/terpotong. |
| **Database connection error** | Periksa `DB_HOST/DB_DATABASE/DB_USERNAME/DB_PASSWORD` (nama biasanya berawalan `if0_`). |
| **CSS hilang / tampilan polos** | `.htaccess` tidak terunggah. Aktifkan "Force showing hidden files" di FileZilla, upload ulang. |
| **Gambar produk tidak muncul** | Pastikan folder `public/products/` terunggah. |
| **Tidak bisa upload ZIP 14 MB** | Memang dilarang. Extract dulu, upload isinya lewat FTP. |
| **File >10 MB gagal / hilang** | Batas InfinityFree 10 MB/file. Paket ini sudah aman; jangan tambah file besar. |
| **File PHP/JS/HTML hilang** | Batas 1 MB untuk file tersebut. Paket ini sudah aman. |
| **HTTPS "not secure"** | Buka `https://` tanpa `www`. Subdomain gratis sudah otomatis SSL. |
| **Website lambat / 508** | Batas hosting gratis; tunggu sebentar atau pantau di panel. |

---

## Bagian E — Perawatan Rutin (opsional tapi disarankan)

- **Backup database**: phpMyAdmin → **Export** minimal 1×/bulan.
- **Simpan PIN admin** baik-baik; jangan dibagikan.
- **Jangan bagikan** isi file `.env` ke siapa pun.
- Jika akun hosting kedaluwarsa/tidak aktif, website bisa mati — pastikan
  login sesekali ke InfinityFree.

---

## Peta File Bantuan

```
C:\Users\ASUS\Documents\kasir\
  paket-hosting.zip          <- paket untuk upload manual (Bagian A)
  database\kasir_db.sql      <- dump lokal lengkap (admin + pelanggan + pesanan)
  database\kasir_db_hosting.sql <- dump bersih untuk hosting (admin + produk)
  deploy\
    CARA-INSTALL.md          <- panduan upload manual (detail)
    PANDUAN-BUKA-WEBSITE-ONLINE.md  <- dokumen ini (panduan induk)
    .env.production          <- template .env
    .htaccess                <- pengarah ke folder public
```

> Kalau Anda tidak mau ribet, cukup kirim data **subdomain + FTP (host/user/pass)
> + DB (host/nama/user/pass)** ke saya, nanti saya yang unggah & pasang —
> Anda tinggal pakai.
