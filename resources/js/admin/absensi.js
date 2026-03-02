window.toggleAllDates = function(checkbox) {
    const form = checkbox.form;
    const dateInput = form.querySelector('#tanggalFilter');
    if (!dateInput) return;

    if (checkbox.checked) {
        dateInput.value = '';
        dateInput.disabled = true;
    } else {
        dateInput.disabled = false;
        if (!dateInput.value) {
            dateInput.value = document.getElementById('tanggalFilter').dataset.today || '';
        }
    }

    form.submit();
};

document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('allDatesFilter');
    const dateInput = document.getElementById('tanggalFilter');
    if (checkbox && dateInput && checkbox.checked) {
        dateInput.disabled = true;
    }
});

let locationMap = null;

window.openLocationModal = function(nama, waktu, jenis, status, mode, lat, lng, catatan) {
    document.getElementById('modalNama').textContent = nama;
    document.getElementById('modalJenis').textContent = jenis;
    document.getElementById('modalStatus').textContent = status;
    document.getElementById('modalMode').textContent = mode;
    document.getElementById('modalWaktu').textContent = waktu;
    document.getElementById('modalSubtitle').textContent = nama + ' — ' + jenis + ' ' + waktu;
    document.getElementById('modalCoords').textContent = lat.toFixed(7) + ', ' + lng.toFixed(7);
    document.getElementById('modalAddress').textContent = 'Memuat alamat...';

    const catatanWrapper = document.getElementById('modalCatatanWrapper');
    if (catatan && catatan.trim() !== '') {
        document.getElementById('modalCatatan').textContent = catatan;
        catatanWrapper.classList.remove('hidden');
    } else {
        catatanWrapper.classList.add('hidden');
    }

    document.getElementById('locationModalOverlay').classList.remove('hidden');
    const modal = document.getElementById('locationModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    setTimeout(() => {
        if (locationMap) {
            locationMap.remove();
        }
        locationMap = L.map('mapContainer').setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(locationMap);
        L.marker([lat, lng]).addTo(locationMap)
            .bindPopup('<b>' + nama + '</b><br>' + jenis + ' — ' + waktu)
            .openPopup();

        fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=18&addressdetails=1', {
            headers: { 'Accept-Language': 'id-ID,id;q=0.9', 'User-Agent': 'SimpanData-Attendance-System' }
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('modalAddress').textContent = data.display_name || 'Alamat tidak ditemukan';
        })
        .catch(() => {
            document.getElementById('modalAddress').textContent = 'Gagal memuat alamat';
        });
    }, 100);
};

window.closeLocationModal = function() {
    document.getElementById('locationModalOverlay').classList.add('hidden');
    document.getElementById('locationModal').classList.add('hidden');
    document.body.style.overflow = '';
    if (locationMap) {
        locationMap.remove();
        locationMap = null;
    }
};

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLocationModal(); });
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('locationModalOverlay')?.addEventListener('click', closeLocationModal);
    document.getElementById('locationModal')?.addEventListener('click', e => {
        if (e.target === e.currentTarget) closeLocationModal();
    });
});
