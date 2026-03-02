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
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, timer: 2000, showConfirmButton: false })
                    .then(() => fetchTable());
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi bermasalah.' }));
    });
};

window.hapusPermanent = function (id, nama) {
    Swal.fire({
        title: 'Hapus Permanen?',
        html: `<div class="text-left">
                <p>Seluruh data <strong>${nama}</strong> akan dihapus permanen dan <span class="text-red-600 font-semibold">tidak dapat dikembalikan</span>.</p>
                <p class="mt-2 text-sm text-gray-500">Termasuk: absensi, laporan, feedback, dan penilaian.</p>
               </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bx bx-trash"></i> Ya, Hapus Permanen',
        cancelButtonText: 'Batal',
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
                Swal.fire({ icon: 'success', title: 'Terhapus!', text: data.message, timer: 2000, showConfirmButton: false })
                    .then(() => fetchTable());
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi bermasalah.' }));
    });
};
