let currentStep = 1;
let emailVerified = false;
let resendTimer = null;
let usernameTimeout;
let emailTimeout;
let usernameValid = false;
let emailValid = false;

const getCsrfToken = () => document.querySelector('meta[name="csrf-token"]').getAttribute('content');

window.showLogin = function() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    loginForm.classList.remove('hidden');
    registerForm.classList.add('hidden');

    loginForm.classList.add('animate-fade-in');
    setTimeout(() => {
        loginForm.classList.remove('animate-fade-in');
    }, 400);
}

window.showRegister = function() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    registerForm.classList.remove('hidden');
    loginForm.classList.add('hidden');

    registerForm.classList.add('animate-fade-in');
    setTimeout(() => {
        registerForm.classList.remove('animate-fade-in');
    }, 400);

    showStep(1);
}

window.showStep = function(step) {
    currentStep = step;

    ['step1', 'step2', 'step3'].forEach(id => {
        document.getElementById(id).classList.add('hidden');
    });

    const targetStep = document.getElementById(`step${step}`);
    targetStep.classList.remove('hidden');
    targetStep.classList.add('animate-slide-in');

    setTimeout(() => {
        targetStep.classList.remove('animate-slide-in');
    }, 400);

    if (step === 2) {
        setupOtpInputs();
        const email = document.getElementById('emailInput').value;
        document.getElementById('emailDisplay').textContent = email;
        startResendTimer();
    }
}

function setupOtpInputs() {
    const otpInputs = document.querySelectorAll('.otp-input');

    otpInputs.forEach((input, index) => {
        input.className =
            'w-10 h-10 text-lg font-bold text-center transition-all duration-200 border-2 border-gray-300 rounded-lg otp-input focus:border-primary focus:ring-2 focus:ring-primary-light';
        input.inputMode = 'numeric';
        input.type = 'text';

        input.addEventListener('input', (e) => {
            input.value = input.value.replace(/[^0-9]/g, '');

            if (input.value && otpInputs[index + 1]) {
                otpInputs[index + 1].focus();
            }

            collectOtp();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const otp = collectOtp();
                if (otp.length === 6) {
                    verifyOtpManual();
                }
                return;
            }

            if (e.key === 'Backspace' && !input.value && otpInputs[index - 1]) {
                e.preventDefault();
                otpInputs[index - 1].focus();
                otpInputs[index - 1].value = '';
                collectOtp();
            }
        });

        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');

            if (pasteData.length === 6) {
                pasteData.split('').forEach((char, idx) => {
                    if (otpInputs[idx]) {
                        otpInputs[idx].value = char;
                    }
                });
                collectOtp();
                otpInputs[5].focus();
            }
        });
    });

    if (otpInputs[0]) {
        setTimeout(() => otpInputs[0].focus(), 100);
    }
}

window.collectOtp = function() {
    const otpInputs = document.querySelectorAll('.otp-input');
    let otp = '';

    otpInputs.forEach(input => {
        otp += input.value;
    });

    const hiddenInput = document.getElementById('otpHidden');
    if(hiddenInput) hiddenInput.value = otp;

    const verifyBtn = document.getElementById('verifyOtpBtn');
    if(verifyBtn) verifyBtn.disabled = otp.length !== 6;

    return otp;
}

window.checkUsernameAvailability = async function() {
    const username = document.getElementById('username').value;
    const statusDiv = document.getElementById('usernameStatus');

    if (!username) {
        statusDiv.innerHTML = '';
        usernameValid = false;
        updateSendOtpButton();
        return;
    }

    if (username.length < 3) {
        statusDiv.innerHTML = '<span class="text-red-600"><i class="mr-1 fas fa-times"></i>Username minimal 3 karakter</span>';
        usernameValid = false;
        updateSendOtpButton();
        return;
    }

    try {
        const response = await fetch(window.routes.checkUsername, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ username })
        });

        const data = await response.json();

        if (data.available) {
            statusDiv.innerHTML = '<span class="text-green-600"><i class="mr-1 fas fa-check"></i>Username tersedia</span>';
            usernameValid = true;
        } else {
            statusDiv.innerHTML = '<span class="text-red-600"><i class="mr-1 fas fa-times"></i>Username sudah digunakan</span>';
            usernameValid = false;
        }
    } catch (error) {
        console.error('Error checking username:', error);
        statusDiv.innerHTML = '<span class="text-gray-500"><i class="mr-1 fas fa-exclamation-triangle"></i>Tidak dapat memeriksa username</span>';
        usernameValid = false;
    }

    updateSendOtpButton();
}

