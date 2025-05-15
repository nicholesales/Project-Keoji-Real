<div class="container py-5 register-container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            
            <div class="card shadow-lg border-0 rounded-lg animate__animated animate__fadeIn">
                <div class="decorative-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                
                <div class="card-header bg-white border-0 pt-4">
                    <h3 class="text-center font-weight-bold mb-2">Reset Password</h3>
                    <p class="text-center text-muted mb-4">Enter your new password below</p>
                </div>
                <div class="card-body px-4 py-3">
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger animate__animated animate__shakeX">
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success animate__animated animate__fadeInDown">
                            <?= $this->session->flashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= site_url('auth/process-reset-password') ?>" method="post" class="register-form" id="reset-password-form">
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                        <input type="hidden" name="token" value="<?= isset($token) ? $token : '' ?>">
                        
                        <div class="form-floating mb-1 input-group-animation">
                            <input type="password" class="form-control" id="password" name="password" placeholder="New Password" required>
                            <label for="password">New Password</label>
                            <div class="focus-border"></div>
                            <button type="button" class="btn-toggle-password" onclick="togglePassword('password')">
                                <i class="fa fa-eye"></i>
                            </button>
                            <div class="invalid-feedback" id="password-feedback"></div>
                        </div>
                        
                        <div class="password-requirements mb-3">
                            <small class="form-text text-muted ps-2">Password must contain:</small>
                            <div class="d-flex flex-wrap gap-2 mt-1 ps-2">
                                <span id="length-check" class="password-check invalid"><i class="fa fa-times-circle"></i> 8+ characters</span>
                                <span id="uppercase-check" class="password-check invalid"><i class="fa fa-times-circle"></i> 1 uppercase</span>
                                <span id="number-check" class="password-check invalid"><i class="fa fa-times-circle"></i> 1 number</span>
                                <span id="special-check" class="password-check invalid"><i class="fa fa-times-circle"></i> 1 special character</span>
                            </div>
                            
                            <!-- Password strength meter -->
                            <div class="password-strength-meter mt-2">
                                <div class="strength-bar" id="strength-bar"></div>
                            </div>
                            <span class="password-strength-text" id="strength-text"></span>
                        </div>
                        
                        <div class="form-floating mb-4 input-group-animation">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                            <label for="confirm_password">Confirm Password</label>
                            <div class="focus-border"></div>
                            <button type="button" class="btn-toggle-password" onclick="togglePassword('confirm_password')">
                                <i class="fa fa-eye"></i>
                            </button>
                            <div class="invalid-feedback" id="confirm-password-feedback"></div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-3 fw-bold pulse-on-hover">
                                <span>Reset Password</span>
                                <svg class="btn-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white text-center border-0 py-4">
                    <p class="mb-0">Remember your password? <a href="<?= site_url('auth/login') ?>" class="text-decoration-none fw-bold hover-effect">Sign In</a></p>
                </div>
            </div>
            
            <!-- Featured blog post preview -->
            <div class="featured-post animate__animated animate__fadeInUp">
                <div class="featured-tag">Latest Post</div>
                <h4>10 Tips for Better Blog Writing</h4>
                <p>Discover how to engage your audience with captivating content...</p>
                <a href="#">Read More</a>
            </div>
        </div>
    </div>
</div>

<script>
// Function to toggle password visibility
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const toggleButton = passwordInput.parentNode.querySelector('.btn-toggle-password i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.classList.remove('fa-eye');
        toggleButton.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleButton.classList.remove('fa-eye-slash');
        toggleButton.classList.add('fa-eye');
    }
}

// Calculate password strength score (0-100)
function calculatePasswordStrength(password) {
    if (!password) return 0;
    
    let score = 0;
    
    // Length contribution (up to 25 points)
    if (password.length >= 8) score += 10;
    if (password.length >= 10) score += 5;
    if (password.length >= 12) score += 5;
    if (password.length >= 14) score += 5;
    
    // Character variety contribution
    if (/[A-Z]/.test(password)) score += 15; // Uppercase
    if (/[a-z]/.test(password)) score += 10; // Lowercase
    if (/[0-9]/.test(password)) score += 15; // Numbers
    if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) score += 20; // Special chars
    
    // Character variety
    const charTypes = [/[A-Z]/, /[a-z]/, /[0-9]/, /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/].filter(regex => regex.test(password)).length;
    score += (charTypes - 1) * 5;
    
    // Penalize repetitions
    if (/(.)\1\1/.test(password)) score -= 10;
    
    // Cap the score at 100
    return Math.min(Math.max(score, 0), 100);
}

