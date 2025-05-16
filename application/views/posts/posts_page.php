<link rel="stylesheet" href="<?= base_url('assets/css/posts_page.css'); ?>">

<?php 
// Debug data
echo '<!-- Debug: recentPosts count: ' . (isset($recentPosts) ? count($recentPosts) : 'not set') . ' -->';
echo '<!-- Debug: featuredPosts count: ' . (isset($featuredPosts) ? count($featuredPosts) : 'not set') . ' -->';
?>

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

    <!-- Featured Posts Section with Carousel -->
    <div class="section-header">
        <h2>Featured Posts</h2>
    </div>
    
    <?php if(empty($featuredPosts)): ?>
        <div class="featured-post-carousel">
            <div class="featured-post-empty">
                <i class="bi bi-star"></i>
                <p>No featured posts yet. Mark a post as featured to see it here!</p>
            </div>
        </div>
    <?php else: ?>
        <!-- Bootstrap carousel implementation with enhanced fade-right transition -->
        <div id="featuredPostCarousel" class="carousel slide featured-post-carousel" data-bs-ride="carousel">
            <!-- Carousel indicators -->
            <div class="carousel-indicators">
                <?php foreach($featuredPosts as $index => $post): ?>
                    <button type="button" 
                        data-bs-target="#featuredPostCarousel" 
                        data-bs-slide-to="<?= $index ?>" 
                        <?= $index === 0 ? 'class="active" aria-current="true"' : '' ?> 
                        aria-label="Slide <?= $index + 1 ?>">
                    </button>
                <?php endforeach; ?>
            </div>
            
            <!-- Carousel items with enhanced fade-right transition -->
            <div class="carousel-inner">
                <?php foreach($featuredPosts as $index => $post): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="featured-post-item">
                            <?php if(!empty($post['image'])): ?>
                                <img src="<?= base_url('uploads/posts/' . $post['image']) ?>" 
                                    class="featured-post-image" 
                                    alt="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>">
                            <?php else: ?>
                                <div class="featured-post-placeholder">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                            <?php endif; ?>
                            <div class="featured-post-details">
                                <small class="text-muted mb-2 d-block">
                                    Featured post with tag #<?= htmlspecialchars($post['category'], ENT_QUOTES, 'UTF-8') ?>
                                </small>
                                <h3 class="featured-post-title">
                                    <a href="<?= site_url('posts/view/' . $post['post_id']) ?>">
                                        <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>
                                    </a>
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
            </div>
            
            <!-- Enhanced transparent carousel controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#featuredPostCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#featuredPostCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- Enhanced Post Modal with Animation -->
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
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter post title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Select a category</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?= $category ?>"><?= $category ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="3" placeholder="Write your post content here" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label d-block">Image</label>
                        <label for="image" class="custom-file-upload">
                            <i class="bi bi-cloud-upload"></i> Choose Image
                        </label>
                        <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png">
                        <small class="text-muted">Optional: Upload a featured image for your post</small>
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

