# Cara Memasang Website MAGSTORE ke Hosting Gratis

Panduan ini memakai **InfinityFree** (hosting PHP + MySQL gratis, dengan subdomain gratis).
Semua file sudah ada di dalam paket ZIP ini — **tidak perlu SSH / Composer / perintah terminal**.

> **PENTING — batas InfinityFree:**
> - Ukuran maksimal **10 MB per file**. Karena itu **JANGAN upload `paket-hosting.zip`** (ukurannya ±14 MB). Extract dulu di komputer, lalu upload **isi foldernya** lewat FTP.
> - File **PHP/JS/HTML maksimal 1 MB**. Paket ini sudah disusun agar semua file aman (Pint dan file Faker yang kebesaran tidak disertakan).
> - Gunakan **FTP (FileZilla)**, bukan File Manager, agar upload ribuan file `vendor/` tidak gagal.
> - PHP di InfinityFree sekarang **8.4 otomatis** — tidak perlu mengganti versi.

> Hasil akhir: website bisa dibuka pelanggan lewat link permanen seperti
> `https://namamu.infinityfreeapp.com` **walaupun laptop Anda mati**.

---

## 0. Yang Anda butuhkan
- Email aktif (untuk daftar).
- File `paket-hosting.zip` (dari folder proyek).
- Aplikasi FTP gratis: **FileZilla** (disarankan) — https://filezilla-project.org
- Koneksi internet.

---

## 1. Daftar & buat hosting
1. Buka https://infinityfree.com → **Sign Up** (gunakan email Anda).
2. Buka email → klik link verifikasi → login.
3. Setelah masuk, klik **Create Account**.
4. Pilih subdomain gratis, misal: `magstore`
   → alamat menjadi `magstore.infinityfreeapp.com`.
5. Tunggu sampai status hosting **Active** (beberapa menit).
6. Buka detail akun → catat 3 hal:
   - **FTP**: Host, Username, Password (dan port 21).
   - **MySQL/phpMyAdmin**: host database, nama database, username, password.
   - **Control Panel** (tombol kecil di halaman akun).

> **SSL/HTTPS:** subdomain gratis InfinityFree **otomatis memakai HTTPS**.
> Gunakan alamat **tanpa `www`** (contoh `https://magstore.infinityfreeapp.com`),
> karena `www.` tidak tercakup sertifikat gratisnya.

Catat semuanya, nanti dipakai.

---

## 2. Import database
1. Di halaman akun, klik **Control Panel** → **MySQL Databases** (atau langsung
   **phpMyAdmin** dari menu account).
2. Buat database baru (biasanya sudah otomatis dibuatkan; nama seperti
   `if0_12345678_kasir`).
3. Buka **phpMyAdmin** → pilih database Anda.
4. Klik tab **Import** → **Choose File** → pilih `database/kasir_db.sql`
   (ada di dalam ZIP) → **Import**.
   - Pastikan muncul pesan **"Import has been successfully finished"**.
   - Jika database sudah berisi tabel, centang **"Drop all tables"** dulu.

Setelah import, Anda akan punya 10 tabel: categories, orders, order_items,
products, users, notifications, dll. Database hosting ini **sengaja bersih**:
isinya hanya **produk, kategori, dan akun admin**. Belum ada akun pelanggan
atau pesanan contoh — pelanggan akan mendaftar sendiri lewat `/register`.

> File SQL yang dipakai adalah `database/kasir_db.sql` dari paket ini (±17 KB).
> Jangan meng-import file SQL lama dari folder lain.

---

## 3. Siapkan file `.env`
1. Extract `paket-hosting.zip` di komputer. Di dalamnya ada file bernama `.env`
   (file tersembunyi — aktifkan "Show hidden files" di File Explorer).
   Buka dengan Notepad.
