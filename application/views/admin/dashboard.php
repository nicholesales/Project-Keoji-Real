<div class="admin-dashboard card animate__animated animate__fadeIn">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Admin Dashboard</h1>
            <a href="<?= site_url('auth/logout') ?>" class="btn btn-outline-danger">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </a>
        </div>
        
        <?php if($this->session->flashdata('message')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('message') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-card-body">
                        <div class="stats-card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stats-card-info">
                            <h2><?= $total_regular_users ?></h2>
                            <p>Regular Users</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-card-body">
                        <div class="stats-card-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stats-card-info">
                            <h2><?= $total_posts ?></h2>
                            <p>Total Posts</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="admin-actions">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="admin-card">
                        <div class="admin-card-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="admin-card-content">
                            <h3>User Management</h3>
                            <p>View, edit or remove user accounts and their posts</p>
                            <a href="<?= site_url('admin/users') ?>" class="btn btn-primary">Manage Users</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-4">
                    <div class="admin-card">
                        <div class="admin-card-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="admin-card-content">
                            <h3>Back to Site</h3>
                            <p>Return to the main site</p>
                            <a href="<?= site_url('posts') ?>" class="btn btn-primary">Go to Site</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .admin-dashboard {
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .stats-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    body.dark-theme .stats-card {
        background-color: #222;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    
    .stats-card-body {
        padding: 20px;
        display: flex;
        align-items: center;
    }
    
    .stats-card-icon {
        background: linear-gradient(135deg, #ff476c, #ff9f74);
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
    }
    
    .stats-card-icon i {
        font-size: 24px;
    }
    
    .stats-card-info h2 {
        font-size: 28px;
        margin-bottom: 0;
        font-weight: 700;
    }
    
    .stats-card-info p {
        color: #777;
        margin-bottom: 0;
    }
    
    body.dark-theme .stats-card-info p {
        color: #aaa;
    }
    
    .admin-actions {
        margin-top: 20px;
    }
    
    .admin-card {
        display: flex;
        align-items: center;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        background-color: #fff;
        height: 100%;
    }
    
    body.dark-theme .admin-card {
        background-color: #222;
    }
    
    .admin-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    
    .admin-card-icon {
        background: linear-gradient(135deg, #ff476c, #ff9f74);
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
    }
    
    .admin-card-icon i {
        font-size: 24px;
    }
    
    .admin-card-content {
        flex: 1;
    }
    
    .admin-card-content h3 {
        margin-bottom: 5px;
        font-size: 18px;
    }
    
    .admin-card-content p {
        color: #777;
        margin-bottom: 12px;
    }
    
    body.dark-theme .admin-card-content p {
        color: #aaa;
    }
</style>