window.switchTab = function(tabId) {
    document.querySelectorAll('.tab-panel').forEach(panel => {
        panel.classList.add('hidden');
        panel.classList.remove('block');
    });
    
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    const activePanel = document.getElementById('tab-' + tabId);
    if(activePanel) {
        activePanel.classList.remove('hidden');
        activePanel.classList.add('block');
    }
    
    const activeBtn = document.getElementById('btn-' + tabId);
    if(activeBtn) {
        activeBtn.classList.add('active');
    }
    
    if(tabId === 'feedback') {
        markFeedbackAsRead();
        scrollToBottom();
    }
    
    localStorage.setItem('peserta_detail_tab', tabId);
};

function scrollToBottom() {
    const container = document.getElementById('chat-container');
    if(container) {
        container.scrollTop = container.scrollHeight;
    }
}

function markFeedbackAsRead() {
    const pesertaId = document.querySelector('input[name="peserta_id"]')?.value;
    if(!pesertaId) return;

    fetch(`/admin/feedback/${pesertaId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const lastTab = localStorage.getItem('peserta_detail_tab');
    if(lastTab && document.getElementById('tab-' + lastTab)) {
        window.switchTab(lastTab);
    }

    window.deleteFeedback = function(id) {
        if(!confirm('Apakah Anda yakin ingin menghapus feedback ini?')) return;
        
        fetch(`/admin/feedback/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const element = document.getElementById(`feedback-${id}`);
                if(element) {
                    element.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => element.remove(), 300);
                }
            } else {
                alert('Gagal menghapus feedback');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus feedback');
        });
    };
});

window.actionComingSoon = function(featureName) {
    alert(featureName + ' akan segera aktif secara penuh.');
};

window.reviewLaporan = function(id) {
    alert('Buka panel review untuk laporan ID: ' + id);
};

let monitoringMap = null;

window.openPresensiDetail = function(nama, waktu, jenis, status, mode, lat, lng, catatan) {
    const modal = document.getElementById('map-modal');
    const coordsText = document.getElementById('map-coords-text');
    const addressCard = document.getElementById('map-address-card');
    const addressText = document.getElementById('location-address');
    const externalLink = document.getElementById('google-maps-link');
    
    const modalNama = document.getElementById('modalNama');
    const modalJenis = document.getElementById('modalJenis');
    const modalStatus = document.getElementById('modalStatus');
    const modalMode = document.getElementById('modalMode');
    const modalWaktu = document.getElementById('modalWaktu');
    const modalCatatan = document.getElementById('modalCatatan');
    const modalCatatanWrapper = document.getElementById('modalCatatanWrapper');
    const modalSubtitle = document.getElementById('modalSubtitle');

    if(modalNama) modalNama.innerText = nama;
    if(modalJenis) modalJenis.innerText = jenis;
    if(modalStatus) modalStatus.innerText = status;
    if(modalMode) modalMode.innerText = mode;
    if(modalWaktu) modalWaktu.innerText = waktu;
    if(modalSubtitle) modalSubtitle.innerText = `${nama} — ${jenis} ${waktu}`;
    
    if(modalCatatanWrapper) {
        if (catatan && catatan.trim() !== '') {
            modalCatatan.innerText = catatan;
            modalCatatanWrapper.classList.remove('hidden');
        } else {
            modalCatatanWrapper.classList.add('hidden');
        }
    }

    if(coordsText) coordsText.innerText = `${lat.toFixed(7)}, ${lng.toFixed(7)}`;
    if(addressText) addressText.innerText = 'Menganalisis lokasi...';
    if(addressCard) addressCard.style.display = 'none';
    if(externalLink) externalLink.href = `https://www.google.com/maps?q=${lat},${lng}`;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
    
    setTimeout(() => {
        if (monitoringMap) {
            monitoringMap.remove();
        }
        
        monitoringMap = L.map('map-container-leaflet').setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(monitoringMap);
        
        L.marker([lat, lng]).addTo(monitoringMap)
            .bindPopup(`<b>${nama}</b><br>${jenis} — ${waktu}`)
            .openPopup();

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`, {
            headers: { 'Accept-Language': 'id-ID,id;q=0.9', 'User-Agent': 'SimpanData-Monitoring-System' }
        })
        .then(r => r.json())
        .then(data => {
            if(addressText) addressText.innerText = data.display_name || 'Alamat tidak terdeteksi';
            if(addressCard) addressCard.style.display = 'block';
        })
        .catch(() => {
            if(addressText) addressText.innerText = 'Gagal sinkronisasi alamat';
            if(addressCard) addressCard.style.display = 'block';
        });
    }, 150);
};

window.showMap = window.openPresensiDetail;

window.closeMap = function() {
    const modal = document.getElementById('map-modal');
    if (monitoringMap) {
        monitoringMap.remove();
        monitoringMap = null;
    }
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
};

document.addEventListener('click', function(e) {
    const absLink = e.target.closest('#absensi-pagination a');
    if (absLink) {
        e.preventDefault();
        const url = new URL(absLink.href);
        const page = url.searchParams.get('absensi_page');
        if (page) loadTabData('absensi', page);
    }

    const lapLink = e.target.closest('#laporan-pagination a');
    if (lapLink) {
        e.preventDefault();
        const url = new URL(lapLink.href);
        const page = url.searchParams.get('laporan_page');
        if (page) loadTabData('laporan', page);
    }
});

function loadTabData(tab, page) {
    const container = document.getElementById(tab + '-container');
    const pagination = document.getElementById(tab + '-pagination');
    const pesertaId = document.querySelector('input[name="peserta_id"]')?.value;
    
    if (!container || !pagination || !pesertaId) return;

    container.classList.add('opacity-50', 'pointer-events-none');
    
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set(tab + '_page', page);

    fetch(currentUrl.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        container.innerHTML = data.html;
        pagination.innerHTML = data.pagination;
        container.classList.remove('opacity-50', 'pointer-events-none');
        
        if (tab === 'absensi') {
            container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    })
    .catch(err => {
        console.error('Failed to load ' + tab + ' data:', err);
        container.classList.remove('opacity-50', 'pointer-events-none');
    });
}
