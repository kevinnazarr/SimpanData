let debounceTimer = null;

function refreshPesertaGrid() {
    const search = document.getElementById('searchInput').value;
    const jenisKegiatan = document.getElementById('filterJenisKegiatan').value;
    const statusPenilaian = document.getElementById('filterStatusPenilaian').value;
    const sekolah = document.getElementById('filterSekolah').value;

    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (jenisKegiatan) params.append('jenis_kegiatan', jenisKegiatan);
    if (statusPenilaian) params.append('status_penilaian', statusPenilaian);
    if (sekolah) params.append('sekolah', sekolah);

    fetch(`${window.penilaianConfig.pesertaGridUrl}?${params.toString()}`)
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('pesertaGridContainer');
            if (container) container.innerHTML = data.html;

            const stats = data.stats;
            if (stats) {
                const totalEl = document.getElementById('statTotalPeserta');
                if (totalEl) totalEl.textContent = stats.total;

                const sudahEl = document.getElementById('statSudahDinilai');
                if (sudahEl) sudahEl.textContent = stats.sudah;

                const belumEl = document.getElementById('statBelumDinilai');
                if (belumEl) belumEl.textContent = stats.belum;

                const rataEl = document.getElementById('statRataRata');
                if (rataEl) rataEl.textContent = stats.rata;
            }
        });
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterJenisKegiatan').value = '';
    document.getElementById('filterStatusPenilaian').value = '';
    document.getElementById('filterSekolah').value = '';
    refreshPesertaGrid();
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(refreshPesertaGrid, 300);
        });
    }

    const filterJenisField = document.getElementById('filterJenisKegiatan');
    if (filterJenisField) filterJenisField.addEventListener('change', refreshPesertaGrid);

    const filterStatusField = document.getElementById('filterStatusPenilaian');
    if (filterStatusField) filterStatusField.addEventListener('change', refreshPesertaGrid);

    const filterSekolahField = document.getElementById('filterSekolah');
    if (filterSekolahField) filterSekolahField.addEventListener('change', refreshPesertaGrid);
});

window.resetFilters = resetFilters;
window.refreshPesertaGrid = refreshPesertaGrid;
