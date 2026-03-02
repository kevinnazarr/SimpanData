let currentDeleteId = null;
let currentPage = 1;
let isModalClosing = false;

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

window.loadData = function(page = currentPage) {
    currentPage = page;
    const container = document.getElementById('partnerGridContainer');
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

    const config = window.partnerConfig || {};
    
    fetch(`${config.indexUrl}?page=${page}`, {
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
            bindActionButtons(); 
            
            container.style.opacity = '0';
            container.style.transform = 'translateY(10px)';
            setTimeout(() => {
                container.style.transition = 'all 0.3s ease';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 50);

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

function bindActionButtons() {
    document.querySelectorAll('[data-delete-id]').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-delete-id');
            const name = this.getAttribute('data-name') || 'Partner';
            openDeleteModal(id, name);
        });
    });

    // Pagination links override ??
    // Laravel pagination links are <a> tags.
    // We can intercept them.
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = new URL(this.href);
            const page = url.searchParams.get('page') || 1;
            loadData(page);
        });
    });
}

window.openCreateModal = function() {
    const modal = document.getElementById('createModal');
    const overlay = document.getElementById('createModalOverlay');
    const content = document.getElementById('createModalContent');
    const config = window.partnerConfig || {};

    content.innerHTML = `
        <div class="py-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 mb-4 rounded-full bg-indigo-50">
                <i class="text-3xl text-indigo-600 bx bx-loader-alt bx-spin spinner"></i>
            </div>
            <h3 class="mb-2 text-lg font-semibold text-gray-800">Memuat Formulir</h3>
            <p class="text-gray-600">Mohon tunggu sebentar...</p>
        </div>
    `;

    overlay?.classList.remove('hidden');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    fetch(config.createUrl, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
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
        document.getElementById('createModal')?.classList.add('hidden');
        document.getElementById('createModalOverlay')?.classList.add('hidden');
        document.body.style.overflow = '';
        const content = document.getElementById('createModalContent');
        if (content) content.innerHTML = '';
    });
};

window.submitCreateForm = function() {
    const form = document.getElementById('createPartnerForm');
    if (!form) {
        console.error('Create form not found');
        return;
    }

    const submitBtn = document.querySelector('#createModal button[onclick="submitCreateForm()"]'); // Wait, button is inside the partial now!
    // But wait, the partial create-modal.blade.php INCLUDES the buttons.
    // So the selector should find it inside the modal content.
    // But my previous code selected it from #createModal.
    // Let's refine the selector.
    const btn = form.querySelector('button[onclick="submitCreateForm()"]');
    const originalHTML = btn ? btn.innerHTML : 'Simpan';
    
    if(btn) {
        btn.innerHTML = '<i class="mr-2 bx bx-loader-alt bx-spin"></i>Menyimpan...';
        btn.disabled = true;
    }

    const config = window.partnerConfig || {};
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
            showFormErrors('createPartnerForm', data.errors || {});
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
        if (btn) {
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        }
    });
};

window.openDeleteModal = function(id, name) {
    currentDeleteId = id;
    const nameEl = document.getElementById('deletePartnerName');
    if (nameEl) nameEl.textContent = name;

    document.getElementById('deleteModalOverlay')?.classList.remove('hidden');
    document.getElementById('deleteModal')?.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
};

window.closeDeleteModal = function(e) {
    if (e) {
        e.stopPropagation();
        e.preventDefault();
    }

    preventMultipleCalls(() => {
        document.getElementById('deleteModal')?.classList.add('hidden');
        document.getElementById('deleteModalOverlay')?.classList.add('hidden');
        document.body.style.overflow = '';
        currentDeleteId = null;
    });
};

window.confirmDeletePartner = function() {
    if (!currentDeleteId) return;

    const deleteBtn = document.querySelector('#deleteModal button[onclick="confirmDeletePartner()"]');
    const originalHTML = deleteBtn.innerHTML;
    const config = window.partnerConfig || {};

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

    form.querySelectorAll('.error-message').forEach(el => el.remove());
    form.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500', 'ring-2', 'ring-red-200'));

    Object.keys(errors).forEach(key => {
        const field = form.querySelector(`[name="${key}"]`);
        if (field) {
            field.classList.add('border-red-500', 'ring-2', 'ring-red-200');

            let errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-sm text-red-600 mt-1';
            errorDiv.textContent = errors[key][0];
            field.parentElement.appendChild(errorDiv);
        }
    });
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
    bindActionButtons();
});
