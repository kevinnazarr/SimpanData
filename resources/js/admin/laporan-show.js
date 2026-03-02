window.printPDF = function(url) {
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = url;
    document.body.appendChild(iframe);
    iframe.contentWindow.onload = function() {
        iframe.contentWindow.print();
    };
};

window.validateRevisi = function() {
    const catatan = document.getElementById('catatan_admin').value.trim();
    if (!catatan) {
        Swal.fire({
            icon: 'error',
            title: 'Catatan Wajib Diisi!',
            text: 'Berikan feedback agar peserta tahu apa yang harus diperbaiki.',
            confirmButtonColor: '#F59E0B'
        });
        return false;
    }
    return true;
};