2. Ganti bagian berikut sesuai data hosting Anda:

   ```
   APP_URL=https://magstore.infinityfreeapp.com
   APP_KEY=base64:lRjH+w6zoMelx9BjD5Fnkp86fTK9XaURtideNtu9IJ8=   <- biarkan apa adanya
   ```
   Lalu isi data database dari langkah 1:
   ```
   DB_HOST=sql123.infinityfree.com       <- host MySQL dari hosting
   DB_DATABASE=if0_12345678_kasir        <- nama database
   DB_USERNAME=if0_12345678              <- username database
   DB_PASSWORD=passwordDatabaseAnda      <- password database
   ```
3. Simpan file.

> `APP_ENV=production` dan `APP_DEBUG=false` sudah terisi — jangan diubah.
> **Jangan** bagikan isi `.env` ke orang lain. `APP_KEY` sudah terisi, jadi tidak
> perlu membuat ulang.

---

## 4. Upload file ke hosting (lewat FTP)
1. Buka **FileZilla**, hubungkan ke FTP dari langkah 1:
   - Host: (FTP Host), Username: (FTP User), Password: (FTP Pass), Port: 21.
   - Jika muncul pertanyaan sertifikat → **Trust/OK**.
2. Di sisi kanan (server), masuk ke folder **`htdocs`**.
3. Hapus file bawaan InfinityFree (mis. `index2.html`, `default.php`, halaman
   "coming soon") jika ada.
4. Di sisi kiri (komputer), buka folder hasil **extract** `paket-hosting.zip`.
   **Jangan upload file ZIP-nya** — upload isinya.
5. Aktifkan file tersembunyi: menu **Server → Force showing hidden files**
   (supaya `.env` dan `.htaccess` terlihat dan ikut terupload).
6. Masuk ke folder hasil extract, pilih **SEMUA isi** (Ctrl+A) — yaitu folder
   `app`, `bootstrap`, `config`, `database`, `public`, `resources`, `routes`,
   `storage`, `vendor`, file `artisan`, `.env`, `.htaccess`, dst.
   **Jangan** mendrag folder induknya, nanti di server menjadi
   `htdocs/kasir/...` dan website tidak terbuka.
7. Drag semuanya ke folder `htdocs` di sisi kanan. Jika ditanya **Overwrite**,
   pilih **Overwrite**.
8. Tunggu sampai selesai. Folder `vendor` berisi ±8.000 file, jadi upload bisa
   memakan **10–30 menit** tergantung internet. Jangan tutup FileZilla.

> Jika ada file yang gagal dengan pesan ukuran/`550`, cek apakah file itu
> lebih dari 10 MB. Paket ini sudah membuang file 15 MB (`pint`), jadi
> seharusnya tidak ada. Jangan menambahkan file ZIP/aplikasi besar ke `htdocs`.

Struktur akhir di server akan seperti ini:
```
htdocs/
  .htaccess            <- mengarahkan ke folder public
  .env                 <- pengaturan Anda
  app/
  bootstrap/
  config/
  database/  (berisi kasir_db.sql)
  public/    (index.php, products/, payment_proofs/, img/...)
  resources/
  routes/
  storage/
  vendor/
  artisan
  ...
```

---

## 5. Pengaturan terakhir
1. **PHP Version**: InfinityFree saat ini otomatis memakai **PHP 8.4**.
   Tidak perlu mengubah apa pun. Jika panel masih menyediakan pilihan versi,
   pilih **PHP 8.3** (lebih lama & stabil untuk Laravel 10).
2. **Hak tulis folder**: pastikan folder berikut bisa ditulis (chmod 755, jika
   perlu 777):
   - `storage/` (beserta subfolder di dalamnya)
   - `bootstrap/cache/`
   - `public/payment_proofs/`
   - `public/products/`
   Umumnya di InfinityFree sudah otomatis bisa ditulis.
3. **HTTPS/SSL**: subdomain gratis sudah otomatis SSL. Cukup buka alamat
   `https://...` **tanpa `www`**. Tidak perlu memasang sertifikat manual.
4. Tidak perlu menjalankan `php artisan` — InfinityFree tidak menyediakan SSH
   dan aplikasi sudah siap jalan tanpa perintah terminal.

---

## 6. Uji coba
1. Buka `https://magstore.infinityfreeapp.com` (tanpa `www`) di browser/HP.
   - Saat pertama kali dibuka, mungkin muncul halaman pemeriksaan keamanan
     InfinityFree. Tunggu sebentar / refresh.
