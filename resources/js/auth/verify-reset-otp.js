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
    const inputs = document.querySelectorAll('.otp-input');
    const hiddenInput = document.getElementById('otpHidden');
    const verifyBtn = document.getElementById('verifyBtn');
    const resendBtn = document.getElementById('resendBtn');
    const countdownText = document.getElementById('countdownText');
    const otpForm = document.getElementById('otpForm');
    let countdown = 60;
    let timer = null;

    function setupOtpInputs() {
        inputs.forEach((input, index) => {
            input.className = 'otp-input w-10 h-10 text-center text-lg font-bold border-2 border-gray-300 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary-light transition-all duration-200';
            input.type = 'text';
            input.inputMode = 'numeric';

            input.addEventListener('input', (e) => {
                input.value = input.value.replace(/[^0-9]/g, '');

                if (input.value && inputs[index + 1]) {
                    inputs[index + 1].focus();
                }

                collectOtp();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    const otp = collectOtp();
                    if (otp && otp.length === 6) {
                        otpForm.dispatchEvent(new Event('submit'));
                    }
                    return;
                }

                if (e.key === 'Backspace' && !input.value && inputs[index - 1]) {
                    e.preventDefault();
                    inputs[index - 1].focus();
                    inputs[index - 1].value = '';
                    collectOtp();
                }
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');

                if (pasteData.length === 6) {
                    pasteData.split('').forEach((char, idx) => {
                        if (inputs[idx]) {
                            inputs[idx].value = char;
                        }
                    });
                    collectOtp();
                    inputs[5].focus();
                }
            });
        });

        if (inputs[0]) {
            setTimeout(() => inputs[0].focus(), 100);
        }
    }

    function collectOtp() {
        let otp = '';
        inputs.forEach(i => otp += i.value);
        if (hiddenInput) hiddenInput.value = otp;

        if (verifyBtn) verifyBtn.disabled = otp.length !== 6;
        return otp.length === 6 ? otp : null;
    }

    function startCountdown() {
        if (!resendBtn) return;
        resendBtn.disabled = true;
        countdown = 60;

        updateCountdownText();

        timer = setInterval(() => {
            countdown--;
            updateCountdownText();

            if (countdown <= 0) {
                clearInterval(timer);
                if (countdownText) countdownText.innerText = 'Tidak menerima OTP?';
                resendBtn.disabled = false;
            }
        }, 1000);
    }

    function updateCountdownText() {
        if (countdownText) {
            countdownText.innerText = `Kirim ulang OTP dalam ${countdown} detik`;
        }
    }

    window.resendOtp = async function() {
        if (!resendBtn) return;
        resendBtn.disabled = true;
        const originalText = resendBtn.innerHTML;
        resendBtn.innerHTML = '<div class="mx-auto spinner"></div>';
        showToast('Mengirim ulang OTP...', 'info');

        try {
            const response = await fetch(window.routes.resendOtp, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({})
            });

            const data = await response.json();

            if (response.ok && data.status) {
                showToast(data.message, 'success');

                inputs.forEach(input => input.value = '');
                if (hiddenInput) hiddenInput.value = '';
                if (verifyBtn) verifyBtn.disabled = true;

                clearInterval(timer);
                startCountdown();
            } else {
                throw new Error(data.message || 'Gagal mengirim OTP');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast(error.message || 'Gagal mengirim OTP ulang', 'error');
            resendBtn.disabled = false;
        } finally {
            resendBtn.innerHTML = originalText;
        }
    };

    if (otpForm) {
        otpForm.addEventListener('submit', function(e) {
            const otp = collectOtp();
            if (!otp) {
                e.preventDefault();
                showToast('Harap isi semua digit OTP', 'error');
                return;
            }

            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<div class="mx-auto spinner"></div>';
        });
    }

    setupOtpInputs();
    startCountdown();
});
