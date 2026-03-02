let currentEditId = null;
let currentDeleteId = null;
let currentPage = 1;
let isModalClosing = false;

function updateStats(stats) {
    if (!stats) return;
    const totalEl = document.getElementById('statTotalPeserta');
    const pklEl = document.getElementById('statTotalPkl');
    const magangEl = document.getElementById('statTotalMagang');
    const aktifEl = document.getElementById('statTotalAktif');

    if (totalEl) totalEl.textContent = stats.total ?? totalEl.textContent;
    if (pklEl) pklEl.textContent = stats.pkl ?? pklEl.textContent;
    if (magangEl) magangEl.textContent = stats.magang ?? magangEl.textContent;
    if (aktifEl) aktifEl.textContent = stats.aktif ?? aktifEl.textContent;
}

function toggleBlur(active) {
    if (active) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

function preventMultipleCalls(callback, delay = 300) {
    if (isModalClosing) return;
    isModalClosing = true;
    callback();
    setTimeout(() => {
        isModalClosing = false;
    }, delay);
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

const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('input', debounce(() => {
        currentPage = 1;
        loadData();
    }, 300));
}

const filterJenisKegiatan = document.getElementById('filterJenisKegiatan');
if (filterJenisKegiatan) {
    filterJenisKegiatan.addEventListener('change', () => {
        currentPage = 1;
        loadData();
    });
}

const filterStatus = document.getElementById('filterStatus');
if (filterStatus) {
    filterStatus.addEventListener('change', () => {
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
    document.getElementById('filterJenisKegiatan').selectedIndex = 0;
    document.getElementById('filterAsalSekolah').selectedIndex = 0;
    document.getElementById('filterStatus').selectedIndex = 0;
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

window.loadData = function(page = currentPage) {
    currentPage = page;
    const search = document.getElementById('searchInput').value;
    const jenisKegiatan = document.getElementById('filterJenisKegiatan').value;
    const asalSekolah = document.getElementById('filterAsalSekolah').value;
    const status = document.getElementById('filterStatus').value;

    const params = new URLSearchParams({
        search: search,
        jenis_kegiatan: jenisKegiatan,
        asal_sekolah_universitas: asalSekolah,
        status: status,
        page: page
    });

    const container = document.getElementById('pesertaGridContainer');
    if (!container) return;

    container.innerHTML = `
        <div class="py-16 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-indigo-50">
                <i class="text-3xl text-indigo-600 bx bx-loader-alt bx-spin spinner"></i>
            </div>
            <h3 class="mb-2 text-lg font-semibold text-gray-800">Memuat Data</h3>
            <p class="text-gray-600">Mohon tunggu sebentar...</p>
        </div>
    `;

    const config = window.pesertaConfig || {};
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

            const container = document.getElementById('pesertaGridContainer');
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
    document.querySelectorAll('[data-edit-id]').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-edit-id');
            openEditModal(id);
        });
    });

    document.querySelectorAll('[data-show-id]').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-show-id');
            openShowModal(id);
        });
    });

    document.querySelectorAll('[data-delete-id]').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-delete-id');
            const name = this.getAttribute('data-name') || 'Peserta';
            const jenis = this.getAttribute('data-jenis') || 'PKL/Magang';
            openDeleteModal(id, name, jenis);
        });
    });
}

