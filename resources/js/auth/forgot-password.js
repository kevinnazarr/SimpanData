function showToast(message, type = 'info') {
    const existingToast = document.querySelector('.custom-toast');
    if (existingToast) existingToast.remove();

    const toast = document.createElement('div');
    toast.className = `custom-toast fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white animate-fade-in ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'}`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('animate-fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('emailInput');
    const submitBtn = document.getElementById('submitBtn');
    const forgotForm = document.getElementById('forgotForm');
    const emailStatus = document.getElementById('emailStatus');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let emailValid = false;

    let emailTimeout;

    if (emailInput && !emailInput.value) {
        setTimeout(() => emailInput.focus(), 300);
    }

    if (emailInput && emailInput.value) {
        validateEmail();
    }

    emailInput.addEventListener('input', function() {
        clearTimeout(emailTimeout);
        
        const email = emailInput.value.trim();
        
        if (!email) {
            updateEmailStatus('invalid', '');
            emailValid = false;
            updateSubmitButton();
            return;
        }

        updateEmailStatus('checking', '<i class="mr-1 fas fa-spinner fa-spin"></i>Memeriksa email...');
        emailTimeout = setTimeout(validateEmail, 500);
    });

    async function validateEmail() {
        const email = emailInput.value.trim();

        if (!email) return;

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            updateEmailStatus('invalid', 'Format email tidak valid');
            emailValid = false;
            updateSubmitButton();
            return;
        }

        try {
            const response = await fetch(window.routes.checkEmail, {
                method: "POST",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({ email })
            });

            const data = await response.json();

            if (data.status) {
                updateEmailStatus('valid', '<i class="mr-1 fas fa-check"></i>Email terdaftar');
                emailValid = true;
                updateSubmitButton();
            } else {
                updateEmailStatus('invalid', '<i class="mr-1 fas fa-times"></i>' + data.message);
                emailValid = false;
                updateSubmitButton();
            }
        } catch (error) {
            console.error('Error:', error);
            updateEmailStatus('invalid', 'Gagal memeriksa email');
            emailValid = false;
            updateSubmitButton();
        }
    }

    function updateEmailStatus(status, message) {
        if (!emailStatus) return;
        emailStatus.innerHTML = message;

        switch(status) {
            case 'valid':
                emailStatus.className = 'text-sm text-green-600 font-medium';
                break;
            case 'invalid':
                emailStatus.className = 'text-sm text-red-600';
                break;
            case 'checking':
                emailStatus.className = 'text-sm text-blue-600';
                break;
            default:
                emailStatus.className = 'text-sm text-gray-500';
        }
    }

    function updateSubmitButton() {
        if (emailValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    forgotForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = emailInput.value.trim();

        if (!email) {
            showToast('Email wajib diisi', 'error');
            return;
        }

        if (!emailValid) {
            showToast('Email belum tervalidasi', 'error');
            return;
        }

        const originalText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<div class="mx-auto spinner"></div>';
        submitBtn.classList.remove('bg-primary', 'hover:bg-primary/90');
        submitBtn.classList.add('bg-gray-400');

        try {
            const formData = new FormData();
            formData.append('email', email);
            formData.append('_token', csrfToken);

            const response = await fetch(window.routes.forgotPassword, {
                method: "POST",
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.status) {
                showToast(data.message, 'success');

                setTimeout(() => {
                    window.location.href = window.routes.verifyOtp;
                }, 1500);
            } else {
                throw new Error(data.message || 'Gagal mengirim OTP');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast(error.message || 'Gagal mengirim OTP', 'error');

            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            submitBtn.classList.add('bg-primary', 'hover:bg-primary/90');
            submitBtn.classList.remove('bg-gray-400');
        }
    });

    emailInput.addEventListener('input', () => {
        if (emailValid) {
            emailValid = false;
            updateSubmitButton();
            updateEmailStatus('invalid', '');
        }
    });

    emailInput.addEventListener('keydown', async (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            
            if (!emailValid) {
                await validateEmail();
            }
            
            if (emailValid) {
                forgotForm.dispatchEvent(new Event('submit'));
            }
        }
    });
});
