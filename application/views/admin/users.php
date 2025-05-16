<div class="users-management card animate__animated animate__fadeIn">
    <div class="card-body">
        <h1 class="text-center mb-4">Manage Users</h1>
        
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
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Admin</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['user_id'] ?></td>
                        <td>
                            <?php if(!empty($user['profile_photo'])): ?>
                                <img src="<?= base_url('uploads/profiles/' . $user['profile_photo']) ?>" 
                                     alt="Profile" class="small-profile-pic me-2">
                            <?php endif; ?>
                            <?= $user['username'] ?>
                        </td>
                        <td><?= $user['email'] ?></td>
                        <td>
                            <?php if($user['is_admin']): ?>
                                <span class="badge bg-success">Yes</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">No</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('Y-m-d', strtotime($user['created_at'])) ?></td>
                        <td>
                            <a href="<?= site_url('admin/user-posts/' . $user['user_id']) ?>" 
                               class="btn btn-sm btn-primary" title="View Posts">
                                <i class="fas fa-clipboard-list"></i>
                            </a>
                            
                            <?php if($user['user_id'] != $this->session->userdata('user_id')): ?>
                                <a href="<?= site_url('admin/delete-user/' . $user['user_id']) ?>" 
                                   class="btn btn-sm btn-danger delete-confirm" 
                                   title="Delete User"
                                   data-confirm-message="Are you sure you want to delete this user? This will permanently delete their account and all their posts.">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<style>
    .users-management {
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .small-profile-pic {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    body.dark-theme .table {
        color: #eee;
    }
    
    body.dark-theme .table-hover tbody tr:hover {
        background-color: rgba(255,255,255,0.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirm before deleting
        document.querySelectorAll('.delete-confirm').forEach(function(element) {
            element.addEventListener('click', function(event) {
                const message = this.getAttribute('data-confirm-message');
                if (!confirm(message)) {
                    event.preventDefault();
                }
            });
        });
    });
</script>