window.openCreateModal = function() {
    const modal = document.getElementById('createModal');
    const content = document.getElementById('createModalContent');
    const config = window.pesertaConfig || {};

    content.innerHTML = `
        <div class="py-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-indigo-50">
                <i class="text-3xl text-indigo-600 bx bx-loader-alt bx-spin spinner"></i>
            </div>
            <h3 class="mb-2 text-lg font-semibold text-gray-800">Memuat Formulir</h3>
            <p class="text-gray-600">Mohon tunggu sebentar...</p>
        </div>
    `;

    modal.classList.remove('hidden');
    modal.classList.add('modal-enter');
    document.getElementById('createModalOverlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    toggleBlur(true);

    fetch(config.createUrl, {
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
            setupDateValidation('create');
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
                <h3 class="mb-2 text-lg font-semibold text-red-800">Gagal Memuat Formulir</h3>
                <p class="mb-4 text-gray-600">Terjadi kesalahan saat memuat formulir</p>
                <button onclick="openCreateModal()" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                    <i class="mr-2 bx bx-refresh"></i>Coba Lagi
                </button>
            </div>
        `;
    });
};

window.closeCreateModal = function(e) {
    if (e) {
        e.stopPropagation();
        e.preventDefault();
    }

    preventMultipleCalls(() => {
        const modal = document.getElementById('createModal');
        modal.classList.remove('modal-enter');
        modal.classList.add('hidden');
        document.getElementById('createModalOverlay').classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('createModalContent').innerHTML = '';
        toggleBlur(false);
    });
};

window.submitCreateForm = function() {
    const form = document.getElementById('createPesertaForm');
    if (!form) {
        console.error('Create form not found');
        return;
    }

    const submitBtn = document.querySelector('#createModal button[onclick="submitCreateForm()"]');
    const originalHTML = submitBtn.innerHTML;
    const config = window.pesertaConfig || {};

    submitBtn.innerHTML = '<i class="mr-2 bx bx-loader-alt bx-spin"></i>Menyimpan...';
    submitBtn.disabled = true;

    const formData = new FormData(form);

    fetch(config.storeUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(data.message || 'Data berhasil disimpan');
            closeCreateModal();
            loadData();
        } else {
            showFormErrors('createPesertaForm', data.errors || {});
            if (data.message) {
                showErrorToast(data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorToast('Terjadi kesalahan saat menyimpan data');
    })
    .finally(() => {
        if (submitBtn) {
            submitBtn.innerHTML = originalHTML;
            submitBtn.disabled = false;
        }
    });
};

window.openEditModal = function(id) {
    currentEditId = id;
    const modal = document.getElementById('editModal');
    const content = document.getElementById('editModalContent');
    const config = window.pesertaConfig || {};

    content.innerHTML = `
        <div class="py-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-indigo-50">
                <i class="text-3xl text-indigo-600 bx bx-loader-alt bx-spin spinner"></i>
            </div>
            <h3 class="mb-2 text-lg font-semibold text-gray-800">Memuat Formulir</h3>
            <p class="text-gray-600">Mohon tunggu sebentar...</p>
        </div>
    `;

    modal.classList.remove('hidden');
    modal.classList.add('modal-enter');
    document.getElementById('editModalOverlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    toggleBlur(true);

    fetch(`${config.baseUrl}/${id}/edit`, {
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
            setupDateValidation('edit');
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
                <h3 class="mb-2 text-lg font-semibold text-red-800">Gagal Memuat Formulir</h3>
                <p class="mb-4 text-gray-600">Terjadi kesalahan saat memuat formulir</p>
                <button onclick="openEditModal(${id})" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                    <i class="mr-2 bx bx-refresh"></i>Coba Lagi
                </button>
            </div>
        `;
    });
};

window.closeEditModal = function(e) {
    if (e) {
        e.stopPropagation();
        e.preventDefault();
    }

    preventMultipleCalls(() => {
        const modal = document.getElementById('editModal');
        modal.classList.remove('modal-enter');
        modal.classList.add('hidden');
        document.getElementById('editModalOverlay').classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('editModalContent').innerHTML = '';
        currentEditId = null;
        toggleBlur(false);
    });
};

window.submitEditForm = function() {
    const form = document.getElementById('editPesertaForm');
    if (!form) {
        console.error('Edit form not found');
        return;
    }

    const submitBtn = document.querySelector('#editModal button[onclick="submitEditForm()"]');
    const originalHTML = submitBtn.innerHTML;
    const config = window.pesertaConfig || {};

    submitBtn.innerHTML = '<i class="mr-2 bx bx-loader-alt bx-spin"></i>Memperbarui...';
    submitBtn.disabled = true;

    const formData = new FormData(form);
    formData.append('_method', 'PUT');

    fetch(`${config.baseUrl}/${currentEditId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(data.message || 'Data berhasil diperbarui');
            closeEditModal();
            loadData();
        } else {
            showFormErrors('editPesertaForm', data.errors || {});
            if (data.message) {
                showErrorToast(data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorToast('Terjadi kesalahan saat memperbarui data');
    })
    .finally(() => {
        if (submitBtn) {
            submitBtn.innerHTML = originalHTML;
            submitBtn.disabled = false;
        }
    });
};

window.openShowModal = function(id) {
    const modal = document.getElementById('showModal');
    const content = document.getElementById('showModalContent');
    const config = window.pesertaConfig || {};

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
    toggleBlur(true);

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
        toggleBlur(false);
    });
};

window.openDeleteModal = function(id, name, jenis) {
    currentDeleteId = id;

    const idEl = document.getElementById('deletePesertaId');
    const nameEl = document.getElementById('deletePesertaName');
    const jenisEl = document.getElementById('deletePesertaJenis');

    if (idEl) idEl.textContent = id;
    if (nameEl) nameEl.textContent = name;
    if (jenisEl) jenisEl.textContent = jenis;

    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('modal-enter');
        document.getElementById('deleteModalOverlay').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        toggleBlur(true);
    }
};

window.closeDeleteModal = function(e) {
    if (e) {
        e.stopPropagation();
        e.preventDefault();
    }

    preventMultipleCalls(() => {
        const modal = document.getElementById('deleteModal');
        if (modal) {
            modal.classList.remove('modal-enter');
            modal.classList.add('hidden');
            document.getElementById('deleteModalOverlay').classList.add('hidden');
            document.body.style.overflow = '';
            currentDeleteId = null;
            toggleBlur(false);
        }
    });
};

window.confirmDeletePeserta = function() {
    if (!currentDeleteId) return;

    const deleteBtn = document.querySelector('#deleteModal button[onclick="confirmDeletePeserta()"]');
    const originalHTML = deleteBtn.innerHTML;
    const config = window.pesertaConfig || {};

    deleteBtn.innerHTML = '<i class="mr-2 bx bx-loader-alt bx-spin"></i>Menghapus...';
    deleteBtn.disabled = true;

    fetch(`${config.baseUrl}/${currentDeleteId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast(data.message || 'Data berhasil dihapus');
            closeDeleteModal();
            loadData();
        } else {
            showErrorToast(data.message || 'Terjadi kesalahan saat menghapus data');
            closeDeleteModal();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorToast('Terjadi kesalahan saat menghapus data');
        closeDeleteModal();
    })
    .finally(() => {
        if (deleteBtn) {
            deleteBtn.innerHTML = originalHTML;
            deleteBtn.disabled = false;
        }
    });
};

function showFormErrors(formId, errors) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.querySelectorAll('.error-message').forEach(el => {
        el.textContent = '';
        el.classList.remove('text-red-600');
    });

    form.querySelectorAll('input, select, textarea').forEach(el => {
        el.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
    });

    Object.keys(errors).forEach(key => {
        const field = form.querySelector(`[name="${key}"]`);
        if (field) {
            field.classList.add('border-red-500', 'ring-2', 'ring-red-200');

            let errorDiv = field.parentElement.querySelector('.error-message');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'error-message text-sm text-red-600 mt-1';
                field.parentElement.appendChild(errorDiv);
            }

            errorDiv.textContent = errors[key][0];

            if (Object.keys(errors)[0] === key) {
                field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                field.focus();
            }
        }
    });
}

