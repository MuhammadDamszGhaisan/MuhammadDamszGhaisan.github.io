# ============================================================
#  Siapkan-Upload-Hosting.ps1
#  Menyiapkan folder siap-upload + mengisi .env otomatis
#  berdasarkan data hosting gratis Anda.
#  Jalankan: klik kanan file -> Run with PowerShell
#  (atau dobel klik "Siapkan-Upload.bat")
# ============================================================

$ErrorActionPreference = 'Stop'

$root = "C:\Users\ASUS\Documents\kasir"
$zip  = Join-Path $root "paket-hosting.zip"
$dest = Join-Path $root "upload-hosting"

Write-Host "==============================================" -ForegroundColor Cyan
Write-Host "  Siapkan Paket Upload - MAGSTORE"      -ForegroundColor Cyan
Write-Host "==============================================" -ForegroundColor Cyan
Write-Host ""

if (-not (Test-Path $zip)) {
    Write-Host "Tidak menemukan paket-hosting.zip di:" -ForegroundColor Red
    Write-Host "  $root"
    Write-Host "Pastikan file paket-hosting.zip ada di folder proyek."
    Read-Host "Tekan Enter untuk keluar"
    exit 1
}

# --- Tanya data hosting dulu (biar tidak lama menunggu) ---
Write-Host "Isi data dari panel hosting Anda:"
Write-Host ""

$sub = Read-Host "1) Subdomain (contoh: magstore.infinityfreeapp.com)"
$sub = $sub.Trim()
$sub = $sub -replace '^https?://', ''
$sub = $sub.TrimEnd('/')

$dbHost = (Read-Host "2) DB_HOST (contoh: sql123.infinityfree.com)").Trim()
$dbName = (Read-Host "3) DB_DATABASE (contoh: if0_12345678_kasir)").Trim()
$dbUser = (Read-Host "4) DB_USERNAME (contoh: if0_12345678)").Trim()
$dbPass = (Read-Host "5) DB_PASSWORD").Trim()

if ([string]::IsNullOrWhiteSpace($sub)) {
    Write-Host "Subdomain tidak boleh kosong." -ForegroundColor Red
    Read-Host "Tekan Enter untuk keluar"; exit 1
}

Write-Host ""
Write-Host "Membongkar paket (mohon tunggu 1-2 menit)..." -ForegroundColor Yellow
if (Test-Path $dest) { Remove-Item $dest -Recurse -Force }
Expand-Archive -Path $zip -DestinationPath $dest -Force

Write-Host "Mengisi file .env..." -ForegroundColor Yellow
$envPath = Join-Path $dest ".env"
if (-not (Test-Path $envPath)) { throw "File .env tidak ditemukan di dalam paket." }

$content = [System.IO.File]::ReadAllText($envPath)
$content = $content -replace '(?m)^APP_URL=.*$',    "APP_URL=https://$sub"
$content = $content -replace '(?m)^DB_HOST=.*$',     "DB_HOST=$dbHost"
$content = $content -replace '(?m)^DB_DATABASE=.*$', "DB_DATABASE=$dbName"
$content = $content -replace '(?m)^DB_USERNAME=.*$', "DB_USERNAME=$dbUser"
$content = $content -replace '(?m)^DB_PASSWORD=.*$', "DB_PASSWORD=$dbPass"

# Tulis ulang TANPA BOM agar .env tetap valid
$utf8NoBom = New-Object System.Text.UTF8Encoding($false)
[System.IO.File]::WriteAllText($envPath, $content, $utf8NoBom)

Write-Host ""
Write-Host "==============================================" -ForegroundColor Green
Write-Host "  SELESAI! File siap upload ada di:"           -ForegroundColor Green
Write-Host "  $dest"                                        -ForegroundColor Green
Write-Host "==============================================" -ForegroundColor Green
Write-Host ""
Write-Host "Langkah upload (FileZilla):"
Write-Host "  1. Hubungkan ke FTP hosting Anda."
Write-Host "  2. Masuk folder 'htdocs' (hapus isi bawaan bila ada)."
Write-Host "  3. Seret SEMUA isi folder di atas ke 'htdocs'."
Write-Host "  4. Aktifkan View > 'Show hidden files' agar .htaccess & .env ikut."
Write-Host ""
Write-Host "Link permanen Anda: https://$sub" -ForegroundColor Cyan
Write-Host ""

Start-Process $dest
Read-Host "Tekan Enter untuk menutup jendela ini"
