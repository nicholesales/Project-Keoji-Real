<?php 
// Debug data
echo '<!-- Debug: recentPosts count: ' . (isset($recentPosts) ? count($recentPosts) : 'not set') . ' -->';
echo '<!-- Debug: featuredPosts count: ' . (isset($featuredPosts) ? count($featuredPosts) : 'not set') . ' -->';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Blog Posts</h1>
        <button type="button" class="btn btn-primary" id="newPostBtn">
            + New Post
        </button>
    </div>

    <!-- Recent Posts Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h2>Recent Posts</h2>
        </div>
        <div class="card-body">
            <?php if(empty($recentPosts)): ?>
                <p>No posts yet. Create your first post!</p>
            <?php else: ?>
                <?php foreach($recentPosts as $post): ?>
                    <div class="post-item d-flex justify-content-between align-items-center mb-3 p-2 border-bottom">
                        <div>
                            <div class="post-title">
                                <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>
                                <span class="badge <?= $post['status'] === 'draft' ? 'bg-secondary' : 'bg-success' ?>">
                                    <?= $post['status'] === 'draft' ? 'Draft' : 'Published' ?>
                                </span>
                            </div>
                            <div class="text-muted small">
                                <?= date('F d, Y', strtotime($post['date_created'])) ?>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-primary edit-post" data-id="<?= $post['post_id'] ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-post" data-id="<?= $post['post_id'] ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Featured Posts Section -->
    <div class="card">
        <div class="card-header">
            <h2>Featured Post</h2>
        </div>
        <div class="card-body">
            <?php if(empty($featuredPosts)): ?>
                <p>No featured posts yet. Mark a post as featured to see it here!</p>
            <?php else: ?>
                <?php foreach($featuredPosts as $post): ?>
                    <div class="featured-post row mb-4">
                        <div class="col-md-4">
                            <img src="<?= base_url('uploads/posts/' . $post['image']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="col-md-8">
                            <h3><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p><?= substr(htmlspecialchars($post['description'], ENT_QUOTES, 'UTF-8'), 0, 200) . (strlen($post['description']) > 200 ? '...' : '') ?></p>
                            <div class="d-flex justify-content-between">
                                <div class="text-muted">
                                    <?= date('F d, Y', strtotime($post['date_created'])) ?>
                                </div>
                                <div>
                                    <span class="badge bg-info"><?= htmlspecialchars($post['category'], ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
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
    // Clear any existing handlers to prevent duplicates
    $('#postForm').off('submit');
    $('#saveDraftBtn').off('click');
    $('#publishBtn').off('click');
    
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
        const url = postId ? '<?= base_url('posts/update') ?>/' + postId : '<?= base_url('posts/create') ?>';
        console.log('Submitting to URL:', url);
        
        // Submit form
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
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
                    alert('Error: ' + JSON.stringify(response.errors || response.message));
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', xhr.responseText);
                alert('An error occurred. Please check console for details.');
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
            url: '<?= base_url('posts/edit') ?>/' + postId,
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
                    $('#imagePreview').attr('src', '<?= base_url('uploads/posts') ?>/' + post.image);
                    $('#imagePreviewContainer').removeClass('d-none');
                    
                    // Image not required when editing
                    $('#image').removeAttr('required');
                    
                    // Show modal
                    $('#postModal').modal('show');
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
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
            url: '<?= base_url('posts/delete') ?>/' + postId,
            type: 'POST',
            data: { <?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>' },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#deleteModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
});
</script>