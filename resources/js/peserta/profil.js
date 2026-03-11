document.addEventListener('DOMContentLoaded', function() {
    const profileView = document.getElementById('profileView');
    const editView = document.getElementById('editView');
    const btnEditProfile = document.getElementById('btnEditProfile');
    const btnCancelEdit = document.getElementById('btnCancelEdit');
    const fotoInput = document.getElementById('fotoInput');
    const imagePreview = document.getElementById('imagePreview');

    const btnGetGPS = document.getElementById('btnGetGPS');
    const alamatInput = document.getElementById('alamatInput');
    const mapContainer = document.getElementById('mapContainer');
    const locationLabel = document.getElementById('locationLabel');
    const mapDiv = document.getElementById('map');
    const latitudeInput = document.getElementById('latitudeInput');
    const longitudeInput = document.getElementById('longitudeInput');
    let map, marker, currentLat, currentLng;

    function showInlineMap() {
        mapContainer.classList.add('active');
        if (map) {
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
        }
    }

    function initMap(lat, lng) {
        currentLat = lat;
        currentLng = lng;

        if(latitudeInput) latitudeInput.value = lat;
        if(longitudeInput) longitudeInput.value = lng;

        showInlineMap();

        if (!map) {
            map = L.map('map').setView([lat, lng], 17);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);
            marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map);

            marker.on('dragend', function(e) {
                const newPos = marker.getLatLng();
                currentLat = newPos.lat;
                currentLng = newPos.lng;
                
                if(latitudeInput) latitudeInput.value = currentLat;
                if(longitudeInput) longitudeInput.value = currentLng;

                locationLabel.innerHTML =
                    `<i class='mr-1 bx bx-sync bx-spin'></i> Memperbarui Alamat...`;
                reverseGeocode(currentLat, currentLng);
            });
        } else {
            map.setView([lat, lng], 17);
            marker.setLatLng([lat, lng]);
        }
        locationLabel.innerHTML =
            `<i class='mr-1 text-green-500 bx bx-check-double'></i> Lokasi Anda Sekarang: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    }

    async function reverseGeocode(lat, lng) {
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`
            );
            const data = await response.json();
            if (data.display_name) {
                alamatInput.value = data.display_name;
                locationLabel.innerHTML =
                    `<i class='mr-1 text-green-500 bx bxs-check-circle'></i> Alamat Tersinkronisasi`;
            }
        } catch (error) {
            console.error('Error reverse geocoding:', error);
            locationLabel.innerHTML =
                `<i class='mr-1 text-red-500 bx bx-error-circle'></i> Gagal Mendapatkan Alamat`;
        }
    }

    if (btnGetGPS) {
        btnGetGPS.addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert('Geolocation tidak didukung oleh browser Anda.');
                return;
            }

            btnGetGPS.disabled = true;
            btnGetGPS.classList.add('btn-gps-loading');
            btnGetGPS.innerHTML = "<i class='bx bx-loader-alt'></i> Mencari...";

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                        const {
                            latitude,
                            longitude
                        } = position.coords;
                        initMap(latitude, longitude);
                        await reverseGeocode(latitude, longitude);

                        btnGetGPS.disabled = false;
                        btnGetGPS.classList.remove('btn-gps-loading');
                        btnGetGPS.innerHTML = "<i class='bx bx-check'></i> Berhasil";
                        setTimeout(() => {
                            btnGetGPS.innerHTML =
                                "<i class='text-base bx bx-map-pin'></i> Ambil dari GPS";
                        }, 3000);
                    },
                    (error) => {
                        btnGetGPS.disabled = false;
                        btnGetGPS.classList.remove('btn-gps-loading');
                        btnGetGPS.innerHTML =
                            "<i class='text-base bx bx-map-pin'></i> Ambil dari GPS";
                        alert('Gagal mengambil lokasi: ' + error.message);
                    }, {
                        enableHighAccuracy: true
                    }
            );
        });
    }

    if (btnEditProfile) {
        btnEditProfile.addEventListener('click', () => {
            profileView.classList.add('hidden');
            editView.classList.remove('hidden');
        });
    }

    if (btnCancelEdit) {
        btnCancelEdit.addEventListener('click', () => {
            editView.classList.add('hidden');
            profileView.classList.remove('hidden');
        });
    }

    const btnPrintIDCard = document.getElementById('btnPrintIDCard');
    const printModal = document.getElementById('printModal');
    const printModalOverlay = document.getElementById('printModalOverlay');
    const printModalContainer = document.getElementById('printModalContainer');
    const printFrame = document.getElementById('printFrame');
    const printLoader = document.getElementById('printLoader');

    if (btnPrintIDCard) {
        btnPrintIDCard.addEventListener('click', function() {
            openPrintModal();
        });
    }

    window.openPrintModal = function() {
        if (!printModal || !printModalOverlay || !printFrame) return;

        const downloadBtn = document.getElementById('downloadIdCardBtn');
        printFrame.src = window.printUrl;
        
        if (downloadBtn) {
            const separator = window.printUrl.includes('?') ? '&' : '?';
            downloadBtn.href = window.printUrl + separator + 'download=1';
        }

        printLoader.classList.remove('hidden');
        
        printModal.classList.remove('hidden');
        printModalOverlay.classList.remove('hidden');
        
        setTimeout(() => {
            printModalContainer.classList.remove('scale-95', 'opacity-0');
            printModalContainer.classList.add('scale-100', 'opacity-100');
        }, 10);

        document.body.style.overflow = 'hidden';
    };

    window.closePrintModal = function(e) {
        if (e) {
            e.stopPropagation();
        }

        if (printModalContainer) {
            printModalContainer.classList.remove('scale-100', 'opacity-100');
            printModalContainer.classList.add('scale-95', 'opacity-0');
        }
        
        if (printModalOverlay) {
            printModalOverlay.classList.add('hidden');
        }

        setTimeout(() => {
            if (printModal) {
                printModal.classList.add('hidden');
            }
            if (printFrame) printFrame.src = '';
            document.body.style.overflow = '';
        }, 300);
    };

    fotoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML =
                    `<img src="${e.target.result}" class="object-cover w-full h-full">`;
            }
            reader.readAsDataURL(file);
        }
    });
});
