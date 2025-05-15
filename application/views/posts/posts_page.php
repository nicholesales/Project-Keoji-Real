<?php 
// Debug data
echo '<!-- Debug: recentPosts count: ' . (isset($recentPosts) ? count($recentPosts) : 'not set') . ' -->';
echo '<!-- Debug: featuredPosts count: ' . (isset($featuredPosts) ? count($featuredPosts) : 'not set') . ' -->';
?>

<style>
/* Custom styles for posts page */
.blog-posts-section {
    padding: 20px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-header h2 {
    font-size: 1.2rem;
    font-weight: 500;
    margin: 0;
}

.new-post-btn {
    background-color: #dc2626;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 0.9rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
}

.new-post-btn:hover {
    background-color: #b91c1c;
}

/* Posts cards */
.posts-container {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 24px;
}

.post-item {
    display: flex;
    align-items: flex-start;
    padding: 16px 0;
    border-bottom: 1px solid #f0f0f0;
}

.post-item:last-child {
    border-bottom: none;
}

.post-icon {
    width: 48px;
    height: 48px;
    background-color: #dc2626;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
    flex-shrink: 0;
}

.post-icon i {
    color: white;
    font-size: 24px;
}

.post-content {
    flex: 1;
}

.post-title {
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.post-title a {
    color: #333;
    text-decoration: none;
}

.post-title a:hover {
    text-decoration: underline;
}

.post-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
    font-size: 0.875rem;
}

.status-badge {
    font-size: 0.75rem;
    padding: 2px 8px;
    border-radius: 12px;
}

.status-badge.published {
    background-color: #10b981;
    color: white;
}

.status-badge.draft {
    background-color: #6b7280;
    color: white;
}

.post-actions {
    display: flex;
    gap: 8px;
    margin-left: auto;
}

.action-btn {
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 4px;
    transition: all 0.2s;
}

.action-btn:hover {
    background-color: #f5f5f5;
}

.action-btn.delete:hover {
    color: #dc2626;
}

/* Featured posts section */
.featured-post-container {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.featured-post-header {
    padding: 20px;
    border-bottom: 1px solid #f0f0f0;
}

.featured-post-header h2 {
    font-size: 1.2rem;
    font-weight: 500;
    margin: 0;
}

.featured-post-content {
    padding: 20px;
}

.featured-post-item {
    display: flex;
    gap: 20px;
    align-items: start;
}

.featured-post-image {
    width: 150px;
    height: 100px;
    object-fit: cover;
    border-radius: 4px;
    flex-shrink: 0;
}

.featured-post-details {
    flex: 1;
}

.featured-post-title {
    font-size: 1.1rem;
    font-weight: 500;
    margin-bottom: 8px;
    color: #333;
}

.featured-post-snippet {
    color: #666;
    font-size: 0.875rem;
    line-height: 1.5;
    margin-bottom: 12px;
}

.featured-post-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.featured-post-stats {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 0.875rem;
    color: #666;
}

.featured-post-stats i {
    margin-right: 4px;
}

.featured-post-date {
    font-size: 0.875rem;
    color: #999;
}

/* Empty states */
.empty-state {
    text-align: center;
    color: #666;
    padding: 40px 20px;
}

.empty-state i {
    font-size: 48px;
    color: #ddd;
    margin-bottom: 16px;
}

.tabs-nav {
    display: flex;
    gap: 24px;
    margin-bottom: 20px;
}

.tab-link {
    padding: 8px 0;
    border-bottom: 2px solid transparent;
    color: #666;
    text-decoration: none;
    font-size: 0.95rem;
    transition: all 0.2s;
}

.tab-link.active {
    color: #dc2626;
    border-bottom-color: #dc2626;
}

.tab-link:hover {
    color: #333;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .featured-post-item {
        flex-direction: column;
    }
    
    .featured-post-image {
        width: 100%;
        height: 200px;
    }
}
</style>

<div class="blog-posts-section">
    <!-- Tabs Navigation -->
    <div class="tabs-nav">
        <a href="#all" class="tab-link active">All</a>
        <a href="#published" class="tab-link">Published</a>
        <a href="#drafts" class="tab-link">Drafts</a>
    </div>

    <!-- Blog Posts Header -->
    <div class="section-header">
        <h2>Blog Posts</h2>
        <button type="button" class="new-post-btn" id="newPostBtn">
            <i class="bi bi-plus"></i> New Post
        </button>
    </div>

    <!-- Recent Posts Section -->
    <div class="posts-container">
        <?php if(empty($recentPosts)): ?>
            <div class="empty-state">
                <i class="bi bi-file-earmark-text"></i>
                <p>No posts yet. Create your first post!</p>
            </div>
        <?php else: ?>
            <?php foreach($recentPosts as $post): ?>
                <div class="post-item" data-post-id="<?= $post['post_id'] ?>">
                    <div class="post-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="post-content">
                        <div class="post-title">
                            <a href="<?= site_url('posts/view/' . $post['post_id']) ?>">
                                <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </div>
                        <div class="post-meta">
                            <span class="status-badge <?= $post['status'] === 'draft' ? 'draft' : 'published' ?>">
                                <?= $post['status'] === 'draft' ? 'Draft' : 'Published' ?>
                            </span>
                            <span><i class="bi bi-calendar3"></i> <?= date('M d, Y', strtotime($post['date_created'])) ?></span>
                        </div>
                    </div>
                    <div class="post-actions">
                        <button class="action-btn edit-post" data-id="<?= $post['post_id'] ?>" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="action-btn delete delete-post" data-id="<?= $post['post_id'] ?>" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Featured Posts Section -->
    <div class="section-header">
        <h2>Featured Post</h2>
    </div>
    
    <div class="featured-post-container">
        <?php if(empty($featuredPosts)): ?>
            <div class="empty-state">
                <i class="bi bi-star"></i>
                <p>No featured posts yet. Mark a post as featured to see it here!</p>
            </div>
        <?php else: ?>
            <?php foreach($featuredPosts as $post): ?>
                <div class="featured-post-content">
                    <div class="featured-post-item">
                        <?php if(!empty($post['image'])): ?>
                        <img src="<?= base_url('uploads/posts/' . $post['image']) ?>" 
                             class="featured-post-image" 
                             alt="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>">
                        <?php endif; ?>
                        <div class="featured-post-details">
                            <small class="text-muted mb-2 d-block">
                                Featured post with tag #<?= htmlspecialchars($post['category'], ENT_QUOTES, 'UTF-8') ?>
                            </small>
                            <h3 class="featured-post-title">
                                <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>
                            </h3>
                            <p class="featured-post-snippet">
                                <?= substr(htmlspecialchars($post['description'], ENT_QUOTES, 'UTF-8'), 0, 150) . (strlen($post['description']) > 150 ? '...' : '') ?>
                            </p>
                            <div class="featured-post-footer">
                                <div class="featured-post-stats">
                                    <span><i class="bi bi-heart"></i> <?= rand(500, 9999) ?></span>
                                    <span><i class="bi bi-chat"></i> <?= rand(50, 999) ?></span>
                                    <span><i class="bi bi-eye"></i> <?= number_format(rand(1000, 9999)) ?>k</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Post Modal -->
<div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postModalLabel">Create New Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="postForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="post_id" name="post_id">
                    <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Input text" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Dropdown</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?= $category ?>"><?= $category ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="5" placeholder="Input text" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png" required>
                        <div id="imagePreviewContainer" class="mt-2 d-none">
                            <img id="imagePreview" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="featured" name="featured">
                        <label class="form-check-label" for="featured">
                            Mark as featured post
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-outline-primary" id="saveDraftBtn">Save as Draft</button>
                    <button type="submit" class="btn btn-primary" id="publishBtn">Publish</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Delete Confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this post? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Store the CSRF token for reuse
    const csrfToken = '<?= $this->security->get_csrf_hash() ?>';
    const csrfName = '<?= $this->security->get_csrf_token_name() ?>';
    
    // Clear any existing handlers to prevent duplicates
    $('#postForm').off('submit');
    $('#saveDraftBtn').off('click');
    $('#publishBtn').off('click');
    
    // Tab switching functionality
    $('.tab-link').click(function(e) {
        e.preventDefault();
        $('.tab-link').removeClass('active');
        $(this).addClass('active');
        
        // Here you could filter posts based on the selected tab
        const filter = $(this).attr('href').substring(1);
        // Implement filtering logic here if needed
    });
    
    // Open modal for new post
    $('#newPostBtn').click(function() {
        $('#postModalLabel').text('Create New Post');
        $('#postForm')[0].reset();
        $('#post_id').val('');
        $('#imagePreviewContainer').addClass('d-none');
        $('#image').attr('required', true);
        $('#postModal').modal('show');
    });
    
    // Preview image
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
                $('#imagePreviewContainer').removeClass('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Form submission handler
    $('#postForm').on('submit', function(e) {
        console.log('Form submit event triggered');
        e.preventDefault();
        e.stopPropagation();
        const status = $(this).data('submit-type') || 'published';
        console.log('Submitting with status: ' + status);
        submitPost(status);
    });
    
    // Handle save as draft
    $('#saveDraftBtn').on('click', function(e) {
        console.log('Save Draft button clicked');
        e.preventDefault();
        e.stopPropagation();
        $('#postForm').data('submit-type', 'draft');
        $('#postForm').submit();
    });
    
    // Handle publish
    $('#publishBtn').on('click', function(e) {
        console.log('Publish button clicked');
        e.preventDefault();
        e.stopPropagation();
        $('#postForm').data('submit-type', 'published');
        $('#postForm').submit();
    });
    
    // Add isSubmitting flag to prevent multiple submissions
    let isSubmitting = false;
    
    // Submit post function
    function submitPost(status) {
        // Prevent multiple submissions
        if (isSubmitting) {
            console.log('Form is already being submitted');
            return;
        }
        
        // Validate form
        if (!$('#postForm')[0].checkValidity()) {
            $('#postForm')[0].reportValidity();
            return;
        }
        
        // Set flag to prevent multiple submissions
        isSubmitting = true;
        
        // Disable buttons during submission
        $('#saveDraftBtn, #publishBtn').prop('disabled', true);
        
        // Create form data
        const formData = new FormData($('#postForm')[0]);
        formData.append('status', status);
        
        // Get post ID
        const postId = $('#post_id').val();
        
        // Debug - log form data
        console.log('Form data:', Object.fromEntries(formData));
        
        // Determine URL based on whether this is an edit or create
        const url = postId 
            ? '<?= site_url("PostsController/update") ?>/' + postId 
            : '<?= site_url("PostsController/create") ?>';
        console.log('Submitting to URL:', url);
        
        // Submit form
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                console.log('Success response:', response);
                isSubmitting = false;
                $('#saveDraftBtn, #publishBtn').prop('disabled', false);
                
                if (response.success) {
                    // Close modal and refresh page
                    $('#postModal').modal('hide');
                    location.reload();
                } else {
                    // Display errors
                    console.error('Error response:', response);
                    let errorMessage = 'Error: ';
                    if (response.errors) {
                        errorMessage += Object.values(response.errors).join('\n');
                    } else {
                        errorMessage += response.message || 'Unknown error';
                    }
                    alert(errorMessage);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                alert('An error occurred. Status: ' + xhr.status + '. Please check console for details.');
                isSubmitting = false;
                $('#saveDraftBtn, #publishBtn').prop('disabled', false);
            }
        });
    }
    
    // Edit post
    $(document).on('click', '.edit-post', function() {
        const postId = $(this).data('id');
        
        // Fetch post data
        $.ajax({
            url: '<?= site_url("PostsController/edit") ?>/' + postId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const post = response.post;
                    
                    // Fill form
                    $('#postModalLabel').text('Edit Post');
                    $('#post_id').val(post.post_id);
                    $('#title').val(post.title);
                    $('#category').val(post.category);
                    $('#content').val(post.description);
                    $('#featured').prop('checked', post.featured == 1);
                    
                    // Show image preview
                    $('#imagePreview').attr('src', '<?= base_url("uploads/posts") ?>/' + post.image);
                    $('#imagePreviewContainer').removeClass('d-none');
                    
                    // Image not required when editing
                    $('#image').removeAttr('required');
                    
                    // Show modal
                    $('#postModal').modal('show');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('An error occurred. Status: ' + xhr.status);
            }
        });
    });
    
    // Delete post (show confirmation)
    $(document).on('click', '.delete-post', function() {
        const postId = $(this).data('id');
        $('#confirmDelete').data('id', postId);
        $('#deleteModal').modal('show');
    });
    
    // Confirm delete
    $('#confirmDelete').click(function() {
        const postId = $(this).data('id');
        
        $.ajax({
            url: '<?= site_url("PostsController/delete") ?>/' + postId,
            type: 'POST',
            data: { [csrfName]: csrfToken },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#deleteModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('An error occurred. Status: ' + xhr.status);
            }
        });
    });
});
</script>