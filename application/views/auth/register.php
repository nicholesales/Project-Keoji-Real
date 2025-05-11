<div class="container py-5 register-container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            
            <div class="card shadow-lg border-0 rounded-lg animate__animated animate__fadeIn">
                <div class="decorative-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                
                <div class="card-header bg-white border-0 pt-4">
                    <h3 class="text-center font-weight-bold mb-2">Create an Account</h3>
                    <p class="text-center text-muted mb-4">Join our community and start sharing</p>
                </div>
                <div class="card-body px-4 py-3">
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger animate__animated animate__shakeX">
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= site_url('auth/process-registration') ?>" method="post" class="register-form" id="registration-form">
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                        
                        <div class="form-floating mb-3 input-group-animation">
                            <input type="text" class="form-control" id="username" name="username" value="<?= set_value('username') ?>" placeholder="Username" required>
                            <label for="username">Username</label>
                            <div class="focus-border"></div>
                            <div class="invalid-feedback" id="username-feedback"></div>
                        </div>
                        
                        <div class="form-floating mb-3 input-group-animation">
                            <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email') ?>" placeholder="Email" required>
                            <label for="email">Email</label>
                            <div class="focus-border"></div>
                            <div class="invalid-feedback" id="email-feedback"></div>
                        </div>
                        
                        <div class="form-floating mb-1 input-group-animation">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                            <div class="focus-border"></div>
                            <button type="button" class="btn-toggle-password" onclick="togglePassword('password')">
                                <i class="fa fa-eye"></i>
                            </button>
                            <div class="invalid-feedback" id="password-feedback"></div>
                        </div>
                        
                        <div class="password-requirements mb-3">
                            <small class="form-text text-muted ps-2">Password must contain:</small>
                            <div class="d-flex flex-wrap gap-2 mt-1 ps-2">
                                <span id="length-check" class="password-check"><i class="fa fa-times-circle"></i> 8+ characters</span>
                                <span id="uppercase-check" class="password-check"><i class="fa fa-times-circle"></i> 1 uppercase</span>
                                <span id="number-check" class="password-check"><i class="fa fa-times-circle"></i> 1 number</span>
                                <span id="special-check" class="password-check"><i class="fa fa-times-circle"></i> 1 special character</span>
                            </div>
                        </div>
                        
                        <div class="form-floating mb-3 input-group-animation">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                            <label for="confirm_password">Confirm Password</label>
                            <div class="focus-border"></div>
                            <button type="button" class="btn-toggle-password" onclick="togglePassword('confirm_password')">
                                <i class="fa fa-eye"></i>
                            </button>
                            <div class="invalid-feedback" id="confirm-password-feedback"></div>
                        </div>
                        
                        <div class="form-floating mb-3 input-group-animation">
                            <select class="form-select" id="security_question" name="security_question" required>
                                <option value="">Select a security question</option>
                                <?php foreach($securityQuestions as $question): ?>
                                    <option value="<?= $question ?>" <?= (set_value('security_question') == $question) ? 'selected' : '' ?>>
                                        <?= $question ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="security_question">Security Question</label>
                            <div class="focus-border"></div>
                            <div class="invalid-feedback" id="security-question-feedback"></div>
                        </div>
                        
                        <div class="form-floating mb-4 input-group-animation">
                            <input type="text" class="form-control" id="security_answer" name="security_answer" value="<?= set_value('security_answer') ?>" placeholder="Answer to Security Question" required>
                            <label for="security_answer">Answer to Security Question</label>
                            <div class="focus-border"></div>
                            <div class="invalid-feedback" id="security-answer-feedback"></div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-3 fw-bold pulse-on-hover">
                                <span>Create Account</span>
                                <svg class="btn-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white text-center border-0 py-4">
                    <p class="mb-0">Already have an account? <a href="<?= site_url('auth/login') ?>" class="text-decoration-none fw-bold hover-effect">Sign in</a></p>
                </div>
            </div>
            
            <!-- Featured blog post preview -->
            <div class="featured-post animate__animated animate__fadeInUp">
                <div class="featured-tag">Community</div>
                <h4>Join 10,000+ bloggers sharing their stories</h4>
                <p>Create your personal blog and connect with readers around the world...</p>
                <a href="#">Learn More</a>
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
    
    return lengthValid && uppercaseValid && numberValid && specialValid;
}

// Update requirement check indicators
function updateRequirementCheck(id, isValid) {
    const element = document.getElementById(id);
    const icon = element.querySelector('i');
    
    if (isValid) {
        element.classList.add('valid');
        icon.classList.remove('fa-times-circle');
        icon.classList.add('fa-check-circle');
    } else {
        element.classList.remove('valid');
        icon.classList.remove('fa-check-circle');
        icon.classList.add('fa-times-circle');
    }
}

// Validate email format
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

// Initialize form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registration-form');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const usernameInput = document.getElementById('username');
    const securityQuestionInput = document.getElementById('security_question');
    const securityAnswerInput = document.getElementById('security_answer');
    
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
    
    // Email validation
    emailInput.addEventListener('blur', function() {
        if (!validateEmail(emailInput.value) && emailInput.value !== '') {
            emailInput.classList.add('is-invalid');
            document.getElementById('email-feedback').textContent = 'Please enter a valid email address';
            document.getElementById('email-feedback').style.display = 'block';
        } else {
            emailInput.classList.remove('is-invalid');
            document.getElementById('email-feedback').style.display = 'none';
        }
    });
    
    // Form submission validation
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // Validate username
        if (usernameInput.value.trim() === '') {
            usernameInput.classList.add('is-invalid');
            document.getElementById('username-feedback').textContent = 'Username is required';
            document.getElementById('username-feedback').style.display = 'block';
            isValid = false;
        } else {
            usernameInput.classList.remove('is-invalid');
            document.getElementById('username-feedback').style.display = 'none';
        }
        
        // Validate email
        if (!validateEmail(emailInput.value)) {
            emailInput.classList.add('is-invalid');
            document.getElementById('email-feedback').textContent = 'Please enter a valid email address';
            document.getElementById('email-feedback').style.display = 'block';
            isValid = false;
        } else {
            emailInput.classList.remove('is-invalid');
            document.getElementById('email-feedback').style.display = 'none';
        }
        
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
        
        // Validate security question
        if (securityQuestionInput.value === '') {
            securityQuestionInput.classList.add('is-invalid');
            document.getElementById('security-question-feedback').textContent = 'Please select a security question';
            document.getElementById('security-question-feedback').style.display = 'block';
            isValid = false;
        } else {
            securityQuestionInput.classList.remove('is-invalid');
            document.getElementById('security-question-feedback').style.display = 'none';
        }
        
        // Validate security answer
        if (securityAnswerInput.value.trim() === '') {
            securityAnswerInput.classList.add('is-invalid');
            document.getElementById('security-answer-feedback').textContent = 'Security answer is required';
            document.getElementById('security-answer-feedback').style.display = 'block';
            isValid = false;
        } else {
            securityAnswerInput.classList.remove('is-invalid');
            document.getElementById('security-answer-feedback').style.display = 'none';
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