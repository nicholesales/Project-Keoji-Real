<?php
// This is the updated login.php file that integrates perfectly with the main layout
?>
<!-- Background animation and particles are handled by the main layout -->

<div class="container py-5 login-container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            
            <div class="card shadow-lg border-0 rounded-lg animate__animated animate__fadeIn">
                <div class="decorative-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                
                <div class="card-header bg-white border-0 pt-4">
                    <h3 class="text-center font-weight-bold mb-2">Welcome Back</h3>
                    <p class="text-center text-muted mb-4">Sign in to continue to your dashboard</p>
                </div>
                <div class="card-body px-4 py-3">
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger animate__animated animate__shakeX">
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= site_url('auth/process-login') ?>" method="post" class="login-form">
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                        
                        <div class="form-floating mb-3 input-group-animation">
                            <input type="text" class="form-control" id="username" name="username" value="<?= set_value('username') ?>" placeholder="Username" required>
                            <label for="username">Username</label>
                            <div class="focus-border"></div>
                        </div>
                        
                        <div class="form-floating mb-4 input-group-animation">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            <label for="password">Password</label>
                            <div class="focus-border"></div>
                            <button type="button" class="btn-toggle-password" onclick="togglePassword()">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label text-muted" for="remember">
                                Remember me
                            </label>
                            <a href="<?= site_url('auth/forgot-password') ?>" class="float-end text-decoration-none hover-effect">Forgot Password?</a>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-3 fw-bold pulse-on-hover">
                                <span>Sign In</span>
                                <svg class="btn-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white text-center border-0 py-4">
                    <p class="mb-0">Don't have an account? <a href="<?= site_url('auth/register') ?>" class="text-decoration-none fw-bold hover-effect">Create an account</a></p>
                </div>
            </div>
            
            <!-- Featured blog post preview -->
            <div class="featured-post animate__animated animate__fadeInUp">
                <div class="featured-tag">Latest Post</div>
                <h4>10 Tips for Better Blog Writing</h4>
                <p>Discover how to engage your audience with captivating content...</p>
                <a href="#" class="read-more-link">Read More</a>
            </div>
        </div>
    </div>
</div>

<!-- Let's add some complementary CSS for the login page that matches the main theme -->
<style>
    /* Login form specific styles */

</style>

<script>
// Function to toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleButton = document.querySelector('.btn-toggle-password i');
    
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
</script>