<!-- Enhanced Modal for Delete Confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 48px;"></i>
                </div>
                <p class="text-center">Are you sure you want to delete this post?</p>
                <p class="text-center text-muted small">This action cannot be undone.</p>
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
    
    // Initialize the featured posts carousel with enhanced animations
    const featuredCarousel = $('#featuredPostCarousel');
    if (featuredCarousel.length) {
        // Initialize with Bootstrap 5 syntax if available
        if (typeof bootstrap !== 'undefined') {
            const carousel = new bootstrap.Carousel(document.getElementById('featuredPostCarousel'), {
                interval: 5000,  // 5 seconds per slide
                wrap: true,      // Continuous loop
                touch: true      // Enable touch swiping
            });
        } else {
            // Fallback to jQuery method for Bootstrap 4
            featuredCarousel.carousel({
                interval: 5000,
                wrap: true
            });
        }
        
        // Add swipe support for touch devices
        if ('ontouchstart' in window) {
            let startX, endX;
            const carousel = document.getElementById('featuredPostCarousel');
            
            carousel.addEventListener('touchstart', function(e) {
                startX = e.touches[0].pageX;
            }, { passive: true });
            
            carousel.addEventListener('touchmove', function(e) {
                endX = e.touches[0].pageX;
            }, { passive: true });
            
            carousel.addEventListener('touchend', function(e) {
                const threshold = 100; // Minimum distance for swipe
                const diff = startX - endX;
                
                if (Math.abs(diff) >= threshold) {
                    if (diff > 0) {
                        // Swiped left, go to next slide
                        if (typeof bootstrap !== 'undefined') {
                            const carouselInstance = bootstrap.Carousel.getInstance(carousel);
                            carouselInstance.next();
                        } else {
                            $(carousel).carousel('next');
                        }
                    } else {
                        // Swiped right, go to previous slide
                        if (typeof bootstrap !== 'undefined') {
                            const carouselInstance = bootstrap.Carousel.getInstance(carousel);
                            carouselInstance.prev();
                        } else {
                            $(carousel).carousel('prev');
                        }
                    }
                }
            }, { passive: true });
        }
        
        // Enhance the fade-right transition with smoother animations
        featuredCarousel.on('slide.bs.carousel', function(e) {
            const $nextSlide = $(e.relatedTarget);
            const direction = e.direction === 'left' ? 1 : -1;
            
            // Set initial state for the next slide
            $nextSlide.css({
                'transform': `translateX(${30 * direction}px)`,
                'opacity': '0'
            });
            
            // After a brief delay, animate to visible state
            setTimeout(function() {
                $nextSlide.css({
                    'transform': 'translateX(0)',
                    'opacity': '1'
                });
            }, 50);
        });
    }
    
    // Tab switching functionality with animation
    $('.tab-link').click(function(e) {
        e.preventDefault();
        $('.tab-link').removeClass('active');
        $(this).addClass('active');
        
        // Add a subtle animation to indicate the tab change
        $('.posts-container').addClass('fade').fadeOut(200).fadeIn(300).removeClass('fade');
        
        // Here you could filter posts based on the selected tab
        const filter = $(this).attr('href').substring(1);
        // Implement filtering logic here if needed
    });
    
    // Open modal for new post with animation
    $('#newPostBtn').click(function() {
        $('#postModalLabel').text('Create New Post');
        $('#postForm')[0].reset();
        $('#post_id').val('');
        $('#imagePreviewContainer').addClass('d-none');
        $('#postModal').modal('show');
        
        // Add a slight entrance animation for form elements
        $('.modal-body .mb-3').each(function(index) {
            $(this).css({
                'opacity': '0',
                'transform': 'translateY(20px)'
            });
            
            setTimeout(() => {
                $(this).css({
                    'transition': 'all 0.3s ease',
                    'opacity': '1',
                    'transform': 'translateY(0)'
                });
            }, 100 + (index * 50));
        });
    });
    
    // Enhance image preview with animation
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
                $('#imagePreviewContainer').removeClass('d-none').css({
                    'opacity': '0',
                    'transform': 'scale(0.9)'
                });
                
                setTimeout(() => {
                    $('#imagePreviewContainer').css({
                        'transition': 'all 0.3s ease',
                        'opacity': '1',
                        'transform': 'scale(1)'
                    });
                }, 10);
            }
            reader.readAsDataURL(file);
        }
    });
    
    // Form submission handler with improved UI feedback
    $('#postForm').on('submit', function(e) {
        console.log('Form submit event triggered');
        e.preventDefault();
        e.stopPropagation();
        const status = $(this).data('submit-type') || 'published';
        console.log('Submitting with status: ' + status);
        submitPost(status);
    });
    
    // Handle save as draft with button animation
    $('#saveDraftBtn').on('click', function(e) {
        console.log('Save Draft button clicked');
        e.preventDefault();
        e.stopPropagation();
        
        // Add button press animation
        $(this).addClass('active').css('transform', 'scale(0.95)');
        setTimeout(() => {
            $(this).removeClass('active').css('transform', '');
        }, 200);
        
        $('#postForm').data('submit-type', 'draft');
        $('#postForm').submit();
    });
    
    // Handle publish with button animation
    $('#publishBtn').on('click', function(e) {
        console.log('Publish button clicked');
        e.preventDefault();
        e.stopPropagation();
        
        // Add button press animation
        $(this).addClass('active').css('transform', 'scale(0.95)');
        setTimeout(() => {
            $(this).removeClass('active').css('transform', '');
        }, 200);
        
        $('#postForm').data('submit-type', 'published');
        $('#postForm').submit();
    });
    
    // Add isSubmitting flag to prevent multiple submissions
    let isSubmitting = false;
    
    // Enhanced submit post function with better UX
    function submitPost(status) {
        // Prevent multiple submissions
        if (isSubmitting) {
            console.log('Form is already being submitted');
            return;
        }
        
        // Validate form
        if (!$('#postForm')[0].checkValidity()) {
            $('#postForm')[0].reportValidity();
            
            // Shake animation for invalid fields
            $('.form-control:invalid, .form-select:invalid').css('animation', 'shake 0.5s');
            setTimeout(() => {
                $('.form-control, .form-select').css('animation', '');
            }, 500);
            
            return;
        }
        
        // Set flag to prevent multiple submissions
        isSubmitting = true;
        
        // Show loading state on buttons
        $('#saveDraftBtn, #publishBtn').prop('disabled', true);
        const activeBtn = status === 'draft' ? $('#saveDraftBtn') : $('#publishBtn');
        const originalText = activeBtn.html();
        activeBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        
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
        
        // Submit form with improved error handling
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
                
                if (response.success) {
                    // Show success animation before closing modal
                    $('#postModal .modal-content').append(
                        '<div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-white bg-opacity-90" style="z-index: 1050; animation: fadeIn 0.3s;">' +
                        '<div class="text-center">' +
                        '<i class="bi bi-check-circle text-success" style="font-size: 48px; animation: scaleIn 0.5s;"></i>' +
                        '<h5 class="mt-3">Post ' + (postId ? 'Updated' : 'Created') + ' Successfully!</h5>' +
                        '</div>' +
                        '</div>'
                    );
                    
                    // Close modal and refresh page after delay
                    setTimeout(function() {
                        $('#postModal').modal('hide');
                        location.reload();
                    }, 1200);
                } else {
                    // Reset button state
                    $('#saveDraftBtn, #publishBtn').prop('disabled', false);
                    activeBtn.html(originalText);
                    
                    // Display errors with animation
                    console.error('Error response:', response);
                    let errorMessage = 'Error: ';
                    if (response.errors) {
                        errorMessage += Object.values(response.errors).join('\n');
                    } else {
                        errorMessage += response.message || 'Unknown error';
                    }
                    
                    // Create animated error toast
                    $('body').append(
                        '<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">' +
                        '<div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">' +
                        '<div class="d-flex">' +
                        '<div class="toast-body">' + errorMessage + '</div>' +
                        '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );
                    
                    // Show toast with Bootstrap 5
                    if (typeof bootstrap !== 'undefined') {
                        const toastEl = document.querySelector('.toast');
                        const toast = new bootstrap.Toast(toastEl, { delay: 5000 });
                        toast.show();
                    } else {
                        // Fallback for Bootstrap 4
                        $('.toast').toast({ delay: 5000 }).toast('show');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                
                // Reset button state
                isSubmitting = false;
                $('#saveDraftBtn, #publishBtn').prop('disabled', false);
                activeBtn.html(originalText);
                
                // Create animated error toast
                $('body').append(
                    '<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">' +
                    '<div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">' +
                    '<div class="d-flex">' +
                    '<div class="toast-body">An error occurred. Status: ' + xhr.status + '. Please check console for details.</div>' +
                    '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );
                
                // Show toast with Bootstrap 5
                if (typeof bootstrap !== 'undefined') {
                    const toastEl = document.querySelector('.toast');
                    const toast = new bootstrap.Toast(toastEl, { delay: 5000 });
                    toast.show();
                } else {
                    // Fallback for Bootstrap 4
                    $('.toast').toast({ delay: 5000 }).toast('show');
                }
            }
        });
    }
    
    // Enhanced edit post with animations
    $(document).on('click', '.edit-post', function() {
        const postId = $(this).data('id');
        
        // Add loading animation to the post item
        const postItem = $(this).closest('.post-item');
        postItem.css({
            'position': 'relative',
            'overflow': 'hidden'
        }).append('<div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-light bg-opacity-50"><div class="spinner-border text-primary" role="status"></div></div>');
        
        // Fetch post data
        $.ajax({
            url: '<?= site_url("PostsController/edit") ?>/' + postId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Remove loading animation
                postItem.find('.position-absolute').remove();
                postItem.css({
                    'position': '',
                    'overflow': ''
                });
                
                if (response.success) {
                    const post = response.post;
                    
                    // Fill form
                    $('#postModalLabel').text('Edit Post');
                    $('#post_id').val(post.post_id);
                    $('#title').val(post.title);
                    $('#category').val(post.category);
                    $('#content').val(post.description);
                    $('#featured').prop('checked', post.featured == 1);
                    
                    // Show image preview if available
                    if (post.image) {
                        $('#imagePreview').attr('src', '<?= base_url("uploads/posts") ?>/' + post.image);
                        $('#imagePreviewContainer').removeClass('d-none');
                    } else {
                        $('#imagePreviewContainer').addClass('d-none');
                    }
                    
                    // Image not required when editing
                    $('#image').removeAttr('required');
                    
                    // Show modal with animation
                    $('#postModal').modal('show');
                    
                    // Add a slight entrance animation for form elements
                    $('.modal-body .mb-3').each(function(index) {
                        $(this).css({
                            'opacity': '0',
                            'transform': 'translateY(20px)'
                        });
                        
                        setTimeout(() => {
                            $(this).css({
                                'transition': 'all 0.3s ease',
                                'opacity': '1',
                                'transform': 'translateY(0)'
                            });
                        }, 100 + (index * 50));
                    });
                } else {
                    // Show error toast
                    $('body').append(
                        '<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">' +
                        '<div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">' +
                        '<div class="d-flex">' +
                        '<div class="toast-body">Error: ' + response.message + '</div>' +
                        '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );
                    
                    // Show toast with Bootstrap 5
                    if (typeof bootstrap !== 'undefined') {
                        const toastEl = document.querySelector('.toast');
                        const toast = new bootstrap.Toast(toastEl, { delay: 5000 });
                        toast.show();
                    } else {
                        // Fallback for Bootstrap 4
                        $('.toast').toast({ delay: 5000 }).toast('show');
                    }
                }
            },
            error: function(xhr) {
                // Remove loading animation
                postItem.find('.position-absolute').remove();
                postItem.css({
                    'position': '',
                    'overflow': ''
                });
                
                // Show error toast
                $('body').append(
                    '<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">' +
                    '<div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">' +
                    '<div class="d-flex">' +
                    '<div class="toast-body">An error occurred. Status: ' + xhr.status + '</div>' +
                    '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );
                
                // Show toast with Bootstrap 5
                if (typeof bootstrap !== 'undefined') {
                    const toastEl = document.querySelector('.toast');
                    const toast = new bootstrap.Toast(toastEl, { delay: 5000 });
                    toast.show();
                } else {
                    // Fallback for Bootstrap 4
                    $('.toast').toast({ delay: 5000 }).toast('show');
                }
            }
        });
    });
    
    // Delete post with enhanced confirmation
    $(document).on('click', '.delete-post', function() {
        const postId = $(this).data('id');
        $('#confirmDelete').data('id', postId);
        
        // Find the post title for confirmation message
        const postTitle = $(this).closest('.post-item').find('.post-title a').text().trim();
        if (postTitle) {
            $('#deleteModal .modal-body p:first').text('Are you sure you want to delete "' + postTitle + '"?');
        }
        
        // Show modal with subtle entrance animation
        $('#deleteModal').modal('show');
        $('#deleteModal .bi-exclamation-triangle').css({
            'transform': 'scale(0.5)',
            'opacity': '0'
        });
        
        setTimeout(() => {
            $('#deleteModal .bi-exclamation-triangle').css({
                'transition': 'all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)',
                'transform': 'scale(1)',
                'opacity': '1'
            });
        }, 100);
    });
    
    // Confirm delete with animations
    $('#confirmDelete').click(function() {
        const postId = $(this).data('id');
        const $button = $(this);
        
        // Show loading state
        $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...');
        
        $.ajax({
            url: '<?= site_url("PostsController/delete") ?>/' + postId,
            type: 'POST',
            data: { [csrfName]: csrfToken },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success animation in modal
                    $('#deleteModal .modal-body').html(
                        '<div class="text-center">' +
                        '<i class="bi bi-check-circle text-success" style="font-size: 48px; animation: scaleIn 0.5s;"></i>' +
                        '<p class="mt-3">Post deleted successfully!</p>' +
                        '</div>'
                    );
                    
                    // Find the post item to animate it out
                    const postItem = $('.post-item[data-post-id="' + postId + '"]');
                    postItem.css({
                        'transition': 'all 0.5s ease',
                        'transform': 'translateX(100px)',
                        'opacity': '0',
                        'height': postItem.height() + 'px'
                    });
                    
                    // After animation, close modal and refresh page
                    setTimeout(function() {
                        $('#deleteModal').modal('hide');
                        postItem.css({
                            'height': '0',
                            'padding': '0',
                            'margin': '0',
                            'overflow': 'hidden'
                        });
                        
                        setTimeout(function() {
                            postItem.remove();
                            
                            // If no posts left, show empty state
                            if ($('.post-item').length === 0) {
                                $('.posts-container').html(
                                    '<div class="empty-state">' +
                                    '<i class="bi bi-file-earmark-text"></i>' +
                                    '<p>No posts yet. Create your first post!</p>' +
                                    '</div>'
                                );
                            }
                        }, 300);
                    }, 1200);
                } else {
                    // Reset button state
                    $button.prop('disabled', false).text('Delete');
                    
                    // Show error in modal
                    $('#deleteModal .modal-body').html(
                        '<div class="text-center mb-3">' +
                        '<i class="bi bi-x-circle text-danger" style="font-size: 48px;"></i>' +
                        '</div>' +
                        '<p class="text-center">Error: ' + response.message + '</p>' +
                        '<p class="text-center text-muted small">Please try again.</p>'
                    );
                }
            },
            error: function(xhr) {
                // Reset button state
                $button.prop('disabled', false).text('Delete');
                
                // Show error in modal
                $('#deleteModal .modal-body').html(
                    '<div class="text-center mb-3">' +
                    '<i class="bi bi-x-circle text-danger" style="font-size: 48px;"></i>' +
                    '</div>' +
                    '<p class="text-center">An error occurred. Status: ' + xhr.status + '</p>' +
                    '<p class="text-center text-muted small">Please try again.</p>'
                );
            }
        });
    });
    
    // Add animations for keyframe definitions
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
            @keyframes scaleIn {
                0% { transform: scale(0); opacity: 0; }
                50% { transform: scale(1.2); opacity: 1; }
                100% { transform: scale(1); opacity: 1; }
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        `)
        .appendTo('head');
});
</script>