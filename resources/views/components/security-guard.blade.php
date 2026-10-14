<script>
    (function () {
        'use strict';

        // 1. Mencegah klik kanan (Context Menu) agar menu inspect tidak muncul
        document.addEventListener('contextmenu', function (e) {
            e.preventDefault();
            return false;
        }, false);

        // 2. Blokir pintasan tombol inspect element & view source
        document.addEventListener('keydown', function (e) {
            // F12
            if (e.key === 'F12' || e.keyCode === 123) {
                e.preventDefault();
                return false;
            }

            // Ctrl+Shift+I / Cmd+Option+I (Inspect)
            // Ctrl+Shift+J / Cmd+Option+J (Console)
            // Ctrl+Shift+C / Cmd+Option+C (Inspect Element)
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && (
                e.key === 'I' || e.key === 'i' ||
                e.key === 'J' || e.key === 'j' ||
                e.key === 'C' || e.key === 'c' ||
                e.keyCode === 73 || e.keyCode === 74 || e.keyCode === 67
            )) {
                e.preventDefault();
                return false;
            }

            // Ctrl+U / Cmd+Option+U (View Page Source)
            if ((e.ctrlKey || e.metaKey) && (e.key === 'U' || e.key === 'u' || e.keyCode === 85)) {
                e.preventDefault();
                return false;
            }

            // Ctrl+S / Cmd+S (Save Page HTML)
            if ((e.ctrlKey || e.metaKey) && (e.key === 'S' || e.key === 's' || e.keyCode === 83)) {
                e.preventDefault();
                return false;
            }
        }, false);

        // 3. Banner Peringatan Keamanan Resmi di Console
        try {
            console.clear();
            const titleStyle = 'color: #dc2626; font-size: 20px; font-weight: 800; font-family: sans-serif;';
            const textStyle  = 'color: #374151; font-size: 13px; font-family: sans-serif; line-height: 1.6;';
            console.log('%c⚠️ PERINGATAN KEAMANAN BPJS KETENAGAKERJAAN', titleStyle);
            console.log('%cPortal Magang Resmi dilindungi oleh sistem keamanan.\nSegala bentuk inspeksi elemen, ekstraksi kode, atau injeksi skrip dilarang keras.', textStyle);
        } catch (err) {}
    })();
</script>