// Update password strength meter
function updatePasswordStrengthMeter(score) {
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');
    
    // Remove previous classes
    strengthBar.classList.remove('weak', 'medium', 'strong', 'very-strong');
    strengthText.classList.remove('weak', 'medium', 'strong', 'very-strong');
    
    // Update strength meter
    if (score < 40) {
        strengthBar.classList.add('weak');
        strengthText.classList.add('weak');
        strengthText.textContent = 'Weak';
    } else if (score < 60) {
        strengthBar.classList.add('medium');
        strengthText.classList.add('medium');
        strengthText.textContent = 'Medium';
    } else if (score < 80) {
        strengthBar.classList.add('strong');
        strengthText.classList.add('strong');
        strengthText.textContent = 'Strong';
    } else {
        strengthBar.classList.add('very-strong');
        strengthText.classList.add('very-strong');
        strengthText.textContent = 'Very Strong';
    }
}

// Validate password strength
function validatePassword() {
    const password = document.getElementById('password').value;
    
    // Password requirements
    const lengthValid = password.length >= 8;
    const uppercaseValid = /[A-Z]/.test(password);
    const numberValid = /[0-9]/.test(password);
    const specialValid = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
    
    // Update requirement indicators
    updateRequirementCheck('length-check', lengthValid);
    updateRequirementCheck('uppercase-check', uppercaseValid);
    updateRequirementCheck('number-check', numberValid);
    updateRequirementCheck('special-check', specialValid);
    
    // Calculate and update password strength
    const strengthScore = calculatePasswordStrength(password);
    updatePasswordStrengthMeter(strengthScore);
    
    return lengthValid && uppercaseValid && numberValid && specialValid;
}

// Update requirement check indicators
function updateRequirementCheck(id, isValid) {
    const element = document.getElementById(id);
    const icon = element.querySelector('i');
    
    if (isValid) {
        element.classList.remove('invalid');
        element.classList.add('valid');
        icon.classList.remove('fa-times-circle');
        icon.classList.add('fa-check-circle');
        // Add pulse animation when becoming valid
        icon.style.animation = 'none';
        setTimeout(() => {
            icon.style.animation = 'pulse-valid 0.5s ease-in-out';
        }, 10);
    } else {
        element.classList.remove('valid');
        element.classList.add('invalid');
        icon.classList.remove('fa-check-circle');
        icon.classList.add('fa-times-circle');
    }
}

// Initialize form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reset-password-form');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    // Initial password validation to set up indicators
    validatePassword();
    
    // Real-time password validation
    passwordInput.addEventListener('input', validatePassword);
    
    // Check if passwords match
    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (password !== confirmPassword) {
            confirmPasswordInput.classList.add('is-invalid');
            document.getElementById('confirm-password-feedback').textContent = 'Passwords do not match';
            document.getElementById('confirm-password-feedback').style.display = 'block';
        } else {
            confirmPasswordInput.classList.remove('is-invalid');
            document.getElementById('confirm-password-feedback').style.display = 'none';
        }
    });
    
    // Form submission validation
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // Validate password
        if (!validatePassword()) {
            passwordInput.classList.add('is-invalid');
            document.getElementById('password-feedback').textContent = 'Password does not meet requirements';
            document.getElementById('password-feedback').style.display = 'block';
            isValid = false;
        } else {
            passwordInput.classList.remove('is-invalid');
            document.getElementById('password-feedback').style.display = 'none';
        }
        
        // Validate password confirmation
        if (passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.classList.add('is-invalid');
            document.getElementById('confirm-password-feedback').textContent = 'Passwords do not match';
            document.getElementById('confirm-password-feedback').style.display = 'block';
            isValid = false;
        } else {
            confirmPasswordInput.classList.remove('is-invalid');
            document.getElementById('confirm-password-feedback').style.display = 'none';
        }
        
        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
            
            // Scroll to the first invalid input
            const firstInvalidInput = document.querySelector('.is-invalid');
            if (firstInvalidInput) {
                firstInvalidInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalidInput.focus();
            }
        }
    });
});
</script>