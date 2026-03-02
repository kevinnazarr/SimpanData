document.addEventListener("DOMContentLoaded", function () {
    updateTime();
    setInterval(updateTime, 1000);
    getLocation();
    setupEventListeners();
});

function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString("id-ID", {
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });

    const timeElement = document.getElementById("current-time");
    if (timeElement) {
        timeElement.textContent = timeString;
    }

    const attendanceTimeInput = document.getElementById("attendance-time");
    if (attendanceTimeInput) {
        attendanceTimeInput.value = now.toISOString();
    }
}

function getLocation() {
    const locationStatus = document.getElementById("location-status");
    const refreshBtn = document.getElementById("refresh-location-btn");

    if (locationStatus) {
        locationStatus.textContent = "Mendeteksi lokasi...";
        locationStatus.className = "text-lg font-medium text-gray-800";
        locationStatus.classList.remove(
            "text-green-600",
            "text-red-600",
            "text-yellow-600",
        );
    }

    if (refreshBtn) {
        refreshBtn.disabled = true;
        refreshBtn.classList.add("opacity-50", "cursor-not-allowed");
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                document.getElementById("latitude").value =
                    position.coords.latitude;
                document.getElementById("longitude").value =
                    position.coords.longitude;

                if (locationStatus) {
                    locationStatus.textContent = "Lokasi terdeteksi";
                    locationStatus.classList.add("text-green-600");
                }

                // Fetch human-readable address
                getAddress(position.coords.latitude, position.coords.longitude);
            },
            (error) => {
                console.error("Error getting location:", error);
                let message = "Gagal mendeteksi lokasi";
                
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        message = "Izin lokasi ditolak";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message = "Lokasi tidak tersedia";
                        break;
                    case error.TIMEOUT:
                        message = "Waktu deteksi habis";
                        break;
                }

                if (locationStatus) {
                    locationStatus.textContent = message;
                    locationStatus.classList.add("text-red-600");
                }

                if (refreshBtn) {
                    refreshBtn.disabled = false;
                    refreshBtn.classList.remove(
                        "opacity-50",
                        "cursor-not-allowed",
                    );
                }

                validateForm();
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0,
            },
        );
    } else {
        if (locationStatus) {
            locationStatus.textContent = "Geolokasi tidak didukung";
            locationStatus.classList.add("text-red-600");
        }
        if (refreshBtn) {
            refreshBtn.disabled = false;
            refreshBtn.classList.remove("opacity-50", "cursor-not-allowed");
        }
    }
}

function setupEventListeners() {
    const checkinBtn = document.getElementById("checkin-btn");
    if (checkinBtn) {
        checkinBtn.addEventListener("click", () =>
            setAttendanceType("checkin"),
        );
    }

    const checkoutBtn = document.getElementById("checkout-btn");
    if (checkoutBtn) {
        checkoutBtn.addEventListener("click", () =>
            setAttendanceType("checkout"),
        );
    }

    const keteranganBtn = document.getElementById("keterangan-btn");
    if (keteranganBtn) {
        keteranganBtn.addEventListener("click", () =>
            setAttendanceType("checkin"), // We reuse 'checkin' but with different label for Izin/Sakit
        );
    }

    document
        .querySelectorAll('input[name="mode_kerja"], input[name="status"]')
        .forEach((radio) => {
            radio.addEventListener("change", validateForm);
        });

    document.querySelectorAll('input[name="status"]').forEach((radio) => {
        radio.addEventListener("change", toggleWorkModeVisibility);
    });

    const refreshBtn = document.getElementById("refresh-location-btn");
    if (refreshBtn) {
        refreshBtn.addEventListener("click", (e) => {
            e.preventDefault();
            getLocation();
        });
    }

    const form = document.getElementById("attendance-form");
    if (form) {
        form.addEventListener("submit", handleFormSubmit);
    }
}

function setAttendanceType(type) {
    document.getElementById("attendance-type").value = type;

    const checkinBtn = document.getElementById("checkin-btn");
    const checkoutBtn = document.getElementById("checkout-btn");
    const keteranganBtn = document.getElementById("keterangan-btn");

    if (checkinBtn) {
        checkinBtn.classList.remove(
            "border-green-500",
            "bg-green-50",
            "ring-2",
            "ring-green-500",
        );
    }
    if (checkoutBtn) {
        checkoutBtn.classList.remove(
            "border-red-500",
            "bg-red-50",
            "ring-2",
            "ring-red-500",
        );
    }
    if (keteranganBtn) {
        keteranganBtn.classList.remove(
            "border-indigo-500",
            "bg-indigo-50",
            "ring-2",
            "ring-indigo-500",
        );
    }

    if (type === "checkin") {
        const status = document.querySelector('input[name="status"]:checked');
        if (status && status.value === "Hadir") {
            if (checkinBtn) {
                checkinBtn.classList.add(
                    "border-green-500",
                    "bg-green-50",
                    "ring-2",
                    "ring-green-500",
                );
            }
        } else {
            if (keteranganBtn) {
                keteranganBtn.classList.add(
                    "border-indigo-500",
                    "bg-indigo-50",
                    "ring-2",
                    "ring-indigo-500",
                );
            }
        }
    } else if (type === "checkout" && checkoutBtn) {
        checkoutBtn.classList.add(
            "border-red-500",
            "bg-red-50",
            "ring-2",
            "ring-red-500",
        );
    }

    validateForm();
}

