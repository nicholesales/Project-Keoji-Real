<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Profile</h3>
                </div>
                <div class="card-body">
                    <?php 
                    // Debug: Check session data
                    echo '<!-- Debug Session: ' . print_r($this->session->userdata(), true) . ' -->';
                    ?>
                    
                    <?php if($this->session->flashdata('message')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('message') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php foreach($this->session->flashdata('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('user/update-profile') ?>" method="POST" enctype="multipart/form-data">
                        
                        <!-- Profile Photo -->
                        <div class="mb-3 text-center">
                            <div class="profile-photo-container">
                                <?php if($user['profile_photo']): ?>
                                    <img src="<?= base_url('uploads/profiles/' . $user['profile_photo']) ?>" 
                                         alt="Profile Photo" 
                                         class="rounded-circle" 
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                         style="width: 150px; height: 150px; margin: 0 auto;">
                                        <i class="bi bi-person-fill text-white" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mt-3">
                                <label for="profile_photo" class="form-label">Profile Photo</label>
                                <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/jpg,image/jpeg,image/png">
                                <small class="text-muted">Allowed formats: JPG, PNG. Max file size: 5MB. Image will be cropped to a square.</small>
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= set_value('username', $user['username']) ?>" required minlength="3" maxlength="30">
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= set_value('email', $user['email']) ?>" required>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" minlength="8">
                            <small class="text-muted">Leave blank to keep current password. Minimum 8 characters. Cannot be the same as current password.</small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>

                        <!-- Security Question -->
                        <div class="mb-3">
                            <label for="security_question" class="form-label">Security Question</label>
                            <select class="form-select" id="security_question" name="security_question" required>
                                <option value="">Select a security question</option>
                                <?php foreach($securityQuestions as $question): ?>
                                    <option value="<?= $question ?>" 
                                            <?= ($user['security_question'] == $question) ? 'selected' : '' ?>>
                                        <?= $question ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Security Answer -->
                        <div class="mb-3">
                            <label for="security_answer" class="form-label">Security Answer</label>
                            <input type="text" class="form-control" id="security_answer" name="security_answer" 
                                   value="<?= set_value('security_answer', $user['security_answer']) ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Profile</button>
                        <a href="<?= site_url('posts') ?>" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Preview profile photo before upload
document.getElementById('profile_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please upload only JPG or PNG images.');
            this.value = '';
            return;
        }
        
        // Check file size (5MB = 5242880 bytes)
        if (file.size > 5242880) {
            alert('File size must be less than 5MB.');
            this.value = '';
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgContainer = document.querySelector('.profile-photo-container');
            imgContainer.innerHTML = `
                <img src="${e.target.result}" 
                     alt="Profile Photo Preview" 
                     class="rounded-circle" 
                     style="width: 150px; height: 150px; object-fit: cover;">
            `;
        };
        reader.readAsDataURL(file);
    }
});

// Client-side password validation
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password || confirmPassword) {
        if (password.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long.');
            return;
        }
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Password and Confirm Password do not match.');
            return;
        }
    }
});
</script>