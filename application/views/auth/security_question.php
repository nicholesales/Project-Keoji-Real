<div class="container py-5 security-container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            
            <div class="card shadow-lg border-0 rounded-lg animate__animated animate__fadeIn">
                <div class="decorative-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                
                <div class="card-header bg-white border-0 pt-4">
                    <h3 class="text-center font-weight-bold mb-2">Security Verification</h3>
                    <p class="text-center text-muted mb-4">Please answer your security question</p>
                </div>
                <div class="card-body px-4 py-3">
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger animate__animated animate__shakeX">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= site_url('auth/process-security-question') ?>" method="post" class="security-form">
                        <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                        
                        <div class="security-question mb-4">
                            <label class="form-label text-muted">Your Security Question:</label>
                            <div class="question-display">
                                <i class="fas fa-shield-alt security-icon"></i>
                                <p class="mb-0 security-text"><?= $security_question ?></p>
                            </div>
                        </div>
                        
                        <div class="form-floating mb-4 input-group-animation">
                            <input type="text" class="form-control" id="security_answer" name="security_answer" placeholder="Your Answer" required>
                            <label for="security_answer">Your Answer</label>
                            <div class="focus-border"></div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-3 fw-bold pulse-on-hover">
                                <span>Verify Identity</span>
                                <svg class="btn-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white text-center border-0 py-4">
                    <p class="mb-0"><a href="<?= site_url('auth/login') ?>" class="text-decoration-none fw-bold hover-effect">
                        <i class="fas fa-arrow-left me-2"></i>Back to Login
                    </a></p>
                </div>
            </div>
            
            <!-- Security tips -->
            <div class="featured-post animate__animated animate__fadeInUp">
                <div class="featured-tag">Security Tips</div>
                <h4>Keep Your Account Secure</h4>
                <p>Never share your security answers with anyone. We'll never ask for them in emails or messages...</p>
                <a href="#">Learn More</a>
            </div>
        </div>
    </div>
</div>

<script>
// Add shake animation to security question if there's an error
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.alert-danger')) {
        document.querySelector('.question-display').classList.add('animate__animated', 'animate__headShake');
    }
});
</script>