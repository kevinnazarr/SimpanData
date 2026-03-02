let currentPage = 1;
let isModalClosing = false;

function updateStats(stats) {
    if (!stats) return;
    const totalEl = document.getElementById('statTotalPeserta');
    const completeEl = document.getElementById('statProfileComplete');
    const incompleteEl = document.getElementById('statProfileIncomplete');

    if (totalEl) totalEl.textContent = stats.total ?? totalEl.textContent;
    if (completeEl) completeEl.textContent = stats.complete ?? completeEl.textContent;
    if (incompleteEl) incompleteEl.textContent = stats.incomplete ?? incompleteEl.textContent;
}

function updatePageHeader(search, profileStatus, asalSekolah) {
    const titleEl = document.getElementById('pageTitle');
    const subtitleEl = document.getElementById('pageSubtitle');

    if (!titleEl || !subtitleEl) return;

    let title = 'Data Akun Peserta';
    let subtitle = 'Kelola akun peserta PKL dan Magang';

    if (profileStatus === 'complete') {
        title = 'Akun dengan Profil Terisi';
        subtitle = 'Menampilkan akun yang sudah melengkapi data profil';
    } else if (profileStatus === 'incomplete') {
        title = 'Akun dengan Profil Belum Terisi';
        subtitle = 'Menampilkan akun yang belum melengkapi data profil';
    } else if (asalSekolah) {
        title = 'Akun dari ' + asalSekolah;
        subtitle = 'Filter berdasarkan sekolah/universitas';
    } else if (search) {
        title = 'Hasil Pencarian';
        subtitle = 'Pencarian: "' + search + '"';
    }

    titleEl.textContent = title;
    subtitleEl.textContent = subtitle;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function preventMultipleCalls(callback, delay = 300) {
    if (isModalClosing) return;
    isModalClosing = true;
    callback();
    setTimeout(() => {
        isModalClosing = false;
    }, delay);
}

const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('input', debounce(() => {
        currentPage = 1;
        loadData();
    }, 300));
}

const filterProfileStatus = document.getElementById('filterProfileStatus');
if (filterProfileStatus) {
    filterProfileStatus.addEventListener('change', () => {
        currentPage = 1;
        loadData();
    });
}

const filterAsalSekolah = document.getElementById('filterAsalSekolah');
if (filterAsalSekolah) {
    filterAsalSekolah.addEventListener('change', () => {
        currentPage = 1;
        loadData();
    });
}

window.resetFilters = function() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterProfileStatus').selectedIndex = 0;
    document.getElementById('filterAsalSekolah').selectedIndex = 0;
    currentPage = 1;

    const resetBtn = document.querySelector('[onclick="resetFilters()"]');
    if (resetBtn) {
        const originalHTML = resetBtn.innerHTML;
        resetBtn.innerHTML = '<i class="bx bx-check"></i><span>Berhasil</span>';
        resetBtn.classList.add('bg-green-100', 'text-green-700', 'border-green-200');

        setTimeout(() => {
            resetBtn.innerHTML = originalHTML;
            resetBtn.classList.remove('bg-green-100', 'text-green-700', 'border-green-200');
        }, 2000);
    }

    loadData();
};

window.filterByTotal = function() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterProfileStatus').selectedIndex = 0;
    document.getElementById('filterAsalSekolah').selectedIndex = 0;
    currentPage = 1;
    loadData();
};

window.filterByStatus = function(status) {
    const filterEl = document.getElementById('filterProfileStatus');
    if (filterEl) {
        filterEl.value = status;
        currentPage = 1;
        loadData();
    }
};

window.loadData = function(page = currentPage) {
    currentPage = page;
    const search = document.getElementById('searchInput').value;
    const profileStatus = document.getElementById('filterProfileStatus').value;
    const asalSekolah = document.getElementById('filterAsalSekolah').value;

    const params = new URLSearchParams({
        search: search,
        profile_status: profileStatus,
        asal_sekolah_universitas: asalSekolah,
        page: page
    });

    const container = document.getElementById('userGridContainer');
    if (!container) return;

    updatePageHeader(search, profileStatus, asalSekolah);

    container.innerHTML = `
        <div class="py-16 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-indigo-50">
                <i class="text-3xl text-indigo-600 bx bx-loader-alt bx-spin spinner"></i>
            </div>
            <h3 class="mb-2 text-lg font-semibold text-gray-800">Memuat Data</h3>
            <p class="text-gray-600">Mohon tunggu sebentar...</p>
        </div>
    `;

    const config = window.userConfig || {};
    fetch(`${config.indexUrl}?${params}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.grid) {
            container.innerHTML = data.grid;
            bindPaginationLinks();
            bindActionButtons();

            container.style.opacity = '0';
            container.style.transform = 'translateY(10px)';
            setTimeout(() => {
                container.style.transition = 'all 0.3s ease';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 50);

            if (data.stats && typeof updateStats === 'function') {
                updateStats(data.stats);
            }
        } else {
            throw new Error('Invalid response format');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        container.innerHTML = `
            <div class="py-16 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-red-50">
                    <i class="text-3xl text-red-600 bx bx-error"></i>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-red-800">Gagal Memuat Data</h3>
                <p class="mb-4 text-gray-600">Terjadi kesalahan saat memuat data</p>
                <button onclick="loadData()" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                    <i class="mr-2 bx bx-refresh"></i>Coba Lagi
                </button>
            </div>
        `;
    });
};

function bindPaginationLinks() {
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = new URL(this.href);
            const page = url.searchParams.get('page') || 1;
            loadData(page);

            const container = document.getElementById('userGridContainer');
            if (container) {
                window.scrollTo({
                    top: container.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
}

function bindActionButtons() {
    document.querySelectorAll('[data-show-id]').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-show-id');
            openShowModal(id);
        });
    });
}

window.openShowModal = function(id) {
    const modal = document.getElementById('showModal');
    const content = document.getElementById('showModalContent');
    const config = window.userConfig || {};

    content.innerHTML = `
        <div class="py-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-indigo-50">
                <i class="text-3xl text-indigo-600 bx bx-loader-alt bx-spin spinner"></i>
            </div>
            <h3 class="mb-2 text-lg font-semibold text-gray-800">Memuat Data</h3>
            <p class="text-gray-600">Mohon tunggu sebentar...</p>
        </div>
    `;

    modal.classList.remove('hidden');
    modal.classList.add('modal-enter');
    document.getElementById('showModalOverlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    fetch(`${config.baseUrl}/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.html) {
            content.innerHTML = data.html;
        } else {
            throw new Error('Invalid response format');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        content.innerHTML = `
            <div class="py-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-red-50">
                    <i class="text-3xl text-red-600 bx bx-error"></i>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-red-800">Gagal Memuat Data</h3>
                <p class="mb-4 text-gray-600">Terjadi kesalahan saat memuat data</p>
                <button onclick="openShowModal(${id})" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                    <i class="mr-2 bx bx-refresh"></i>Coba Lagi
                </button>
            </div>
        `;
    });
};

window.closeShowModal = function(e) {
    if (e) {
        e.stopPropagation();
        e.preventDefault();
    }

    preventMultipleCalls(() => {
        const modal = document.getElementById('showModal');
        modal.classList.remove('modal-enter');
        modal.classList.add('hidden');
        document.getElementById('showModalOverlay').classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('showModalContent').innerHTML = '';
    });
};

document.addEventListener('DOMContentLoaded', function() {
    bindPaginationLinks();
    bindActionButtons();

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('showModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeShowModal();
            }
        }
    });
});