function toggleWorkModeVisibility() {
    const status = document.querySelector('input[name="status"]:checked');
    const workModeSection = document.getElementById("work-mode-section");
    const hadirTypes = document.getElementById("hadir-types-container");
    const izinSakitTypes = document.getElementById("izin-sakit-types-container");

    if (!workModeSection) return;

    // Reset attendance type when status changes drastically
    document.getElementById("attendance-type").value = "";
    setAttendanceType(""); // Clear styles

    if (status && status.value === "Hadir") {
        workModeSection.classList.remove("hidden");
        if (hadirTypes) hadirTypes.classList.remove("hidden");
        if (izinSakitTypes) izinSakitTypes.classList.add("hidden");
    } else {
        workModeSection.classList.add("hidden");
        if (hadirTypes) hadirTypes.classList.add("hidden");
        if (izinSakitTypes) izinSakitTypes.classList.remove("hidden");
        
        document
            .querySelectorAll('input[name="mode_kerja"]')
            .forEach((radio) => {
                radio.checked = false;
            });
    }

    validateForm();
}

function validateForm() {
    const attendanceType = document.getElementById("attendance-type").value;
    const statusRadio = document.querySelector('input[name="status"]:checked');
    const statusHidden = document.querySelector('input[name="status"][type="hidden"]');
    const status = statusRadio || statusHidden;
    
    const lat = document.getElementById("latitude").value;
    const lng = document.getElementById("longitude").value;
    const submitBtn = document.getElementById("submit-btn");

    if (!submitBtn) return;

    let modeKerjaValid = true;
    if (status && status.value === "Hadir") {
        const modeKerja = document.querySelector(
            'input[name="mode_kerja"]:checked',
        );
        const workModeSection = document.getElementById("work-mode-section");
        if (workModeSection && !workModeSection.classList.contains("hidden")) {
            modeKerjaValid = !!modeKerja;
        }
    }

    if (attendanceType && status && lat && lng && modeKerjaValid) {
        submitBtn.disabled = false;
        submitBtn.classList.remove("opacity-50", "cursor-not-allowed");
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.add("opacity-50", "cursor-not-allowed");
    }
}

function handleFormSubmit(e) {
    const attendanceType = document.getElementById("attendance-type").value;
    const lat = document.getElementById("latitude").value;
    const lng = document.getElementById("longitude").value;

    if (!attendanceType) {
        e.preventDefault();
        alert("Silakan pilih jenis absensi (Check In atau Check Out)");
        return;
    }

    const statusRadio = document.querySelector('input[name="status"]:checked');
    const statusHidden = document.querySelector('input[name="status"][type="hidden"]');
    const status = statusRadio || statusHidden;

    if (!status) {
        e.preventDefault();
        alert("Silakan pilih status kehadiran");
        return;
    }

    if (
        status.value === "Hadir" &&
        !document.querySelector('input[name="mode_kerja"]:checked')
    ) {
        const workModeSection = document.getElementById("work-mode-section");
        if (workModeSection && !workModeSection.classList.contains("hidden")) {
            e.preventDefault();
            alert("Silakan pilih mode kerja (WFO atau WFA)");
            return;
        }
    }

    if (!lat || !lng) {
        e.preventDefault();
        alert(
            "Lokasi belum terdeteksi. Pastikan Anda mengizinkan akses lokasi dan klik tombol refresh jika perlu.",
        );
        return;
    }

    const typeLabel =
        status.value === "Hadir"
            ? attendanceType === "checkin"
                ? "Check In"
                : "Check Out"
            : "Kirim Keterangan";

    const submitBtn = document.getElementById("submit-btn");
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
    }
}

async function getAddress(lat, lng) {
    const addressContainer = document.getElementById("location-address");
    const addressText = document.getElementById("address-text");
    const refreshBtn = document.getElementById("refresh-location-btn");

    if (addressContainer) addressContainer.classList.remove("hidden");
    if (addressText) addressText.textContent = "Mencari alamat...";

    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`,
            {
                headers: {
                    "Accept-Language": "id-ID,id;q=0.9",
                    "User-Agent": "SimpanData-Attendance-System",
                },
            },
        );

        const data = await response.json();

        if (data && data.display_name) {
            if (addressText) addressText.textContent = data.display_name;
        } else {
            if (addressText) addressText.textContent = "Alamat tidak ditemukan";
        }
    } catch (error) {
        console.error("Error fetching address:", error);
        if (addressText) addressText.textContent = "Gagal memuat alamat";
    } finally {
        if (refreshBtn) {
            refreshBtn.disabled = false;
            refreshBtn.classList.remove("opacity-50", "cursor-not-allowed");
        }
        validateForm();
    }
}
