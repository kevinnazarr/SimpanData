let currentPesertaData = null;
let debounceTimer = null;

function updateSliderValue(name) {
    const slider = document.getElementById(name);
    const valueDisplay = document.getElementById(name + 'Value');
    const value = parseInt(slider.value);
    valueDisplay.textContent = value;

    const buttons = document.querySelectorAll('.indicator-btn-' + name);
    buttons.forEach(btn => {
        btn.classList.remove('bg-red-50', 'text-red-600', 'bg-orange-50', 'text-orange-600', 'bg-blue-50', 'text-blue-600', 'bg-emerald-50', 'text-emerald-600', 'font-bold');
        btn.classList.add('bg-gray-50', 'text-gray-400');
    });

    for (let i = 1; i <= 4; i++) {
        const skat = document.getElementById(`skat-${name}-${i}`);
        if (skat) {
            skat.classList.remove('bg-red-500', 'bg-orange-500', 'bg-blue-500', 'bg-emerald-500', 'h-1.5');
            skat.classList.add('bg-gray-100', 'h-1');
        }
    }

    if (value <= 25) {
        const btn = document.getElementById('btn-' + name + '-25');
        if (btn) btn.classList.add('bg-red-50', 'text-red-600', 'font-bold');
        if (btn) btn.classList.remove('bg-gray-50', 'text-gray-400');
        const skat = document.getElementById(`skat-${name}-1`);
        if (skat) {
            skat.classList.replace('bg-gray-100', 'bg-red-500');
            skat.classList.replace('h-1', 'h-1.5');
        }
    } else if (value <= 50) {
        const btn = document.getElementById('btn-' + name + '-50');
        if (btn) btn.classList.add('bg-orange-50', 'text-orange-600', 'font-bold');
        if (btn) btn.classList.remove('bg-gray-50', 'text-gray-400');
        const skat = document.getElementById(`skat-${name}-2`);
        if (skat) {
            skat.classList.replace('bg-gray-100', 'bg-orange-500');
            skat.classList.replace('h-1', 'h-1.5');
        }
    } else if (value <= 75) {
        const btn = document.getElementById('btn-' + name + '-75');
        if (btn) btn.classList.add('bg-blue-50', 'text-blue-600', 'font-bold');
        if (btn) btn.classList.remove('bg-gray-50', 'text-gray-400');
        const skat = document.getElementById(`skat-${name}-3`);
        if (skat) {
            skat.classList.replace('bg-gray-100', 'bg-blue-500');
            skat.classList.replace('h-1', 'h-1.5');
        }
    } else {
        const btn = document.getElementById('btn-' + name + '-100');
        if (btn) btn.classList.add('bg-emerald-50', 'text-emerald-600', 'font-bold');
        if (btn) btn.classList.remove('bg-gray-50', 'text-gray-400');
        const skat = document.getElementById(`skat-${name}-4`);
        if (skat) {
            skat.classList.replace('bg-gray-100', 'bg-emerald-500');
            skat.classList.replace('h-1', 'h-1.5');
        }
    }

    calculateNilaiAkhir();
}

function setSliderValue(name, value) {
    const slider = document.getElementById(name);
    if (slider) {
        slider.value = value;
        updateSliderValue(name);
    }
}

function calculateNilaiAkhir() {
    const aspectNames = ['kedisiplinan', 'keterampilan', 'kerjasama', 'inisiatif', 'komunikasi'];
    let total = 0;

    aspectNames.forEach(name => {
        const element = document.getElementById(name);
        if (element) total += parseInt(element.value);
    });

    const nilaiAkhir = Math.round(total / aspectNames.length);
    const valueDisplay = document.getElementById('nilaiAkhirPreview');
    if (valueDisplay) valueDisplay.textContent = nilaiAkhir;

    let grade = 'E';
    if (nilaiAkhir >= 90) grade = 'A';
    else if (nilaiAkhir >= 80) grade = 'B';
    else if (nilaiAkhir >= 70) grade = 'C';
    else if (nilaiAkhir >= 60) grade = 'D';

    const gradeDisplay = document.getElementById('gradePreview');
    if (gradeDisplay) gradeDisplay.textContent = grade;
}

