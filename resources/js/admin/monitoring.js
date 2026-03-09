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

window.showMap = function(lat, lng) {
    const modal = document.getElementById('map-modal');
    const coordsText = document.getElementById('map-coords-text');
    const addressCard = document.getElementById('map-address-card');
    const addressText = document.getElementById('location-address');
    const externalLink = document.getElementById('google-maps-link');
    
    coordsText.innerText = `GPS: ${lat}, ${lng}`;
    addressText.innerText = 'Menganalisis lokasi...';
    addressCard.style.display = 'none';
    externalLink.href = `https://www.google.com/maps?q=${lat},${lng}`;
    
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
        
        L.marker([lat, lng]).addTo(monitoringMap);

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`, {
            headers: { 'Accept-Language': 'id-ID,id;q=0.9', 'User-Agent': 'SimpanData-Monitoring-System' }
        })
        .then(r => r.json())
        .then(data => {
            addressText.innerText = data.display_name || 'Alamat tidak terdeteksi';
            addressCard.style.display = 'block';
        })
        .catch(() => {
            addressText.innerText = 'Gagal sinkronisasi alamat';
            addressCard.style.display = 'block';
        });
    }, 150);
};

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
