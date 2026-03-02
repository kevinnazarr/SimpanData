// Feedback Character Count Logic
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('pesanFeedback');
    const charCount = document.getElementById('charCount');

    if (textarea && charCount) {
        // Initial count
        charCount.textContent = textarea.value.length;

        textarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;

            if (length >= 950) {
                charCount.classList.add('text-red-500');
                charCount.classList.remove('text-slate-400');
            } else {
                charCount.classList.remove('text-red-500');
                charCount.classList.add('text-slate-400');
            }
        });
    }
});