function openPenilaianModal(pesertaId, nama, sekolah, jurusan, foto) {
    currentPesertaData = { id: pesertaId, nama, sekolah, jurusan, foto };

    document.getElementById('modalTitle').textContent = 'Beri Penilaian';
    document.getElementById('pesertaId').value = pesertaId;
    document.getElementById('penilaianId').value = '';
    document.getElementById('pesertaNama').textContent = nama;
    document.getElementById('pesertaInfo').textContent = `${sekolah} - ${jurusan}`;
    document.getElementById('pesertaAvatar').textContent = nama.charAt(0).toUpperCase();
    document.getElementById('submitBtnText').textContent = 'Simpan Penilaian';

    ['kedisiplinan', 'keterampilan', 'kerjasama', 'inisiatif', 'komunikasi'].forEach(name => {
        const element = document.getElementById(name);
        if (element) {
            element.value = 75;
            updateSliderValue(name);
        }
    });

    document.getElementById('catatan').value = '';
    calculateNilaiAkhir();

    document.getElementById('penilaianModalOverlay')?.classList.remove('hidden');
    document.getElementById('penilaianModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function openEditModal(pesertaId) {
    fetch(`${window.penilaianConfig.showUrl}/${pesertaId}`)
        .then(res => res.json())
        .then(data => {
            const peserta = data.peserta;
            const penilaian = data.penilaian;

            currentPesertaData = {
                id: peserta.id,
                nama: peserta.nama,
                sekolah: peserta.asal_sekolah_universitas,
                jurusan: peserta.jurusan
            };

            document.getElementById('modalTitle').textContent = 'Edit Penilaian';
            document.getElementById('pesertaId').value = peserta.id;
            document.getElementById('penilaianId').value = penilaian.id;
            document.getElementById('pesertaNama').textContent = peserta.nama;
            document.getElementById('pesertaInfo').textContent = `${peserta.asal_sekolah_universitas} - ${peserta.jurusan}`;
            document.getElementById('pesertaAvatar').textContent = peserta.nama.charAt(0).toUpperCase();
            document.getElementById('submitBtnText').textContent = 'Update Penilaian';

            ['kedisiplinan', 'keterampilan', 'kerjasama', 'inisiatif', 'komunikasi'].forEach(name => {
                const element = document.getElementById(name);
                if (element) {
                    element.value = penilaian[name];
                    updateSliderValue(name);
                }
            });
            document.getElementById('catatan').value = penilaian.catatan || '';
            calculateNilaiAkhir();

            document.getElementById('penilaianModalOverlay')?.classList.remove('hidden');
            document.getElementById('penilaianModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(err => {
            if (typeof showToast === 'function') showToast('Gagal memuat data penilaian', 'error');
        });
}

function closePenilaianModal() {
    document.getElementById('penilaianModal').classList.add('hidden');
    document.getElementById('penilaianModalOverlay')?.classList.add('hidden');
    document.body.style.overflow = '';
}

function openDetailModal(pesertaId) {
    fetch(`${window.penilaianConfig.showUrl}/${pesertaId}`)
        .then(res => res.json())
        .then(data => {
            const peserta = data.peserta;
            const penilaian = data.penilaian;

            currentPesertaData = { id: peserta.id };

            document.getElementById('detailPesertaNama').textContent = peserta.nama;

            let gradeClass = 'text-gray-600';
            let grade = 'E';
            if (penilaian.nilai_akhir >= 90) { grade = 'A'; gradeClass = 'text-emerald-600'; }
            else if (penilaian.nilai_akhir >= 80) { grade = 'B'; gradeClass = 'text-blue-600'; }
            else if (penilaian.nilai_akhir >= 70) { grade = 'C'; gradeClass = 'text-amber-600'; }
            else if (penilaian.nilai_akhir >= 60) { grade = 'D'; gradeClass = 'text-orange-600'; }
            else { gradeClass = 'text-red-600'; }

            document.getElementById('detailContent').innerHTML = `
                <div class="space-y-4">
                    <div class="p-4 text-center rounded-lg bg-gradient-to-r from-indigo-50 to-purple-50">
                        <p class="text-sm font-medium text-gray-600">Nilai Akhir</p>
                        <p class="text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                            ${penilaian.nilai_akhir}
                        </p>
                        <p class="text-lg ${gradeClass} font-semibold">Grade: ${grade}</p>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                            <span class="text-gray-600"><i class='mr-2 text-indigo-500 bx bx-time'></i>Kedisiplinan</span>
                            <span class="font-bold text-indigo-600">${penilaian.kedisiplinan}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                            <span class="text-gray-600"><i class='mr-2 text-purple-500 bx bx-code-alt'></i>Keterampilan</span>
                            <span class="font-bold text-purple-600">${penilaian.keterampilan}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                            <span class="text-gray-600"><i class='mr-2 text-emerald-500 bx bx-group'></i>Kerjasama</span>
                            <span class="font-bold text-emerald-600">${penilaian.kerjasama}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                            <span class="text-gray-600"><i class='mr-2 text-amber-500 bx bx-bulb'></i>Inisiatif</span>
                            <span class="font-bold text-amber-600">${penilaian.inisiatif}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                            <span class="text-gray-600"><i class='mr-2 text-blue-500 bx bx-message-rounded-dots'></i>Komunikasi</span>
                            <span class="font-bold text-blue-600">${penilaian.komunikasi}</span>
                        </div>
                    </div>

                    ${penilaian.catatan ? `
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <p class="mb-2 text-sm font-medium text-gray-700"><i class='mr-1 bx bx-note'></i>Catatan:</p>
                        <p class="text-gray-600">${penilaian.catatan}</p>
                    </div>
                    ` : ''}

                    <div class="pt-2 text-xs text-gray-400">
                        Dinilai oleh: ${penilaian.user?.username || 'Admin'} |
                        ${new Date(penilaian.updated_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
                    </div>
                </div>
            `;

            document.getElementById('penilaianModalOverlay')?.classList.remove('hidden');
            document.getElementById('detailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        })
        .catch(err => {
            if (typeof showToast === 'function') showToast('Gagal memuat detail penilaian', 'error');
        });
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('penilaianModalOverlay')?.classList.add('hidden');
    document.body.style.overflow = '';
}

function editFromDetail() {
    closeDetailModal();
    if (currentPesertaData) {
        openEditModal(currentPesertaData.id);
    }
}

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

            // Update Stats Cards
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

    const penilaianForm = document.getElementById('penilaianForm');
    if (penilaianForm) {
        penilaianForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const penilaianId = document.getElementById('penilaianId').value;
            const isUpdate = penilaianId !== '';

            const formData = {
                peserta_id: document.getElementById('pesertaId').value,
                kedisiplinan: document.getElementById('kedisiplinan').value,
                keterampilan: document.getElementById('keterampilan').value,
                kerjasama: document.getElementById('kerjasama').value,
                inisiatif: document.getElementById('inisiatif').value,
                komunikasi: document.getElementById('komunikasi').value,
                catatan: document.getElementById('catatan').value
            };

            const url = isUpdate
                ? `${window.penilaianConfig.updateUrl}/${penilaianId}`
                : window.penilaianConfig.storeUrl;

            fetch(url, {
                method: isUpdate ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.penilaianConfig.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (typeof showToast === 'function') showToast(data.message, 'success');
                    closePenilaianModal();
                    refreshPesertaGrid();
                } else {
                    if (typeof showToast === 'function') showToast(data.message || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(err => {
                if (typeof showToast === 'function') showToast('Terjadi kesalahan saat menyimpan', 'error');
            });
        });
    }

    calculateNilaiAkhir();
});

window.updateSliderValue = updateSliderValue;
window.setSliderValue = setSliderValue;
window.openPenilaianModal = openPenilaianModal;
window.openEditModal = openEditModal;
window.openDetailModal = openDetailModal;
window.closePenilaianModal = closePenilaianModal;
window.closeDetailModal = closeDetailModal;
window.editFromDetail = editFromDetail;
window.resetFilters = resetFilters;
window.refreshPesertaGrid = refreshPesertaGrid;