window.checkEmailAvailability = async function() {
    const email = document.getElementById('emailInput').value;
    const statusDiv = document.getElementById('emailStatus');

    if (!email) {
        statusDiv.innerHTML = '';
        emailValid = false;
        updateSendOtpButton();
        return;
    }

    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailRegex.test(email)) {
        statusDiv.innerHTML = '<span class="text-red-600"><i class="mr-1 fas fa-times"></i>Format email tidak valid</span>';
        emailValid = false;
        updateSendOtpButton();
        return;
    }

    try {
        const response = await fetch(window.routes.checkEmailAvailability, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email })
        });

        const data = await response.json();

        if (data.available) {
            statusDiv.innerHTML = '<span class="text-green-600"><i class="mr-1 fas fa-check"></i>Email tersedia</span>';
            emailValid = true;
        } else {
            statusDiv.innerHTML = '<span class="text-red-600"><i class="mr-1 fas fa-times"></i>Email sudah terdaftar</span>';
            emailValid = false;
        }
    } catch (error) {
        console.error('Error checking email:', error);
        statusDiv.innerHTML = '<span class="text-gray-500"><i class="mr-1 fas fa-exclamation-triangle"></i>Tidak dapat memeriksa email</span>';
        emailValid = false;
    }

    updateSendOtpButton();
}

function updateSendOtpButton() {
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    if (sendOtpBtn) {
        sendOtpBtn.disabled = !(usernameValid && emailValid);
    }
}

window.sendOtp = async function() {
    const email = document.getElementById('emailInput').value;
    const username = document.getElementById('username').value;
    const sendOtpBtn = document.getElementById('sendOtpBtn');

    if (!usernameValid || !emailValid) {
        showToast('Pastikan username dan email valid tersedia', 'error');
        return;
    }

    if (!email || !username) {
        showToast('Email dan username wajib diisi', 'error');
        return;
    }

    const originalText = sendOtpBtn.innerHTML;

    sendOtpBtn.disabled = true;
    sendOtpBtn.innerHTML = '<div class="mx-auto spinner"></div>';
    sendOtpBtn.classList.remove('bg-primary', 'hover:bg-primary/90');
    sendOtpBtn.classList.add('bg-gray-400');

    try {
        const response = await fetch(window.routes.sendOtp, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": getCsrfToken(),
                "Accept": "application/json"
            },
            body: JSON.stringify({
                email: email,
                username: username
            })
        });

        const data = await response.json();

        if (response.ok && data.status) {
            showToast('Kode verifikasi telah dikirim ke email Anda', 'success');
            showStep(2);
        } else {
            throw new Error(data.message || 'Gagal mengirim kode verifikasi');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast(error.message || 'Gagal mengirim kode verifikasi', 'error');
    } finally {
        sendOtpBtn.disabled = false;
        sendOtpBtn.innerHTML = originalText;
        sendOtpBtn.classList.add('bg-primary', 'hover:bg-primary/90');
        sendOtpBtn.classList.remove('bg-gray-400');
    }
}

