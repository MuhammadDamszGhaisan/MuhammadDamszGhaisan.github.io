/* ============================================================
 * receipt.js — JavaScript halaman cetak struk
 * (resources/views/admin/transactions/receipt.blade.php)
 * ============================================================ */
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    // Tombol "Cetak"
    var btn = document.querySelector('[data-print]');
    if (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            window.print();
        });
    }

    // Cetak otomatis ketika struk dibuka
    window.setTimeout(function () {
        window.print();
    }, 300);
});
