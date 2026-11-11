<?php
/* ============================================================
   Installer otomatis - MAGSTORE
   ------------------------------------------------------------
   CARA PAKAI:
   1. Unggah file ini (install.php) dan magstore-app.zip ke
      folder 'htdocs' hosting Anda.
   2. Buka di browser: https://<subdomain-anda>/install.php
   3. Isi Alamat website + data database, klik "Pasang Sekarang".
   Installer akan: ekstrak aplikasi, isi .env, impor database,
   buat .htaccess, lalu menghapus dirinya sendiri.
   ============================================================ */

error_reporting(E_ALL);
ini_set('display_errors', '1');
@set_time_limit(0);

$BASE = __DIR__;
$ZIP  = 'magstore-app.zip';

$HTACCESS = <<<'HT'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

Options -Indexes

<FilesMatch "^\.">
    <IfModule mod_authz_core.c>
        Require all denied
    </IfModule>
    <IfModule !mod_authz_core.c>
        Order allow,deny
        Deny from all
    </IfModule>
</FilesMatch>

<FilesMatch "\.(env|log|sql|md|json|lock|dist|ini|sh|bat|ps1|vbs|bak|old|swp)$">
    <IfModule mod_authz_core.c>
        Require all denied
    </IfModule>
    <IfModule !mod_authz_core.c>
        Order allow,deny
        Deny from all
    </IfModule>
</FilesMatch>
HT;

$logs = [];
$finished = false;
$appUrl = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appUrl = trim($_POST['app_url'] ?? '');
    $dbHost = trim($_POST['db_host'] ?? '');
    $dbName = trim($_POST['db_name'] ?? '');
    $dbUser = trim($_POST['db_user'] ?? '');
    $dbPass = $_POST['db_pass'] ?? '';

    if ($appUrl !== '' && !preg_match('#^https?://#i', $appUrl)) {
        $appUrl = 'https://' . $appUrl;
    }
    $appUrl = rtrim($appUrl, '/');

    if ($appUrl === '' || $dbHost === '' || $dbName === '' || $dbUser === '') {
        $logs[] = ['Data belum lengkap. Mohon isi Alamat website, DB Host, Nama Database, dan DB Username.', 'err'];
    } else {
        // 1) Ekstrak aplikasi
        if (file_exists($BASE . '/' . $ZIP)) {
            $zip = new ZipArchive();
            if ($zip->open($BASE . '/' . $ZIP) === true) {
                $zip->extractTo($BASE);
                $zip->close();
                $logs[] = ['Aplikasi berhasil diekstrak.', 'ok'];
            } else {
                $logs[] = ['Gagal membuka ' . $ZIP . ' (file rusak?).', 'err'];
            }
        } else {
            $logs[] = ['File ' . $ZIP . ' tidak ditemukan di folder ini.', 'err'];
        }

        // 2) Tulis .env
        $envPath = $BASE . '/.env';
        if (file_exists($envPath)) {
            $env = file_get_contents($envPath);
            $env = preg_replace('/^APP_URL=.*$/m', 'APP_URL=' . $appUrl, $env);
            $env = preg_replace('/^DB_HOST=.*$/m', 'DB_HOST=' . $dbHost, $env);
            $env = preg_replace('/^DB_DATABASE=.*$/m', 'DB_DATABASE=' . $dbName, $env);
            $env = preg_replace('/^DB_USERNAME=.*$/m', 'DB_USERNAME=' . $dbUser, $env);
            $env = preg_replace('/^DB_PASSWORD=.*$/m', 'DB_PASSWORD=' . $dbPass, $env);
            file_put_contents($envPath, $env);
            $logs[] = ['File .env diperbarui.', 'ok'];
        } else {
            $logs[] = ['.env tidak ditemukan setelah ekstrak.', 'err'];
        }

        // 3) Impor database
        $sqlPath = $BASE . '/database/kasir_db.sql';
        if (file_exists($sqlPath)) {
            $mysqli = @new mysqli($dbHost, $dbUser, $dbPass, $dbName);
            if ($mysqli->connect_errno) {
                $logs[] = ['Gagal konek ke database: ' . $mysqli->connect_error, 'err'];
            } else {
                $sql = file_get_contents($sqlPath);
                if ($mysqli->multi_query($sql)) {
                    do {
                        if ($res = $mysqli->store_result()) { $res->free(); }
                    } while ($mysqli->more_results() && $mysqli->next_result());
                    $logs[] = ['Database berhasil diimpor.', 'ok'];
                } else {
                    $logs[] = ['Gagal impor database: ' . $mysqli->error, 'err'];
                }
                $mysqli->close();
            }
        } else {
            $logs[] = ['File database/kasir_db.sql tidak ditemukan.', 'err'];
        }

        // 4) Buat .htaccess
        if (file_put_contents($BASE . '/.htaccess', $HTACCESS) !== false) {
            $logs[] = ['File .htaccess dibuat.', 'ok'];
        } else {
            $logs[] = ['Gagal membuat .htaccess.', 'err'];
        }

        // 5) Bersihkan
        @unlink($BASE . '/' . $ZIP);
        $finished = true;

        // Hapus installer (dijalankan paling akhir)
        @unlink(__FILE__);
    }
}