window.verifyOtpManual = async function() {
    const otp = collectOtp();
    const email = document.getElementById('emailInput').value;
    const verifyBtn = document.getElementById('verifyOtpBtn');
    const otpStatus = document.getElementById('otpStatus');

    if (!otp || otp.length !== 6) {
        showToast('Kode verifikasi harus 6 digit', 'error');
        return;
    }

    if (!email) {
        showToast('Email wajib diisi', 'error');
        return;
    }

    const originalText = verifyBtn.innerHTML;

    verifyBtn.disabled = true;
    verifyBtn.innerHTML = '<div class="mx-auto spinner"></div>';
    verifyBtn.classList.remove('bg-primary', 'hover:bg-primary/90');
    verifyBtn.classList.add('bg-gray-400');

    try {
        const response = await fetch(window.routes.verifyOtp, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": getCsrfToken(),
                "Accept": "application/json"
            },
            body: JSON.stringify({
                email: email,
                otp: otp
            })
        });

        const data = await response.json();

        if (response.ok && data.status) {
            showToast('Email berhasil diverifikasi!', 'success');

            emailVerified = true;

            otpStatus.innerHTML =
                '<span class="font-medium text-green-600"><i class="mr-1 fas fa-check"></i>Email terverifikasi</span>';

            document.querySelectorAll('.otp-input').forEach(input => {
                input.disabled = true;
                input.classList.add('opacity-50', 'bg-gray-100');
            });

            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<i class="mr-1 fas fa-check"></i>Terverifikasi';
            verifyBtn.classList.remove('bg-gray-400');
            verifyBtn.classList.add('bg-green-500', 'hover:bg-green-500', 'cursor-default');

            if (resendTimer) clearInterval(resendTimer);

            setTimeout(() => {
                showStep(3);
            }, 1000);
        } else {
            throw new Error(data.message || 'Kode verifikasi tidak valid');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast(error.message || 'Kode verifikasi tidak valid', 'error');

        otpStatus.innerHTML =
            '<span class="text-red-600"><i class="mr-1 fas fa-times"></i>Kode tidak valid</span>';
    } finally {
        if (!emailVerified) {
            verifyBtn.innerHTML = originalText;
            verifyBtn.disabled = false;
            verifyBtn.classList.add('bg-primary', 'hover:bg-primary/90');
            verifyBtn.classList.remove('bg-gray-400');
        }
    }
}

window.resendOtp = async function() {
    const email = document.getElementById('emailInput').value;
    const username = document.getElementById('username').value;
    const resendBtn = document.getElementById('resendOtpBtn');

    if (!email || !username) {
        showToast('Email dan username wajib diisi', 'error');
        return;
    }

    const originalText = resendBtn.innerHTML;

    resendBtn.disabled = true;
    resendBtn.innerHTML = '<div class="mx-auto spinner"></div>';
    resendBtn.classList.add('opacity-50');

    showToast('Mengirim ulang kode verifikasi...', 'info');

    try {
        const response = await fetch(window.routes.sendOtp, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": getCsrfToken(),
                "Accept": "application/json"
            },
            body: JSON.stringify({
                email: email,
                username: username
            })
        });

        const data = await response.json();

        if (response.ok && data.status) {
            showToast('Kode verifikasi baru telah dikirim', 'success');

            document.querySelectorAll('.otp-input').forEach(input => {
                input.value = '';
                input.disabled = false;
                input.classList.remove('opacity-50', 'bg-gray-100');
            });

            document.getElementById('otpHidden').value = '';

            const verifyBtn = document.getElementById('verifyOtpBtn');
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = 'Verifikasi Email';
            verifyBtn.classList.remove('bg-green-500', 'cursor-default');
            verifyBtn.classList.add('bg-primary', 'hover:bg-primary/90');

            document.getElementById('otpStatus').innerHTML = '';

            setupOtpInputs();
            startResendTimer();
        } else {
            throw new Error(data.message || 'Gagal mengirim kode verifikasi');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast(error.message || 'Gagal mengirim kode verifikasi ulang', 'error');
    } finally {
        resendBtn.disabled = false;
        resendBtn.innerHTML = originalText;
        resendBtn.classList.remove('opacity-50');
    }
}

