// Revision Modal Logic
window.showRevisionModal = function(note) {
    const modal = document.getElementById('revisionModal');
    const noteContent = document.getElementById('revisionNoteContent');
    if (modal && noteContent) {
        noteContent.textContent = note;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

window.closeRevisionModal = function() {
    const modal = document.getElementById('revisionModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const reportForm = document.getElementById('report-form');
    if (reportForm) {
        let clickedButtonValue = null;
        const submitButtons = reportForm.querySelectorAll('button[type="submit"]');
        submitButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                clickedButtonValue = this.value;
            });
        });

        reportForm.addEventListener('submit', function(e) {
            const judul = document.getElementById('judul')?.value.trim();
            const deskripsi = document.getElementById('deskripsi')?.value.trim();
            
            if (judul === '' || deskripsi === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Wajib Diisi!',
                    text: 'Judul dan Deskripsi laporan tidak boleh kosong.',
                    confirmButtonColor: '#7C3AED'
                });
                return false;
            }

            const statusInput = document.getElementById('status-field');
            if (statusInput && clickedButtonValue) {
                statusInput.value = clickedButtonValue;
            }

            const submitBtn = Array.from(submitButtons).find(btn => btn.value === clickedButtonValue) || submitButtons[0];
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="flex items-center gap-2"><i class="bx bx-loader-alt animate-spin"></i><span>Menyimpan...</span></div>';
        });

        const fileInput = document.getElementById('file');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const isLaporanAkhir = window.location.pathname.includes('laporan-akhir');
                    const maxSize = (isLaporanAkhir ? 10 : 5) * 1024 * 1024;
                    const maxSizeText = isLaporanAkhir ? '10MB' : '5MB';

                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Terlalu Besar!',
                            text: `Ukuran file maksimal adalah ${maxSizeText}.`,
                            confirmButtonColor: '#7C3AED'
                        });
                        e.target.value = '';
                    }
                }
            });
        }
    }

    const deleteForm = document.getElementById('delete-form');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Laporan?',
                text: "Laporan yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    }

    const historySection = document.getElementById('history-section');
    if (historySection) {
        let searchTimer;
        
        const attachSearchListeners = (container) => {
            const searchInput = container.querySelector('#history-search-input');
            const spinner = container.querySelector('#search-spinner');
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    if (spinner) spinner.classList.remove('hidden');
                    
                    searchTimer = setTimeout(() => {
                        const searchTerm = this.value;
                        const url = new URL(window.location.href);
                        url.searchParams.set('search', searchTerm);
                        
                        // Handle different pagination param names
                        const pageParam = window.location.pathname.includes('laporan-akhir') ? 'final_page' : 'history_page';
                        url.searchParams.delete(pageParam);

                        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.getElementById('history-section');
                            if (newContent) {
                                historySection.innerHTML = newContent.innerHTML;
                                const newSearchInput = historySection.querySelector('#history-search-input');
                                if (newSearchInput) {
                                    newSearchInput.focus();
                                    newSearchInput.setSelectionRange(searchTerm.length, searchTerm.length);
                                    attachSearchListeners(historySection);
                                }
                            }
                            window.history.pushState({}, '', url);
                            if (spinner) spinner.classList.add('hidden');
                        })
                        .catch(err => {
                            console.error('Search error:', err);
                            if (spinner) spinner.classList.add('hidden');
                        });
                    }, 500);
                });
            }

            container.addEventListener('click', function(e) {
                const link = e.target.closest('.pagination a, a.history-clear-btn');
                if (link) {
                    e.preventDefault();
                    if (spinner) spinner.classList.remove('hidden');
                    const url = new URL(link.href);

                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.getElementById('history-section');
                        if (newContent) {
                            historySection.innerHTML = newContent.innerHTML;
                            attachSearchListeners(historySection);
                            const newSearchInput = historySection.querySelector('#history-search-input');
                            if (newSearchInput && url.searchParams.has('search')) {
                                newSearchInput.focus();
                            }
                        }
                        window.history.pushState({}, '', url);
                    })
                    .catch(err => {
                        console.error('Pagination error:', err);
                        if (spinner) spinner.classList.add('hidden');
                    });
                }
            });
        };

        attachSearchListeners(historySection);
    }

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('revisionModal');
        if (event.target == modal) {
            closeRevisionModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeRevisionModal();
        }
    });
});
