const csrfToken  = window.arsipConfig?.csrfToken  ?? '';
const arsipUrl   = window.arsipConfig?.arsipUrl   ?? '';
const tableCard  = document.getElementById('arsipTableCard');
const searchInput = document.getElementById('searchInput');
const filterJenis = document.getElementById('filterJenis');

let debounceTimer = null;

function fetchTable() {
    const search = searchInput.value.trim();
    const jenis  = filterJenis.value;

    const params = new URLSearchParams();
    if (search) params.set('search', search);
    if (jenis)  params.set('jenis_kegiatan', jenis);

    const newUrl = arsipUrl + (params.toString() ? '?' + params.toString() : '');
    window.history.replaceState(null, '', newUrl);

    tableCard.style.opacity = '0.5';
    tableCard.style.pointerEvents = 'none';

    fetch(arsipUrl + '?' + params.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => {
        tableCard.innerHTML = data.table;
        tableCard.style.opacity = '1';
        tableCard.style.pointerEvents = '';
    })
    .catch(() => {
        tableCard.style.opacity = '1';
        tableCard.style.pointerEvents = '';
    });
}

function resetFilter() {
    searchInput.value = '';
    filterJenis.value = '';
    fetchTable();
}

searchInput?.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(fetchTable, 300);
});

filterJenis?.addEventListener('change', fetchTable);

window.pulihkan = function (id, nama) {
    Swal.fire({
        title: 'Pulihkan Peserta?',
        html: `Data <strong>${nama}</strong> akan dikembalikan ke status <strong>Selesai</strong>.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bx bx-undo"></i> Ya, Pulihkan',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-2xl shadow-xl',
            confirmButton: 'rounded-lg font-semibold',
            cancelButton: 'rounded-lg font-semibold',
        },
        buttonsStyling: true,
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch(`/admin/arsip/${id}/pulihkan`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccessToast(data.message);
                fetchTable();
            } else {
                showErrorToast(data.message);
            }
        })
        .catch(() => showErrorToast('Koneksi bermasalah. Coba lagi.'));
    });
};

window.hapusPermanent = function (id, nama) {
    Swal.fire({
        title: 'Hapus Permanen?',
        html: `<div class="text-left text-sm text-gray-600">
                <p class="mb-2">Seluruh data <strong class="text-gray-800">${nama}</strong> akan dihapus permanen dan <span class="font-semibold text-red-600">tidak dapat dikembalikan</span>.</p>
                <p class="text-gray-500">Termasuk: absensi, laporan, feedback, dan penilaian.</p>
               </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bx bx-trash"></i> Ya, Hapus Permanen',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-2xl shadow-xl',
            confirmButton: 'rounded-lg font-semibold',
            cancelButton: 'rounded-lg font-semibold',
        },
        buttonsStyling: true,
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch(`/admin/arsip/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccessToast(data.message);
                fetchTable();
            } else {
                showErrorToast(data.message);
            }
        })
        .catch(() => showErrorToast('Koneksi bermasalah. Coba lagi.'));
    });
};

function showSuccessToast(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#f0f9ff',
        iconColor: '#10b981',
    });
}

function showErrorToast(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: message,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        background: '#fef2f2',
        iconColor: '#ef4444',
    });
}

