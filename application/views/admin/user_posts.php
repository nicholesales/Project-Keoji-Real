<div class="user-posts card animate__animated animate__fadeIn">
    <div class="card-body">
        <h1 class="text-center mb-4">Posts by <?= $user['username'] ?></h1>
        
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
        
        <?php if(empty($posts)): ?>
            <div class="alert alert-info">
                This user has no posts.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach($posts as $post): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="post-card">
                            <div class="post-header">
                                <div class="post-date">
                                    <?= date('M d, Y', strtotime($post['date_created'])) ?>
                                </div>
                                <div class="post-actions">
                                    <a href="<?= site_url('admin/delete-post/' . $post['post_id']) ?>" 
                                       class="btn btn-sm btn-danger delete-confirm"
                                       data-confirm-message="Are you sure you want to delete this post?">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <?php if(!empty($post['image'])): ?>
                                <div class="post-image">
                                    <img src="<?= base_url('uploads/posts/' . $post['image']) ?>" alt="Post Image">
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-content">
                                <h3><?= $post['title'] ?></h3>
                                <p><?= substr($post['description'], 0, 150) . (strlen($post['description']) > 150 ? '...' : '') ?></p>
                            </div>
                            
                            <div class="post-footer">
                                <div class="post-meta">
                                    <span class="category"><?= $post['category'] ?></span>
                                    <?php if(isset($post['status'])): ?>
                                        <span class="status">
                                            <span class="badge <?= $post['status'] === 'published' ? 'bg-success' : 'bg-secondary' ?>">
                                                <?= ucfirst($post['status']) ?>
                                            </span>
                                        </span>
                                    <?php endif; ?>
                                    <?php if(isset($post['featured']) && $post['featured']): ?>
                                        <span class="featured">
                                            <span class="badge bg-warning">Featured</span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="mt-3">
            <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Users
            </a>
        </div>
    </div>
</div>

<style>
    .user-posts {
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .post-card {
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        background-color: #fff;
    }
    
    body.dark-theme .post-card {
        background-color: #222;
        color: #eee;
    }
    
    .post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    
    .post-header {
        padding: 12px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
    }
    
    body.dark-theme .post-header {
        border-color: #333;
    }
    
    .post-image {
        height: 180px;
        overflow: hidden;
    }
    
    .post-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .post-content {
        padding: 15px;
        flex-grow: 1;
    }
    
    .post-content h3 {
        font-size: 18px;
        margin-bottom: 10px;
    }
    
    .post-content p {
        color: #666;
        font-size: 14px;
    }
    
    body.dark-theme .post-content p {
        color: #aaa;
    }
    
    .post-footer {
        padding: 12px 15px;
        border-top: 1px solid #eee;
    }
    
    body.dark-theme .post-footer {
        border-color: #333;
    }
    
    .post-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .category {
        font-size: 13px;
        color: #555;
        background-color: #f0f0f0;
        padding: 3px 8px;
        border-radius: 10px;
    }
    
    body.dark-theme .category {
        background-color: #333;
        color: #ccc;
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