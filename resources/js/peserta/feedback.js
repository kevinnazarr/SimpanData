document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('pesanFeedback');
    const charCount = document.getElementById('charCount');
    const editTtextarea = document.getElementById('editPesanFeedback');
    const editCharCount = document.getElementById('editCharCount');

    function updateCharCount(el, display) {
        if (!el || !display) return;
        const length = el.value.length;
        display.textContent = length;
        if (length >= 950) {
            display.classList.add('text-red-500');
            display.classList.remove('text-slate-400');
        } else {
            display.classList.remove('text-red-500');
            display.classList.add('text-slate-400');
        }
    }

    if (textarea && charCount) {
        updateCharCount(textarea, charCount);
        textarea.addEventListener('input', () => updateCharCount(textarea, charCount));
    }

    if (editTtextarea && editCharCount) {
        updateCharCount(editTtextarea, editCharCount);
        editTtextarea.addEventListener('input', () => updateCharCount(editTtextarea, editCharCount));
    }

    window.editFeedback = function(id, pesan, rating) {
        const modal = document.getElementById('editFeedbackModal');
        const form = document.getElementById('editFeedbackForm');
        const textarea = document.getElementById('editPesanFeedback');
        
        textarea.value = pesan;
        updateCharCount(textarea, document.getElementById('editCharCount'));
        
        document.querySelectorAll('input[name="rating"][id^="edit-star"]').forEach(radio => {
            radio.checked = false;
        });
        
        if (rating) {
            const radio = document.getElementById(`edit-star${rating}`);
            if (radio) radio.checked = true;
        }
        
        form.action = `/peserta/feedback/${id}`;
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    window.closeEditModal = function() {
        const modal = document.getElementById('editFeedbackModal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    window.confirmDeleteFeedback = function(id) {
        const modal = document.getElementById('deleteFeedbackModal');
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        
        confirmBtn.onclick = function() {
            document.getElementById(`delete-form-${id}`).submit();
        };
        
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    window.closeDeleteModal = function() {
        const modal = document.getElementById('deleteFeedbackModal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };
});
