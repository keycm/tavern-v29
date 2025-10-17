<div id="signInUpModal" class="modal">
    <div class="modal-content" style="background-color: #ffffff !important;">
        <span class="close-button">&times;</span>

        <div id="signInPanel" class="modal-panel active">
            <div class="modal-form-container">
                <h2 class="modal-title">Sign In</h2>
                <form id="signInForm" class="modal-form">
                    <input type="hidden" name="redirect_url" id="redirectUrl">
                    <div class="form-group" style="text-align: left;">
                        <label for="loginUsernameEmail">Username or Email</label>
                        <input type="text" id="loginUsernameEmail" name="username_email" placeholder="Enter your username or email" required>
                    </div>
                    <div class="form-group" style="text-align: left;">
                        <label for="loginPassword">Password</label>
                        <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-options">
                        <a href="#" id="forgotPasswordLink">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary modal-btn">Sign In</button>
                </form>
                <p class="modal-bottom-text">Don't have an account? <a href="#" class="switch-to-register">Register here</a></p>
            </div>
        </div>

        <div id="registerPanel" class="modal-panel">
            <div class="modal-form-container">
                <h2 class="modal-title">Register</h2>
                <form id="registerForm" class="modal-form">
                    <div class="form-group" style="text-align: left;">
                        <label for="registerName">Username</label>
                        <input type="text" id="registerName" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group" style="text-align: left;">
                        <label for="registerEmail">Email Address</label>
                        <input type="email" id="registerEmail" name="email" placeholder="Enter your email address" required>
                        <div id="gmail-error-message" class="email-error-message">Only @gmail.com addresses are allowed.</div>
                    </div>
                    <div class="form-group" style="text-align: left; position: relative;">
                        <label for="registerPassword">Password</label>
                        <input type="password" id="registerPassword" name="password" placeholder="Create a password" required>
                        <div id="password-rules-modal" class="mini-modal">
                            <p class="validation-rule-container">
                                <span id="length" class="validation-rule invalid">6+ characters</span>,
                                <span id="capital" class="validation-rule invalid">1 uppercase</span>,
                                <span id="special" class="validation-rule invalid">1 special</span>
                            </p>
                        </div>
                    </div>
                    <div class="form-group" style="text-align: left;">
                        <label for="registerConfirmPassword">Confirm Password</label>
                        <input type="password" id="registerConfirmPassword" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary modal-btn">Register</button>
                </form>
                <p class="modal-bottom-text">Already have an account? <a href="#" class="switch-to-signin">Sign In here</a></p>
            </div>
        </div>
    </div>
</div>

<div id="otpModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <div class="modal-form-container">
            <h2 class="modal-title">Enter Verification Code</h2>
            <form id="otpForm" class="modal-form">
                <input type="hidden" id="otpEmail" name="email">
                <div class="form-group" style="text-align: left;">
                    <label for="otp">A 6-digit code has been sent to your email.</label>
                    <input type="text" id="otp" name="otp" placeholder="Enter code" required>
                </div>
                <div class="form-options" style="text-align: center; margin-top: 15px;">
                    <a href="#" id="resendRegisterOtpLink" class="disabled-link">Resend Code</a>
                    <span id="resendRegisterTimer"></span>
                </div>
                <button type="submit" class="btn btn-primary modal-btn">Verify</button>
            </form>
        </div>
    </div>
</div>

<div id="forgotPasswordModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <div class="modal-form-container">
            <h2 class="modal-title">Reset Password</h2>
            <form id="forgotPasswordForm" class="modal-form">
                <div class="form-group" style="text-align: left;">
                    <label for="forgotEmail">Enter your email to receive a reset code.</label>
                    <input type="email" id="forgotEmail" name="email" placeholder="Your email address" required>
                </div>
                <button type="submit" class="btn btn-primary modal-btn">Send Reset Code</button>
            </form>
        </div>
    </div>
</div>

<div id="resetOtpModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <div class="modal-form-container">
            <h2 class="modal-title">Enter Reset Code</h2>
            <form id="resetOtpForm" class="modal-form">
                <input type="hidden" id="resetOtpEmail" name="email">
                <div class="form-group" style="text-align: left;">
                    <label for="resetOtp">A 6-digit code has been sent to your email.</label>
                    <input type="text" id="resetOtp" name="otp" placeholder="Enter code" required>
                </div>
                 <div class="form-options" style="text-align: center; margin-top: 15px;">
                    <a href="#" id="resendOtpLink" class="disabled-link">Resend Code</a>
                    <span id="resendTimer"></span>
                </div>
                <button type="submit" class="btn btn-primary modal-btn">Verify Code</button>
            </form>
        </div>
    </div>
