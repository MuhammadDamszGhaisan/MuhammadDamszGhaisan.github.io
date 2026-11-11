# ============================================================
#  buat-paket-hosting.ps1
#  Membuat 2 paket ZIP siap-upload ke hosting PHP gratis:
#    1) paket-hosting.zip  -> upload manual (ada .htaccess di root)
#    2) magstore-app.zip   -> dipakai oleh install.php (TANPA .htaccess root)
#  Struktur ZIP: isi langsung diextract ke folder htdocs.
#  Database di dalam ZIP memakai versi bersih: admin + produk/kategori saja.
# ============================================================

$ErrorActionPreference = 'Stop'

$root  = "C:\Users\ASUS\Documents\kasir"
$stage = Join-Path $env:TEMP "opencode\paket-hosting-stage"
$stageApp = Join-Path $env:TEMP "opencode\magstore-app-stage"
$zipRoot = Join-Path $root "paket-hosting.zip"
$zipDesk = Join-Path $env:USERPROFILE "Desktop\paket-hosting.zip"
$zipAppRoot = Join-Path $root "magstore-app.zip"
$zipAppInstaller = Join-Path $env:USERPROFILE "Desktop\Installer-Hosting\magstore-app.zip"

Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

function New-ZipFromFolder([string]$sourceDir, [string]$zipPath, [string[]]$exclude = @()) {
    if (Test-Path $zipPath) { Remove-Item $zipPath -Force }
    $excludeSet = @{}
    foreach ($e in $exclude) { $excludeSet[$e.Replace('\', '/')] = $true }
    $fs = [System.IO.File]::Open($zipPath, [System.IO.FileMode]::CreateNew)
    $ar = New-Object System.IO.Compression.ZipArchive($fs, [System.IO.Compression.ZipArchiveMode]::Create)
    $baseLen = $sourceDir.Length + 1
    foreach ($d in (Get-ChildItem $sourceDir -Recurse -Directory -Force)) {
        $rel = $d.FullName.Substring($baseLen).Replace('\', '/') + '/'
        if ($excludeSet.ContainsKey($rel)) { continue }
        $ar.CreateEntry($rel) | Out-Null
    }
    foreach ($f in (Get-ChildItem $sourceDir -Recurse -File -Force)) {
        $rel = $f.FullName.Substring($baseLen).Replace('\', '/')
        if ($excludeSet.ContainsKey($rel)) { continue }
        $entry = $ar.CreateEntry($rel, [System.IO.Compression.CompressionLevel]::Optimal)
        $es = $entry.Open()
        $src = [System.IO.File]::OpenRead($f.FullName)
        $src.CopyTo($es)
        $src.Close()
        $es.Close()
    }
    $ar.Dispose()
    $fs.Close()
}

Write-Host "==> Bersihkan area kerja..." -ForegroundColor Cyan
foreach ($d in @($stage, $stageApp)) {
    if (Test-Path $d) { Remove-Item $d -Recurse -Force }
}
New-Item -ItemType Directory -Path $stage | Out-Null

Write-Host "==> Menyalin file proyek (kecuali file lokal)..." -ForegroundColor Cyan
$excludeDirs = @(
    (Join-Path $root ".git"),
    (Join-Path $root "node_modules"),
    (Join-Path $root "tools"),
    (Join-Path $root ".idea"),
    (Join-Path $root ".vscode"),
    (Join-Path $root "deploy"),
    (Join-Path $root "img"),
    (Join-Path $root "public\storage"),
    # Alat dev, tidak dipakai saat aplikasi berjalan.
    # Pint berisi builds\pint ~15 MB (melebihi 10 MB/file).
    (Join-Path $root "vendor\laravel\pint"),
    # Hanya file locale ini yang ~1,01 MB (melebihi batas 1 MB untuk file PHP).
    # Faker sendiri tetap disertakan agar aplikasi tidak error saat boot.
    (Join-Path $root "vendor\fakerphp\faker\src\Faker\Provider\nl_BE")
)
$excludeFiles = @(
    ".env", "*.bat", "*.vbs", "*.lnk", "*.zip", "*.code-workspace",
    "server-start.ps1", "buat-paket-hosting.ps1",
    "vscode-kasir.ps1", "kasir-server.ps1",
    "QR Pelanggan.png", "Link Pelanggan.txt", "Link Pelanggan.html",
    "URL-Publik.txt", "Produk MAGSTORE.txt"
)

$rcArgs = @($root, $stage, "/E", "/NFL", "/NDL", "/NJH", "/NJS", "/NP", "/R:1", "/W:1")
$rcArgs += "/XD"; $rcArgs += $excludeDirs
$rcArgs += "/XF"; $rcArgs += $excludeFiles

& robocopy @rcArgs | Out-Null
if ($LASTEXITCODE -ge 8) { throw "Robocopy gagal (kode $LASTEXITCODE)" }

Write-Host "==> Mengosongkan cache/log (tetap sisakan foldernya)..." -ForegroundColor Cyan
$clearList = @(
    "storage\framework\cache\data",
    "storage\framework\views",
    "storage\framework\sessions",
    "storage\logs",
    "bootstrap\cache"
)
foreach ($rel in $clearList) {
    $dir = Join-Path $stage $rel
    if (Test-Path $dir) {
        Get-ChildItem $dir -Force | Remove-Item -Recurse -Force -ErrorAction SilentlyContinue
    } else {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
    }
}

Write-Host "==> Menaruh .htaccess, .env, dan panduan..." -ForegroundColor Cyan
Copy-Item (Join-Path $root "deploy\.htaccess")        (Join-Path $stage ".htaccess") -Force
Copy-Item (Join-Path $root "deploy\.env.production")  (Join-Path $stage ".env")       -Force
Copy-Item (Join-Path $root "deploy\CARA-INSTALL.md")  (Join-Path $stage "CARA-INSTALL.md") -Force

# Paket hosting memakai database bersih (hanya admin + produk/kategori).
# Dump lokal yang lengkap tetap disimpan di database\kasir_db.sql.
Copy-Item (Join-Path $root "database\kasir_db_hosting.sql") (Join-Path $stage "database\kasir_db.sql") -Force

# Pastikan folder penting ada
foreach ($rel in @("bootstrap\cache", "public\products", "public\payment_proofs", "storage\app\public")) {
    $dir = Join-Path $stage $rel
    if (-not (Test-Path $dir)) { New-Item -ItemType Directory -Path $dir -Force | Out-Null }
}

Write-Host "==> Membuat paket-hosting.zip..." -ForegroundColor Cyan
New-ZipFromFolder $stage $zipRoot
Copy-Item $zipRoot $zipDesk -Force

Write-Host "==> Membuat magstore-app.zip (tanpa .htaccess root)..." -ForegroundColor Cyan
New-ZipFromFolder $stage $zipAppRoot @(".htaccess", "CARA-INSTALL.md")
$installerDir = Split-Path $zipAppInstaller -Parent
if (Test-Path $installerDir) { Copy-Item $zipAppRoot $zipAppInstaller -Force }

Write-Host ""
Write-Host "SELESAI!" -ForegroundColor Green
foreach ($z in @($zipRoot, $zipAppRoot)) {
    $zi = Get-Item $z
    Write-Host ("  {0}  ({1:N1} MB)" -f $zi.Name, ($zi.Length / 1MB))
}
Write-Host "Salinan paket-hosting.zip : $zipDesk"
if (Test-Path $installerDir) { Write-Host "Salinan magstore-app.zip  : $zipAppInstaller" }
Write-Host ""
Write-Host "Langkah berikutnya: buka CARA-INSTALL.md di dalam ZIP."
