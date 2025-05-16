<div class="container py-5 login-container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="card shadow-lg border-0 rounded-lg animate__animated animate__fadeIn">
                <div class="decorative-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="card-header border-0 pt-4">
                    <h3 class="text-center font-weight-bold mb-2">Password Recovery</h3>
                    <p class="text-center text-muted mb-4">Enter your email to reset your password</p>
                </div>
                <div class="card-body px-4 py-3">
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger animate__animated animate__shakeX d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success animate__animated animate__fadeInDown d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= $this->session->flashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('auth/process-forgot-password') ?>" method="post" class="login-form">
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                        <div class="form-floating mb-4 input-group-animation">
                            <input type="email" class="form-control" id="email" name="email" 
                                value="<?= set_value('email') ?>" placeholder="name@example.com" required>
                            <label for="email">Email address</label>
                            <div class="focus-border"></div>
                        </div>
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-3 fw-bold pulse-on-hover">
                                <span>Reset Password</span>
                                <svg class="btn-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center border-0 py-4">
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
// Initialize dark mode based on stored preference
document.addEventListener('DOMContentLoaded', function() {
    // Add input focus animations
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});
</script>