function startResendTimer() {
    const resendBtn = document.getElementById('resendOtpBtn');
    const countdownText = document.getElementById('countdownText');
    let countdown = 60;

    resendBtn.disabled = true;
    countdownText.innerHTML = `Kirim ulang OTP dalam ${countdown} detik`;
    resendBtn.classList.add('opacity-50', 'cursor-not-allowed');

    if (resendTimer) clearInterval(resendTimer);

    resendTimer = setInterval(() => {
        countdown--;
        countdownText.innerHTML = `Kirim ulang OTP dalam ${countdown} detik`;

        if (countdown <= 0) {
            clearInterval(resendTimer);
            resendBtn.disabled = false;
            countdownText.innerHTML = 'Tidak menerima kode?';
            resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }, 1000);
}

window.checkRegisterPassword = function() {
    const password = document.getElementById('registerPassword').value;
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');

    const strength = checkPasswordStrength(password);

    const percentages = [0, 25, 50, 75, 100];
    const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-400', 'bg-green-600'];
    const texts = ['Sangat Lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];

    strengthBar.style.width = `${percentages[strength]}%`;
    strengthBar.className = `h-full ${colors[strength]} transition-all duration-300`;
    strengthText.textContent = texts[strength];
    strengthText.className =
        `text-xs font-medium ${strength >= 3 ? 'text-green-600' : strength >= 2 ? 'text-yellow-600' : 'text-red-600'}`;

    checkPasswordMatch();
}

window.checkPasswordMatch = function() {
    const password = document.getElementById('registerPassword').value;
    const confirm = document.getElementById('registerPasswordConfirm').value;
    const matchDiv = document.getElementById('passwordMatch');
    const registerBtn = document.getElementById('registerBtn');

    if (!password || !confirm) {
        matchDiv.innerHTML = '';
        registerBtn.disabled = true;
        return;
    }

    if (password === confirm && password.length >= 8) {
        matchDiv.innerHTML = '<span class="text-green-600"><i class="mr-1 fas fa-check"></i>Password cocok</span>';

        registerBtn.disabled = !emailVerified;
        if (!registerBtn.disabled) {
            registerBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    } else {
        matchDiv.innerHTML =
            '<span class="text-red-600"><i class="mr-1 fas fa-times"></i>Password tidak cocok</span>';

        registerBtn.disabled = true;
        registerBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

window.togglePassword = function(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function checkPasswordStrength(password) {
    let score = 0;
    if (!password) return 0;

    if (password.length >= 8) score++;
    if (password.length >= 12) score++;

    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    return Math.min(score, 4);
}

window.showToast = function(message, type = 'info') {
    const existingToast = document.querySelector('.custom-toast');
    if (existingToast) existingToast.remove();

    const toast = document.createElement('div');
    toast.className =
        `custom-toast fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white animate-fade-in ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'}`;
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
    setupOtpInputs();

    if (window.authConfig && window.authConfig.hasRegisterErrors) {
        showRegister();
        const email = document.getElementById('emailInput').value;
        if (email) {
            showStep(2);
        }
    }

    const firstInput = document.querySelector('input:not([type="hidden"])');
    if (firstInput && !firstInput.value) {
        setTimeout(() => firstInput.focus(), 300);
    }

    const usernameInput = document.getElementById('username');
    if (usernameInput) {
        usernameInput.addEventListener('input', function() {
            clearTimeout(usernameTimeout);
            const statusDiv = document.getElementById('usernameStatus');
            statusDiv.innerHTML = '<span class="text-gray-500"><i class="mr-1 fas fa-spinner fa-spin"></i>Memeriksa...</span>';
            usernameTimeout = setTimeout(checkUsernameAvailability, 500);
        });

        usernameInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const emailInput = document.getElementById('emailInput');
                if (emailInput) emailInput.focus();
            }
        });
    }

    const emailInput = document.getElementById('emailInput');
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            clearTimeout(emailTimeout);
            const statusDiv = document.getElementById('emailStatus');
            statusDiv.innerHTML = '<span class="text-gray-500"><i class="mr-1 fas fa-spinner fa-spin"></i>Memeriksa...</span>';
            emailTimeout = setTimeout(checkEmailAvailability, 500);
        });

        emailInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendOtp();
            }
        });
    }
});