</div>

<div id="setNewPasswordModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <div class="modal-form-container">
            <h2 class="modal-title">Set New Password</h2>
            <form id="setNewPasswordForm" class="modal-form">
                <input type="hidden" id="setNewPasswordEmail" name="email">
                <div class="form-group" style="text-align: left;">
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" name="password" required>
                </div>
                <div class="form-group" style="text-align: left;">
                    <label for="newPasswordConfirm">Confirm New Password</label>
                    <input type="password" id="newPasswordConfirm" name="password_confirm" required>
                </div>
                <button type="submit" class="btn btn-primary modal-btn">Save New Password</button>
            </form>
        </div>
    </div>
</div>

<div id="alertModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <div class="modal-form-container">
             <h2 id="alertModalTitle" class="modal-title"></h2>
             <p id="alertModalMessage" style="margin-bottom: 25px;"></p>
             <button id="alertModalOk" class="btn btn-primary">OK</button>
        </div>
    </div>
</div>

<style>
/* --- General Modal Styles --- */
.modal-form-container { padding: 35px 30px; }
.modal-title { text-align: center; margin-bottom: 25px; }
.modal-form { width: 100%; }
.modal-bottom-text { text-align: center; margin-top: 20px; font-size: 0.9em; }
.form-options { text-align: left; margin-bottom: 15px; }
#forgotPasswordLink { font-size: 0.9em; color: #007bff; text-decoration: none; }
#forgotPasswordLink:hover { text-decoration: underline; }

/* Alert Modal Specific Styles */
#alertModal .modal-content { max-width: 400px; text-align: center; }
#alertModal .modal-form-container { padding: 10px 30px 30px 30px; }
#alertModal #alertModalOk { width: auto; min-width: 120px; }

