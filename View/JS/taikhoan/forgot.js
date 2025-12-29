// Validate form đổi mật khẩu
document.getElementById('resetPasswordForm')?.addEventListener('submit', function(e) {
    let newPass = document.getElementById('new_password').value;
    let confirmPass = document.getElementById('confirm_password').value;
    let captcha = grecaptcha.getResponse();
    
    let errorNew = document.getElementById('error_new');
    let errorConfirm = document.getElementById('error_confirm');
    let errorCaptcha = document.getElementById('error_captcha');
    
    errorNew.textContent = '';
    errorConfirm.textContent = '';
    errorCaptcha.textContent = '';
    
    let isValid = true;
    
    // if (newPass.length < 6) {
    //     errorNew.textContent = 'Mật khẩu phải có ít nhất 6 ký tự';
    //     isValid = false;
    // }
    
    if (newPass !== confirmPass) {
        errorConfirm.textContent = 'Mật khẩu xác nhận không khớp';
        isValid = false;
    }
    
    if (captcha === '') {
        errorCaptcha.textContent = 'Bạn không phải con ng';
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});