2. Pastikan halaman depan tampil beserta gambar produk.
3. Login:
   - **Admin**: buka `/login` → masukkan **PIN 20090816**.
   - **Pelanggan**: buka `/register` → daftar sendiri dengan username & password
     pilihan Anda. Setelah login, pelanggan bisa mengubah username/password
     lewat menu **Akun Saya**.
4. Setelah login admin, buka menu **Akun Admin** untuk mengganti PIN awal
   `20090816` dengan PIN rahasia Anda sendiri.
5. Coba buat pesanan, unggah bukti bayar (maks **10 MB**, format JPG/PNG/WEBP),
   lalu verifikasi dari admin.

Kalau gambar/link muncul dan tidak ada error 500, website sudah siap dibagikan.

---

## Troubleshooting
| Gejala | Solusi |
|---|---|
| **Error 500** | Cek `storage/logs/laravel.log` (unduh via FTP). Biasanya `storage/`/`bootstrap/cache/` tidak bisa ditulis, atau `APP_KEY` salah. |
| **"No application encryption key"** | Pastikan baris `APP_KEY` di `.env` ada dan utuh. |
| **Tampilan tanpa warna / CSS hilang** | Pastikan `.htaccess` (file titik di depan) ikut terupload. FileZilla: Server → **Force showing hidden files**. |
| **"Database connection error"** | Cek `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`. Nama DB/user biasanya berawalan `if0_...`. |
| **Gambar produk tidak muncul** | Pastikan folder `public/products/` ikut terupload. |
| **`.htaccess`/`.env` hilang saat upload** | FileZilla: Server → **Force showing hidden files**, lalu upload ulang kedua file itu. |
| **Tidak bisa upload ZIP 14 MB** | Memang dilarang. Extract ZIP di komputer, upload **isinya** lewat FTP. |
| **File gagal upload / hilang, ukuran >10 MB** | InfinityFree membatasi **10 MB/file**. Paket ini sudah membuang file 15 MB. Jangan upload file besar lain. |
| **File PHP/JS/HTML hilang setelah upload** | Batas file PHP/JS/HTML adalah **1 MB**. Paket ini sudah aman; jangan menambahkan file besar. |
| **Upload bukti bayar gagal padahal <10 MB** | Coba foto lain / kompres ke bawah 8 MB. Pastikan format JPG/PNG/WEBP. |
| **Halaman "security check" atau URL ber-`?i=1`** | Normal di InfinityFree untuk kunjungan pertama. Tunggu/refresh. |
| **HTTPS "not secure"** | Buka `https://` **tanpa `www`**. Subdomain gratis sudah otomatis SSL. |
| **Website lambat / error 508** | Hosting gratis punya batas resource. Tunggu beberapa menit; kurangi request berlebihan. |

---

## Tips
- **Link permanen**: setelah selesai, alamat `https://magstore.infinityfreeapp.com`
  bisa dipakai selamanya (selama akun hosting aktif). Bagikan ke pelanggan.
- Buat **QR code** dari link tersebut (bisa lewat situs pembuat QR gratis mana pun)
  dan cetak untuk dipasang di toko.
- Untuk mengganti gambar produk: login admin → Produk → Edit → ganti gambar.
  Gambar baru otomatis tersimpan di `public/products/`.
- Backup rutin: unduh ulang database via phpMyAdmin (Export) minimal 1×/bulan.

Selamat! Website kasir Anda kini online dan bisa diakses dari mana saja.

---

## Akun bawaan
| Peran | Cara login | Kredensial |
|---|---|---|
| Admin | Halaman `/login` | PIN **20090816** |
| Pelanggan | Halaman `/register` | Daftar sendiri (username & password pilihan) |

> Simpan PIN admin baik-baik dan jangan dibagikan. PIN bisa diganti kapan saja
> lewat menu **Akun Admin** setelah login. Database hosting hanya berisi admin
> dan produk/kategori; pelanggan mendaftar sendiri dan pesanan dibuat saat toko
> mulai dipakai.
