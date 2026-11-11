/* ============================================================
 * app.js — JavaScript global Website MAGSTORE
 * Dipakai di semua halaman (diload oleh partials/theme.blade.php).
 * ============================================================ */
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* 1) Tombol cetak -------------------------------------------------
     * Tambahkan atribut data-print pada tombol yang ingin mencetak:
     *     <button data-print>Cetak</button>
     */
    document.querySelectorAll('[data-print]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            window.print();
        });
    });

    /* 2) Auto-tutup pesan sukses setelah beberapa detik -------------- */
    document.querySelectorAll('.alert.alert-success.alert-dismissible').forEach(function (el) {
        window.setTimeout(function () {
            try {
                if (window.bootstrap && window.bootstrap.Alert) {
                    window.bootstrap.Alert.getOrCreateInstance(el).close();
                } else {
                    var btn = el.querySelector('.btn-close');
                    if (btn) { btn.click(); }
                }
            } catch (err) { /* abaikan */ }
        }, 6000);
    });

    /* 3) Konfirmasi tombol hapus (data-confirm="Pesan konfirmasi") ---- */
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!window.confirm(form.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });

    /* 4) Ingat tab produk terakhir di beranda ------------------------ */
    var produkTab = document.getElementById('produkTab');
    if (produkTab && window.bootstrap && window.bootstrap.Tab) {
        var simpanTab = 'magstore.produkTab';
        var tabTerakhir = null;
        try { tabTerakhir = window.sessionStorage.getItem(simpanTab); } catch (err) { tabTerakhir = null; }

        if (tabTerakhir) {
            var pemicu = produkTab.querySelector('[data-bs-target="' + tabTerakhir + '"]');
            if (pemicu) { window.bootstrap.Tab.getOrCreateInstance(pemicu).show(); }
        }

        produkTab.addEventListener('shown.bs.tab', function (e) {
            try {
                window.sessionStorage.setItem(simpanTab, e.target.getAttribute('data-bs-target'));
            } catch (err) { /* abaikan */ }
        });
    }

    /* 5) Tampilkan / sembunyikan password ----------------------------
     * Pakai: <input id="pw" type="password">
     *        <button data-toggle-password="pw">Lihat</button>
     */
    document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.getAttribute('data-toggle-password'));
            if (!input) { return; }

            var lihat = input.type === 'password';
            input.type = lihat ? 'text' : 'password';

            var ikon = btn.querySelector('i');
            if (ikon) { ikon.className = lihat ? 'bi bi-eye-slash' : 'bi bi-eye'; }
            btn.setAttribute('aria-label', lihat ? 'Sembunyikan password' : 'Tampilkan password');
        });
    });

    /* 6) Pratinjau & drop-zone foto ---------------------------------
     * Pakai: <div data-upload-zone>
     *          <input type="file" data-preview="id-gambar">
     *          <div data-upload-status></div>
     *        </div>
     */
    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
        var gambar = document.getElementById(input.getAttribute('data-preview'));
        var zona = input.closest('[data-upload-zone]');
        var status = zona ? zona.querySelector('[data-upload-status]') : null;

        function perbaruiPratinjau(berkas) {
            if (!berkas) {
                if (gambar) {
                    gambar.classList.add('d-none');
                    gambar.removeAttribute('src');
                }
                if (zona) {
                    zona.classList.remove('has-file');
                    zona.classList.remove('is-dragover');
                }
                if (status) { status.textContent = 'Belum ada file dipilih.'; }
                return;
            }

            if (gambar) {
                gambar.src = URL.createObjectURL(berkas);
                gambar.classList.remove('d-none');
            }
            if (zona) {
                zona.classList.add('has-file');
                zona.classList.remove('is-dragover');
            }
            if (status) {
                status.innerHTML = '<i class="bi bi-check-circle"></i> ';
                status.appendChild(document.createTextNode('Siap diunggah: ' + berkas.name));
            }
        }

        input.addEventListener('change', function () {
            perbaruiPratinjau(input.files && input.files[0]);
        });

        if (!zona) { return; }

        ['dragenter', 'dragover'].forEach(function (nama) {
            zona.addEventListener(nama, function (e) {
                e.preventDefault();
                zona.classList.add('is-dragover');
            });
        });

        ['dragleave', 'drop'].forEach(function (nama) {
            zona.addEventListener(nama, function (e) {
                e.preventDefault();
                zona.classList.remove('is-dragover');
            });
        });

        zona.addEventListener('drop', function (e) {
            var berkas = e.dataTransfer && e.dataTransfer.files;
            if (!berkas || !berkas.length) { return; }
            try {
                input.files = berkas;
                perbaruiPratinjau(berkas[0]);
            } catch (err) {
                /* Browser lama: biarkan pengguna memilih lewat tombol. */
            }
        });
    });

    /* 6b) Spinner saat form unggah dikirim -------------------------- */
    document.querySelectorAll('form[data-upload-form]').forEach(function (form) {
        form.addEventListener('submit', function () {
            var tombol = form.querySelector('button[type="submit"]');
            if (!tombol || tombol.disabled) { return; }
            tombol.disabled = true;
            tombol.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Mengunggah...';
        });
    });

    /* 7) Animasi produk terbang ke keranjang --------------------------
     * Pakai: <form data-cart-form> ... </form>
     *        <img data-cart-image>
     *        <i data-cart-target>
     */
    document.querySelectorAll('form[data-cart-form]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (form.dataset.cartAnimating === 'running') {
                e.preventDefault();
                return;
            }
            if (form.dataset.cartAnimating === 'ready') { return; }
            if (reduceMotion || typeof Element.prototype.animate !== 'function') { return; }

            var gambar = document.querySelector('[data-cart-image]');
            var target = document.querySelector('[data-cart-target]');
            if (!gambar || !target) { return; }

            var awal = gambar.getBoundingClientRect();
            var akhir = target.getBoundingClientRect();
            if (!awal.width || !awal.height || !akhir.width || !akhir.height) { return; }

            e.preventDefault();
            form.dataset.cartAnimating = 'running';

            var salinan = gambar.cloneNode(true);
            salinan.removeAttribute('data-cart-image');
            salinan.setAttribute('aria-hidden', 'true');
            salinan.className = 'cart-fly-clone';
            salinan.style.left = awal.left + 'px';
            salinan.style.top = awal.top + 'px';
            salinan.style.width = awal.width + 'px';
            salinan.style.height = awal.height + 'px';
            document.body.appendChild(salinan);

            var dx = (akhir.left + akhir.width / 2) - (awal.left + awal.width / 2);
            var dy = (akhir.top + akhir.height / 2) - (awal.top + awal.height / 2);
            var animasi = salinan.animate([
                { transform: 'translate3d(0, 0, 0) scale(1)', opacity: 1 },
                { transform: 'translate3d(' + dx + 'px, ' + dy + 'px, 0) scale(.12)', opacity: .15 }
            ], {
                duration: 550,
                easing: 'cubic-bezier(.2, .7, .3, 1)',
                fill: 'forwards'
            });

            var selesai = false;
            function kirim() {
                if (selesai) { return; }
                selesai = true;
                salinan.remove();
                target.classList.add('cart-bump');
                window.setTimeout(function () {
                    target.classList.remove('cart-bump');
                    form.dataset.cartAnimating = 'ready';
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                }, 180);
            }

            animasi.onfinish = kirim;
            animasi.oncancel = kirim;
            window.setTimeout(kirim, 900);
        });
    });

    /* 8) Hitung angka statistik admin ------------------------------- */
    document.querySelectorAll('[data-count-to]').forEach(function (el) {
        var target = parseFloat(el.getAttribute('data-count-to'));
        if (!isFinite(target)) { return; }

        var prefix = el.getAttribute('data-count-prefix') || '';
        var locale = el.getAttribute('data-count-locale') || 'id-ID';
        var formatter = new Intl.NumberFormat(locale, { maximumFractionDigits: 0 });

        if (reduceMotion) {
            el.textContent = prefix + formatter.format(target);
            return;
        }

        var durasi = 900;
        var mulai = null;

        function langkah(waktu) {
            if (mulai === null) { mulai = waktu; }
            var progres = Math.min((waktu - mulai) / durasi, 1);
            var halus = 1 - Math.pow(1 - progres, 3);
            el.textContent = prefix + formatter.format(Math.round(target * halus));
            if (progres < 1) { window.requestAnimationFrame(langkah); }
        }

        el.textContent = prefix + formatter.format(0);
        window.requestAnimationFrame(langkah);
        window.setTimeout(function () {
            el.textContent = prefix + formatter.format(target);
        }, durasi + 100);
    });
});