function setupDateValidation(type) {
    setTimeout(() => {
        const tanggalMulai = document.querySelector(`#${type}_tanggal_mulai`);
        const tanggalSelesai = document.querySelector(`#${type}_tanggal_selesai`);

        if (tanggalMulai && tanggalSelesai) {
            const today = new Date().toISOString().split('T')[0];

            if (!tanggalMulai.value) {
                tanggalMulai.min = today;
            }

            if (!tanggalSelesai.value) {
                tanggalSelesai.min = today;
            }

            tanggalMulai.addEventListener('change', function() {
                tanggalSelesai.min = this.value;
                if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
                    tanggalSelesai.value = this.value;
                }
            });
        }
    }, 100);
}

window.showSuccessToast = function(message) {
    if (typeof Swal !== 'undefined') {
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
            iconColor: '#10b981'
        });
    }
};

window.showErrorToast = function(message) {
    if (typeof Swal !== 'undefined') {
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
            iconColor: '#ef4444'
        });
    }
};

document.addEventListener('DOMContentLoaded', function() {
    bindPaginationLinks();
    bindActionButtons();

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = ['createModal', 'editModal', 'showModal', 'deleteModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && !modal.classList.contains('hidden')) {
                    switch(modalId) {
                        case 'createModal':
                            closeCreateModal();
                            break;
                        case 'editModal':
                            closeEditModal();
                            break;
                        case 'showModal':
                            closeShowModal();
                            break;
                        case 'deleteModal':
                            closeDeleteModal();
                            break;
                    }
                }
            });
        }
    });
});