/* Email & Password Validation Styles */
.email-error-message { display: none; color: #e74c3c; font-size: 0.85em; margin-top: 5px; font-weight: 500; }
.mini-modal { display: none; position: absolute; bottom: 105%; left: 0; margin-bottom: 10px; background-color: #333; color: #fff; padding: 10px 15px; border-radius: 6px; z-index: 10; width: auto; white-space: nowrap; box-shadow: 0 4px 8px rgba(0,0,0,0.2); opacity: 0; visibility: hidden; transform: translateY(10px); transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s ease; }
.mini-modal.show { display: block; opacity: 1; visibility: visible; transform: translateY(0); }
.mini-modal::after { content: ''; position: absolute; top: 100%; left: 20px; border-width: 5px; border-style: solid; border-color: #333 transparent transparent transparent; }
.mini-modal .validation-rule-container { color: #eee; margin: 0; font-size: 0.85em; }
.mini-modal .validation-rule.invalid { color: #ff8a8a; }
.mini-modal .validation-rule.valid { color: #8aff8a; }

/* --- Loading Animation Styles --- */
.btn-loading { position: relative; color: transparent !important; cursor: wait; pointer-events: none; }
.btn-loading::after {
    content: ''; position: absolute; left: 50%; top: 50%; width: 20px; height: 20px;
    margin-left: -10px; margin-top: -10px; border: 3px solid rgba(255, 255, 255, 0.5);
    border-top-color: #ffffff; border-radius: 50%; animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* --- Resend Link Styles --- */
.disabled-link { color: #999; pointer-events: none; text-decoration: none; }
#resendTimer, #resendRegisterTimer { margin-left: 5px; color: #555; font-weight: bold; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- MODAL HANDLING ---
    const signInUpModal = document.getElementById("signInUpModal");
    const otpModal = document.getElementById('otpModal');
    const forgotPasswordModal = document.getElementById('forgotPasswordModal');
    const resetOtpModal = document.getElementById('resetOtpModal');
    const setNewPasswordModal = document.getElementById('setNewPasswordModal');
    const alertModal = document.getElementById('alertModal');
    const openModalBtns = document.querySelectorAll(".signin-button");
    const signInPanel = document.getElementById("signInPanel");
    const registerPanel = document.getElementById("registerPanel");
    const switchToRegisterLinks = document.querySelectorAll(".switch-to-register");
    const switchToSignInLink = document.querySelector(".switch-to-signin");
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');
    const redirectUrlInput = document.getElementById('redirectUrl');

    // --- RESEND OTP ELEMENTS ---
    const resendOtpLink = document.getElementById('resendOtpLink');
    const resendTimerSpan = document.getElementById('resendTimer');
    let resendTimer;
    let countdown;

    const resendRegisterOtpLink = document.getElementById('resendRegisterOtpLink');
    const resendRegisterTimerSpan = document.getElementById('resendRegisterTimer');
    let resendRegisterTimer;
    let registerCountdown;

    function closeModal(modal) { if (modal) modal.style.display = 'none'; }

    // --- COUNTDOWN LOGIC (Password Reset) ---
    function startResendCountdown() {
        countdown = 60; 
        resendOtpLink.classList.add('disabled-link');
        resendTimerSpan.textContent = `(${countdown}s)`;
        resendTimer = setInterval(() => {
            countdown--;
            resendTimerSpan.textContent = `(${countdown}s)`;
            if (countdown <= 0) {
                clearInterval(resendTimer);
                resendTimerSpan.textContent = '';
                resendOtpLink.classList.remove('disabled-link');
            }
        }, 1000);
    }

    // --- COUNTDOWN LOGIC (Registration) ---
    function startRegisterResendCountdown() {
        registerCountdown = 60;
        resendRegisterOtpLink.classList.add('disabled-link');
        resendRegisterTimerSpan.textContent = `(${registerCountdown}s)`;
        resendRegisterTimer = setInterval(() => {
            registerCountdown--;
            resendRegisterTimerSpan.textContent = `(${registerCountdown}s)`;
            if (registerCountdown <= 0) {
                clearInterval(resendRegisterTimer);
                resendRegisterTimerSpan.textContent = '';
                resendRegisterOtpLink.classList.remove('disabled-link');
            }
        }, 1000);
    }

    // --- MODIFIED: Dedicated close function for OTP modal to clear session storage ---
    function closeOtpModal() {
        closeModal(otpModal);
        sessionStorage.removeItem('showOtpModal');
        sessionStorage.removeItem('otpEmail');
        clearInterval(resendRegisterTimer);
        resendRegisterTimerSpan.textContent = '';
        resendRegisterOtpLink.classList.remove('disabled-link');
    }
    
    document.querySelectorAll('.modal .close-button').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const modal = e.target.closest('.modal');
            if (modal.id === 'otpModal') {
                closeOtpModal();
            } else {
                closeModal(modal);
            }
            if (modal.id === 'resetOtpModal') {
                clearInterval(resendTimer);
                resendTimerSpan.textContent = '';
                resendOtpLink.classList.remove('disabled-link');
            }
        });
    });

    if (openModalBtns.length > 0 && signInUpModal) {
        openModalBtns.forEach(btn => {
            btn.onclick = function() {
                if (redirectUrlInput) {
                    redirectUrlInput.value = window.location.href;
                }
                signInUpModal.style.display = "flex";
                if (signInPanel) signInPanel.classList.add("active");
                if (registerPanel) registerPanel.classList.remove("active");
            };
        });
    }

    if(switchToRegisterLinks) {
        switchToRegisterLinks.forEach(link => {
            link.onclick = (e) => { e.preventDefault(); signInPanel.classList.remove("active"); registerPanel.classList.add("active"); };
        });
    }

    if(switchToSignInLink){
        switchToSignInLink.onclick = (e) => { e.preventDefault(); registerPanel.classList.remove("active"); signInPanel.classList.add("active"); };
    }

    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener('click', (e) => {
            e.preventDefault();
            closeModal(signInUpModal);
            forgotPasswordModal.style.display = 'flex';
        });
    }

    const alertModalTitle = document.getElementById('alertModalTitle');
    const alertModalMessage = document.getElementById('alertModalMessage');
    const alertModalOk = document.getElementById('alertModalOk');
    function showAlert(title, message) {
        alertModalTitle.textContent = title;
        alertModalMessage.textContent = message;
        alertModal.style.display = 'flex';
    }
    if (alertModalOk) alertModalOk.onclick = () => closeModal(alertModal);

    // --- NEW: Check sessionStorage on page load to show OTP modal if needed ---
    if (sessionStorage.getItem('showOtpModal') === 'true') {
        const userEmail = sessionStorage.getItem('otpEmail');
        if (userEmail) {
            document.getElementById('otpEmail').value = userEmail;
            otpModal.style.display = 'flex';
            startRegisterResendCountdown();
        }
    }

    // --- FORM SUBMISSIONS ---
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('registerConfirmPassword').value;

            if (password !== confirmPassword) {
                showAlert('Registration Failed', 'Passwords do not match. Please try again.');
                return; 
            }

            const submitBtn = registerForm.querySelector('button[type="submit"]');
            submitBtn.classList.add('btn-loading');

            const formData = new FormData(registerForm);
            const userEmail = formData.get('email');

            try {
                const response = await fetch('register.php', { method: 'POST', body: formData });
                if (!response.ok) {
                    throw new Error(`Server responded with status: ${response.status}`);
                }
                const data = await response.json();
                
                if (data.success) {
                    closeModal(signInUpModal);
                    document.getElementById('otpEmail').value = userEmail;
                    // MODIFIED: Save state to sessionStorage before showing modal
                    sessionStorage.setItem('showOtpModal', 'true');
                    sessionStorage.setItem('otpEmail', userEmail);
                    otpModal.style.display = 'flex';
                    startRegisterResendCountdown();
                } else {
                    showAlert('Registration Failed', data.message);
                }

            } catch (error) {
                console.error('Registration error:', error);
                showAlert('Error', 'An unexpected network error occurred. Please try again later.');
            } finally {
                submitBtn.classList.remove('btn-loading');
            }
        });
    }
    
    if (resendRegisterOtpLink) {
        resendRegisterOtpLink.addEventListener('click', async (e) => {
            e.preventDefault();
            if (resendRegisterOtpLink.classList.contains('disabled-link')) return;

            const userEmail = document.getElementById('otpEmail').value;
            if (!userEmail) {
                showAlert('Error', 'Could not find the email to resend the code.');
                return;
            }

            resendRegisterOtpLink.textContent = 'Sending...';
            resendRegisterOtpLink.classList.add('disabled-link');
            
            const formData = new FormData();
            formData.append('email', userEmail);

            try {
                const response = await fetch('resend_otp.php', { method: 'POST', body: formData });
                const data = await response.json();
                
                if (data.success) {
                    showAlert('Success', 'A new verification code has been sent.');
                    startRegisterResendCountdown();
                } else {
                    showAlert('Error', data.message || 'Failed to resend code.');
                    resendRegisterOtpLink.classList.remove('disabled-link');
                }
            } catch (error) {
                showAlert('Error', 'An unexpected network error occurred.');
                resendRegisterOtpLink.classList.remove('disabled-link');
            } finally {
                resendRegisterOtpLink.textContent = 'Resend Code';
            }
        });
    }

    const signInForm = document.getElementById('signInForm');
    if (signInForm) {
        signInForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = signInForm.querySelector('button[type="submit"]');
            submitBtn.classList.add('btn-loading');
            const formData = new FormData(signInForm);
            try {
                const response = await fetch('login.php', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.success) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.reload();
                    }
                } else {
                    showAlert('Login Failed', data.message);
                }
            } catch (error) {
                console.error('Login error:', error);
                showAlert('Error', 'An unexpected network error occurred.');
            } finally {
                submitBtn.classList.remove('btn-loading');
            }
        });
    }

    const otpForm = document.getElementById('otpForm');
    if (otpForm) {
        otpForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = otpForm.querySelector('button[type="submit"]');
            submitBtn.classList.add('btn-loading');
            const formData = new FormData(otpForm);

            try {
                const response = await fetch('verify_otp.php', { method: 'POST', body: formData });
                const data = await response.json();
                
                if (data.success) {
                    // MODIFIED: Clear session storage on success
                    closeOtpModal();
                    showAlert('Success!', data.message);
                } else {
                    showAlert('Verification Failed', data.message);
                }
            } catch (error) {
                console.error('OTP Verification error:', error);
                showAlert('Error', 'An unexpected network error occurred during verification.');
            } finally {
                submitBtn.classList.remove('btn-loading');
            }
        });
    }

    // --- PASSWORD RESET FLOW ---
    // (This section remains unchanged)
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const resetOtpForm = document.getElementById('resetOtpForm');
    const setNewPasswordForm = document.getElementById('setNewPasswordForm');

    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = forgotPasswordForm.querySelector('button[type="submit"]');
            submitBtn.classList.add('btn-loading');
            const formData = new FormData(forgotPasswordForm);
            const userEmail = formData.get('email');
            try {
                const response = await fetch('forgot_password.php', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.success) {
                    closeModal(forgotPasswordModal);
                    document.getElementById('resetOtpEmail').value = userEmail; 
                    resetOtpModal.style.display = 'flex';
                    startResendCountdown();
                } else {
                    showAlert('Error', data.message);
                }
            } catch (error) {
                console.error('Forgot password error:', error);
                showAlert('Error', 'An unexpected network error occurred.');
            } finally {
                submitBtn.classList.remove('btn-loading');
            }
        });
    }

    if (resendOtpLink) {
        resendOtpLink.addEventListener('click', async (e) => {
            e.preventDefault();
            if (resendOtpLink.classList.contains('disabled-link')) return;
            const userEmail = document.getElementById('resetOtpEmail').value;
            if (!userEmail) {
                showAlert('Error', 'Could not find the email to resend the code.');
                return;
            }
            resendOtpLink.textContent = 'Sending...';
            resendOtpLink.classList.add('disabled-link');
            const formData = new FormData();
            formData.append('email', userEmail);
            try {
                const response = await fetch('forgot_password.php', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.success) {
                    showAlert('Success', 'A new reset code has been sent to your email.');
                    startResendCountdown();
                } else {
                    showAlert('Error', data.message || 'Failed to resend code.');
                }
            } catch (error) {
                showAlert('Error', 'An unexpected network error occurred.');
            } finally {
                resendOtpLink.textContent = 'Resend Code';
            }
        });
    }

    if (resetOtpForm) {
        resetOtpForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = resetOtpForm.querySelector('button[type="submit"]');
            submitBtn.classList.add('btn-loading');
            const formData = new FormData(resetOtpForm);
            const userEmail = formData.get('email');
            try {
                const response = await fetch('verify_reset_otp.php', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.success) {
                    clearInterval(resendTimer);
                    closeModal(resetOtpForm);
                    document.getElementById('setNewPasswordEmail').value = userEmail; 
                    setNewPasswordModal.style.display = 'flex';
                } else {
                    showAlert('Verification Failed', data.message);
                }
            } catch (error) {
                console.error('Reset OTP error:', error);
            } finally {
                submitBtn.classList.remove('btn-loading');
            }
        });
    }

    if (setNewPasswordForm) {
        setNewPasswordForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = setNewPasswordForm.querySelector('button[type="submit"]');
            submitBtn.classList.add('btn-loading');
            const formData = new FormData(setNewPasswordForm);
            try {
                const response = await fetch('update_password.php', { method: 'POST', body: formData });
                const data = await response.json();
                closeModal(setNewPasswordModal);
                showAlert(data.success ? 'Success!' : 'Error', data.message);
            } catch (error) {
                console.error('Update password error:', error);
            } finally {
                submitBtn.classList.remove('btn-loading');
            }
        });
    }

    // --- REAL-TIME PASSWORD VALIDATION ---
    const registerPasswordInput = document.getElementById('registerPassword');
    const passwordRulesModal = document.getElementById('password-rules-modal');
    const lengthRule = document.getElementById('length');
    const capitalRule = document.getElementById('capital');
    const specialRule = document.getElementById('special');

    if (registerPasswordInput && passwordRulesModal && lengthRule && capitalRule && specialRule) {
        registerPasswordInput.addEventListener('focus', () => {
            passwordRulesModal.classList.add('show');
        });
        registerPasswordInput.addEventListener('blur', () => {
            passwordRulesModal.classList.remove('show');
        });
        registerPasswordInput.addEventListener('input', () => {
            const password = registerPasswordInput.value;
            if (password.length >= 6) { lengthRule.classList.replace('invalid', 'valid'); } 
            else { lengthRule.classList.replace('valid', 'invalid'); }
            if (/[A-Z]/.test(password)) { capitalRule.classList.replace('invalid', 'valid'); } 
            else { capitalRule.classList.replace('valid', 'invalid'); }
            if (/[^A-Za-z0-9]/.test(password)) { specialRule.classList.replace('invalid', 'valid'); } 
            else { specialRule.classList.replace('valid', 'invalid'); }
        });
    }
});
</script>