$hasErr = false;
foreach ($logs as $l) { if ($l[1] === 'err') { $hasErr = true; } }
?>
<!doctype html>
<html lang="id"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Installer - MAGSTORE</title>
<style>
body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#f8fafc;color:#0f172a;margin:0;padding:24px}
.card{max-width:580px;margin:0 auto;background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;box-shadow:0 8px 30px rgba(0,0,0,.06)}
h1{font-size:20px;margin:0 0 4px}
p.sub{margin:0 0 18px;color:#64748b;font-size:14px}
label{display:block;font-size:13px;font-weight:700;margin:14px 0 4px}
input{width:100%;padding:10px 12px;border:1px solid #cbd5e1;border-radius:9px;font-size:14px;box-sizing:border-box}
button{margin-top:20px;background:#f97316;color:#fff;border:0;padding:13px 18px;border-radius:9px;font-size:15px;font-weight:700;cursor:pointer;width:100%}
button:hover{background:#ea580c}
ul{list-style:none;padding:0;margin:14px 0 0}
li{padding:10px 12px;border-radius:8px;margin-bottom:6px;font-size:14px}
li.ok{background:#ecfdf5;color:#065f46}
li.err{background:#fef2f2;color:#991b1b}
.note{background:#fff7ed;border:1px solid #fed7aa;color:#9a3412;padding:12px;border-radius:9px;font-size:13px;margin-top:14px;line-height:1.5}
a.btn{display:inline-block;margin-top:16px;background:#0f172a;color:#fff;text-decoration:none;padding:12px 18px;border-radius:9px;font-size:14px;font-weight:700}
.small{font-size:12px;color:#94a3b8;margin-top:16px}
</style></head><body>
<div class="card">
<?php if ($finished && !$hasErr): ?>
  <h1>Pemasangan Selesai</h1>
  <p class="sub">Website Anda sudah aktif.</p>
  <ul><?php foreach ($logs as $l) { echo "<li class='" . $l[1] . "'>" . htmlspecialchars($l[0]) . "</li>"; } ?></ul>
  <a class="btn" href="<?php echo htmlspecialchars($appUrl ?: '/'); ?>">Buka Website</a>
  <div class="note">Login admin memakai PIN <b>20090816</b>. Installer sudah otomatis dihapus.</div>
<?php else: ?>
  <h1>Installer MAGSTORE</h1>
  <p class="sub">Isi data hosting, lalu klik Pasang Sekarang. Proses ini otomatis.</p>
  <?php if ($logs): ?>
    <ul><?php foreach ($logs as $l) { echo "<li class='" . $l[1] . "'>" . htmlspecialchars($l[0]) . "</li>"; } ?></ul>
  <?php endif; ?>
  <form method="post">
    <label>Alamat website (subdomain)</label>
    <input name="app_url" placeholder="magstore.infinityfreeapp.com" value="<?php echo htmlspecialchars($_POST['app_url'] ?? ''); ?>" required>
    <label>DB Host</label>
    <input name="db_host" placeholder="sql123.infinityfree.com" value="<?php echo htmlspecialchars($_POST['db_host'] ?? ''); ?>" required>
    <label>Nama Database</label>
    <input name="db_name" placeholder="if0_12345678_kasir" value="<?php echo htmlspecialchars($_POST['db_name'] ?? ''); ?>" required>
    <label>DB Username</label>
    <input name="db_user" placeholder="if0_12345678" value="<?php echo htmlspecialchars($_POST['db_user'] ?? ''); ?>" required>
    <label>DB Password</label>
    <input name="db_pass" type="text" value="<?php echo htmlspecialchars($_POST['db_pass'] ?? ''); ?>">
    <button type="submit">Pasang Sekarang</button>
  </form>
  <div class="note">Pastikan <b>magstore-app.zip</b> sudah ada di folder yang sama. Setelah berhasil, installer menghapus dirinya sendiri.</div>
  <div class="small">MAGSTORE &middot; Installer v1</div>
<?php endif; ?>
</div>
</body></html>
