<div class="blog-post-section">
    <div class="section-header">
        <a href="<?= site_url('feed') ?>" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Feed
        </a>
    </div>
    
    <div class="single-post-container">
        <div class="single-post-item">
            <!-- Post Header with User Info -->
            <div class="single-post-header">
                <div class="post-user">
                    <div class="user-avatar">
                        <?php if (!empty($post['profile_photo'])): ?>
                            <img src="<?= base_url('uploads/profiles/' . $post['profile_photo']) ?>" alt="<?= htmlspecialchars($post['username']) ?>">
                        <?php else: ?>
                            <i class="bi bi-person-fill"></i>
                        <?php endif; ?>
                    </div>
                    <div class="user-info">
                        <div class="username"><?= htmlspecialchars($post['username']) ?></div>
                        <div class="post-date">
                            <i class="bi bi-calendar3"></i> 
                            <?= date('M d, Y', strtotime($post['date_created'])) ?>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($post['category'])): ?>
                    <div class="post-category">
                        <span class="category-badge">
                            <i class="bi bi-tag"></i> <?= htmlspecialchars($post['category']) ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Post Content -->
            <div class="single-post-content">
                <h1 class="single-post-title">
                    <?= htmlspecialchars($post['title']) ?>
                </h1>
                
                <?php if (!empty($post['image'])): ?>
                <div class="single-post-image-container">
                    <img src="<?= base_url('uploads/posts/' . $post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="single-post-image">
                </div>
            <?php endif; ?>
                
                <div class="single-post-description">
                    <?= nl2br(htmlspecialchars($post['description'])) ?>
                </div>
            </div>
            
            <!-- Post Actions -->
            <div class="single-post-actions">
                <div class="action-buttons">
                    <button class="action-btn like-btn <?= $post['user_liked'] ? 'liked' : '' ?>" data-post-id="<?= $post['post_id'] ?>">
                        <i class="bi <?= $post['user_liked'] ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                        <span class="like-count"><?= $post['like_count'] ?></span>
                    </button>
                    <button class="action-btn comment-btn" data-post-id="<?= $post['post_id'] ?>">
                        <i class="bi bi-chat"></i>
                        <span class="comment-count"><?= $post['comment_count'] ?></span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Comments Section -->
        <div class="comments-section">
            <h3 class="comments-title">Comments (<?= $post['comment_count'] ?>)</h3>
            
            <div class="add-comment-form">
                <form id="comment-form">
                    <div class="form-group">
                        <textarea class="form-control comment-textarea" placeholder="Write a comment..." rows="3" name="comment_text" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary submit-comment-btn">
                        Post Comment
                    </button>
                </form>
            </div>
            
            <div class="comments-container" id="comments-container">
                <?php if (empty($comments)): ?>
                    <div class="no-comments-message">
                        <p>No comments yet. Be the first to comment!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-item">
                            <div class="comment-user">
                                <div class="user-avatar small">
                                    <?php if (!empty($comment['profile_photo'])): ?>
                                        <img src="<?= base_url('uploads/profiles/' . $comment['profile_photo']) ?>" alt="<?= htmlspecialchars($comment['username']) ?>">
                                    <?php else: ?>
                                        <i class="bi bi-person-fill"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="comment-user-info">
                                    <div class="comment-username"><?= htmlspecialchars($comment['username']) ?></div>
                                    <div class="comment-date">
                                        <?= date('M d, Y', strtotime($comment['date_commented'])) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="comment-content">
                                <?= nl2br(htmlspecialchars($comment['comment_text'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if ($post['comment_count'] > count($comments)): ?>
                        <div class="load-more-comments">
                            <button class="load-comments-btn" data-post-id="<?= $post['post_id'] ?>" data-offset="<?= count($comments) ?>">
                                Load More Comments <i class="bi bi-arrow-down"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Animation definitions
    if (!$('style#custom-animations').length) {
        $('head').append(`
            <style id="custom-animations">
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                
                @keyframes heartBeat {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.3); }
                    100% { transform: scale(1); }
                }
                
                .single-post-container {
                    animation: fadeIn 0.5s ease-out;
                }
                
                .comment-item {
                    animation: fadeIn 0.5s ease-out;
                }
                
                .single-post-item {
                    position: relative;
                    padding-left: 20px;
                }
                
                .single-post-item::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 4px;
                    height: 100%;
                    background-color: #dc2626;
                    border-radius: 0 0 4px 0;
                }
                
                .blog-post-section {
                    padding: 30px;
                    max-width: 1000px;
                    margin: 0 auto;
                    background: #f8f9fa;
                    min-height: 100vh;
                }
                
                .single-post-container {
                    background: white;
                    border-radius: 12px;
                    padding: 30px;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
                    margin-bottom: 30px;
                    overflow: hidden;
                }
                
                .section-header .back-link {
                    display: inline-flex;
                    align-items: center;
                    color: #4b5563;
                    text-decoration: none;
                    font-weight: 500;
                    transition: all 0.3s ease;
                    padding: 8px 15px;
                    background-color: #f9fafb;
                    border-radius: 8px;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                }
                
                .section-header .back-link:hover {
                    color: #dc2626;
                    transform: translateX(-5px);
                    background-color: #f3f4f6;
                }
                
                .single-post-title {
                    font-size: 2rem;
                    font-weight: 700;
                    margin-bottom: 20px;
                    color: #111827;
                    line-height: 1.3;
                }
                
                .single-post-image-container {
                    margin: 25px 0;
                    border-radius: 12px;
                    overflow: hidden;
                    position: relative;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                    text-align: center;
                }
                
                .single-post-image {
                    max-width: 100%;
                    height: auto;
                    max-height: 600px;
                    object-fit: contain;
                    display: block;
                    margin: 0 auto;
                    transition: transform 0.5s ease;
                    cursor: zoom-in;
                }
                
                .comments-section {
                    background: white;
                    border-radius: 12px;
                    padding: 30px;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
                }
                
                .comments-title {
                    font-size: 1.3rem;
                    font-weight: 600;
                    margin-bottom: 25px;
                    color: #111827;
                    border-bottom: 1px solid #f3f4f6;
                    padding-bottom: 15px;
                }
                
                .comments-container {
                    margin-bottom: 30px;
                }
                
                .comment-item {
                    background-color: #f9fafb;
                    border-radius: 12px;
                    padding: 20px;
                    margin-bottom: 20px;
                    border-left: 3px solid #dc2626;
                    transition: all 0.3s ease;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                }
                
                .comment-item:hover {
                    background-color: #f3f4f6;
                    transform: translateX(5px);
                }
                
                .add-comment-form {
                    margin-top: 30px;
                    border-top: 1px solid #f3f4f6;
                    padding-top: 25px;
                }
                
                .comment-textarea {
                    border-radius: 12px;
                    border: 1px solid #e5e7eb;
                    padding: 15px;
                    font-size: 1rem;
                    width: 100%;
                    transition: all 0.3s ease;
                    min-height: 100px;
                    background-color: #f9fafb;
                    resize: vertical;
                }
                
                .comment-textarea:focus {
                    border-color: #dc2626;
                    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
                    outline: none;
                    background-color: #fff;
                }
                
                .submit-comment-btn {
                    margin-top: 15px;
                    background-color: #dc2626;
                    color: white;
                    border: none;
                    border-radius: 8px;
                    padding: 10px 25px;
                    font-weight: 500;
                    transition: all 0.3s ease;
                    font-size: 0.95rem;
                }
                
                .submit-comment-btn:hover {
                    background-color: #b91c1c;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(220, 38, 38, 0.2);
                }
                
                .image-modal {
                    display: none;
                    position: fixed;
                    z-index: 9999;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.9);
                    overflow: hidden;
                }
                
                .modal-content {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100%;
                }
                
                .zoomed-image {
                    max-width: 90%;
                    max-height: 90%;
                    object-fit: contain;
                    border-radius: 4px;
                    animation: zoomIn 0.3s ease-out;
                }
                
                @keyframes zoomIn {
                    from { transform: scale(0.8); opacity: 0; }
                    to { transform: scale(1); opacity: 1; }
                }
                
                .close-modal {
                    position: absolute;
                    top: 20px;
                    right: 20px;
                    color: white;
                    font-size: 40px;
                    font-weight: bold;
                    cursor: pointer;
                    z-index: 10000;
                    transition: all 0.3s ease;
                }
                
                .close-modal:hover {
                    color: #dc2626;
                    transform: scale(1.1);
                }
                
                @media (max-width: 768px) {
                    .blog-post-section {
                        padding: 15px;
                    }
                    
                    .single-post-container, 
                    .comments-section {
                        padding: 20px;
                    }
                    
                    .single-post-header {
                        flex-direction: column;
                        align-items: flex-start;
                    }
                    
                    .post-category {
                        margin-left: 0;
                        margin-top: 10px;
                    }
                    
                    .single-post-title {
                        font-size: 1.5rem;
                    }
                }
            </style>
        `);
    }

    // Rearrange the DOM to ensure comments container is above the form if not already
    if ($('.add-comment-form').prevAll('.comments-container').length === 0) {
        var commentsContainer = $('.comments-container');
        var commentForm = $('.add-comment-form');
        // Only move if needed
        if (commentsContainer.length && commentForm.length) {
            commentsContainer.insertBefore(commentForm);
        }
    }

    // Like/Unlike Post
    $('.like-btn').off('click').on('click', function() {
        var postId = $(this).data('post-id');
        var likeBtn = $(this);
        
        $.ajax({
            url: '<?= site_url('posts/toggle_like') ?>',
            type: 'POST',
            data: {
                post_id: postId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.liked) {
                        likeBtn.addClass('liked');
                        likeBtn.find('i').removeClass('bi-heart').addClass('bi-heart-fill');
                        
                        // Add heart animation
                        likeBtn.find('i').css('animation', 'heartBeat 0.3s ease-in-out');
                        setTimeout(() => {
                            likeBtn.find('i').css('animation', '');
                        }, 300);
                    } else {
                        likeBtn.removeClass('liked');
                        likeBtn.find('i').removeClass('bi-heart-fill').addClass('bi-heart');
                    }
                    
                    likeBtn.find('.like-count').text(response.like_count);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error toggling like:', error);
                // Show error toast
                showToast('Error: Could not process your like. Please try again.', 'error');
            }
        });
    });
    
    // Submit Comment
    $('#comment-form').off('submit').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var postId = form.data('post-id') || <?= isset($post['post_id']) ? $post['post_id'] : 0 ?>;
        var commentText = form.find('textarea[name="comment_text"]').val();
        
        if (!commentText.trim()) {
            return;
        }
        
        // Disable submit button to prevent double submission
        var submitBtn = form.find('.submit-comment-btn');
        var originalBtnText = submitBtn.text();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Posting...');
        
        $.ajax({
            url: '<?= site_url('posts/add_comment') ?>',
            type: 'POST',
            data: {
                post_id: postId,
                comment_text: commentText
            },
            dataType: 'json',
            success: function(response) {
                // Re-enable button
                submitBtn.prop('disabled', false).text(originalBtnText);
                
                if (response.success) {
                    // Clear the form
                    form.find('textarea[name="comment_text"]').val('');
                    form.find('textarea[name="comment_text"]').css('height', '80px');
                    
                    // Update comment count in multiple places
                    var commentCount = parseInt($('.comment-count').text()) + 1;
                    $('.comment-count').text(commentCount);
                    $('.comments-title').text('Comments (' + commentCount + ')');
                    
                    // Remove no comments message if it exists
                    $('#comments-container .no-comments-message').remove();
                    
                    // Add the new comment to the TOP of the comments container with animation
                    var commentHtml = `
                        <div class="comment-item" style="animation: fadeIn 0.5s ease-out;">
                            <div class="comment-user">
                                <div class="user-avatar small">
                                    ${response.comment.profile_photo ? 
                                        `<img src="<?= base_url('uploads/profiles/') ?>${response.comment.profile_photo}" alt="${response.comment.username}">` : 
                                        '<i class="bi bi-person-fill"></i>'}
                                </div>
                                <div class="comment-user-info">
                                    <div class="comment-username">${response.comment.username}</div>
                                    <div class="comment-date">
                                        ${response.comment.formatted_date}
                                    </div>
                                </div>
                            </div>
                            <div class="comment-content">
                                ${response.comment.comment_text.replace(/\n/g, '<br>')}
                            </div>
                        </div>
                    `;
                    
                    // Prepend to put newest comment at top
                    $('#comments-container').prepend(commentHtml);
                    
                    // Show success toast
                    showToast('Comment posted successfully!', 'success');
                    
                    // Scroll to the new comment
                    $('html, body').animate({
                        scrollTop: $('#comments-container').offset().top - 100
                    }, 500);
                } else {
                    // Show error message
                    showToast('Error: ' + (response.message || 'Could not post comment'), 'error');
                }
            },
            error: function(xhr, status, error) {
                // Re-enable button
                submitBtn.prop('disabled', false).text(originalBtnText);
                console.error('Error adding comment:', error);
                showToast('Error: Could not post comment. Please try again.', 'error');
            }
        });
    });
    
    // Load More Comments
    $(document).on('click', '.load-comments-btn', function() {
        var postId = $(this).data('post-id');
        var offset = $(this).data('offset');
        var loadMoreBtn = $(this);
        
        // Show loading state
        loadMoreBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
        
        $.ajax({
            url: '<?= site_url('posts/load_comments') ?>',
            type: 'POST',
            data: {
                post_id: postId,
                offset: offset
            },
            dataType: 'json',
            success: function(response) {
                // Reset button
                loadMoreBtn.prop('disabled', false).html('Load More Comments <i class="bi bi-arrow-down"></i>');
                
                if (response.success) {
                    if (response.comments.length > 0) {
                        // Add the new comments
                        $.each(response.comments, function(index, comment) {
                            var commentHtml = `
                                <div class="comment-item" style="animation: fadeIn 0.5s ease-out;">
                                    <div class="comment-user">
                                        <div class="user-avatar small">
                                            ${comment.profile_photo ? 
                                                `<img src="<?= base_url('uploads/profiles/') ?>${comment.profile_photo}" alt="${comment.username}">` : 
                                                '<i class="bi bi-person-fill"></i>'}
                                        </div>
                                        <div class="comment-user-info">
                                            <div class="comment-username">${comment.username}</div>
                                            <div class="comment-date">
                                                ${comment.formatted_date}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="comment-content">
                                        ${comment.comment_text.replace(/\n/g, '<br>')}
                                    </div>
                                </div>
                            `;
                            
                            // Add before the load more button container
                            $('.load-more-comments').before(commentHtml);
                        });
                        
                        // Update the offset for next load
                        loadMoreBtn.data('offset', offset + response.comments.length);
                        
                        // Hide load more button if no more comments
                        if (response.comments.length < 5) {
                            loadMoreBtn.parent().hide();
                        }
                    } else {
                        // No more comments to load
                        loadMoreBtn.parent().hide();
                        showToast('No more comments to load', 'info');
                    }
                } else {
                    showToast('Error: ' + (response.message || 'Could not load comments'), 'error');
                }
            },
            error: function(xhr, status, error) {
                // Reset button
                loadMoreBtn.prop('disabled', false).html('Load More Comments <i class="bi bi-arrow-down"></i>');
                console.error('Error loading comments:', error);
                showToast('Error loading comments. Please try again.', 'error');
            }
        });
    });
    
    // Image zoom functionality
    // Create modal elements if they don't exist
    if ($('#imageModal').length === 0) {
        $('body').append(`
            <div id="imageModal" class="image-modal">
                <span class="close-modal">&times;</span>
                <div class="modal-content">
                    <img id="zoomedImage" class="zoomed-image">
                </div>
            </div>
        `);
    }
    
    // Click handler for post images
    $(document).on('click', '.feed-post-image, .single-post-image', function() {
        var imgSrc = $(this).attr('src');
        $('#zoomedImage').attr('src', imgSrc);
        $('#imageModal').fadeIn(300);
        $('body').css('overflow', 'hidden'); // Prevent scrolling while modal is open
    });
    
    // Close modal when clicking X or anywhere on the modal
    $('.close-modal, #imageModal').on('click', function(e) {
        if (e.target !== $('#zoomedImage')[0]) {
            $('#imageModal').fadeOut(300);
            $('body').css('overflow', 'auto'); // Re-enable scrolling
        }
    });
    
    // Prevent close when clicking on the image itself
    $(document).on('click', '#zoomedImage', function(e) {
        e.stopPropagation();
    });
    
    // Close modal on escape key
    $(document).keydown(function(e) {
        if (e.keyCode === 27) { // ESC key
            $('#imageModal').fadeOut(300);
            $('body').css('overflow', 'auto');
        }
    });
    
    // Comment textarea animations
    $(document).on('focus', '.comment-textarea', function() {
        $(this).animate({
            height: '120px'
        }, 300);
    });
    
    $(document).on('blur', '.comment-textarea', function() {
        if ($(this).val().trim() === '') {
            $(this).animate({
                height: '80px'
            }, 300);
        }
    });
    
    // Helper function to show toast notifications
    function showToast(message, type = 'info') {
        // Remove existing toasts
        $('.toast-notification').remove();
        
        // Create toast container if it doesn't exist
        if ($('#toast-container').length === 0) {
            $('body').append('<div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>');
        }
        
        // Set toast color based on type
        let bgColor, textColor, borderColor;
        switch(type) {
            case 'success':
                bgColor = '#dcf5e7';
                textColor = '#0d6832';
                borderColor = '#0d6832';
                icon = '<i class="bi bi-check-circle-fill"></i>';
                break;
            case 'error':
                bgColor = '#fde8e8';
                textColor = '#b91c1c';
                borderColor = '#b91c1c';
                icon = '<i class="bi bi-x-circle-fill"></i>';
                break;
            case 'warning':
                bgColor = '#fef3c7';
                textColor = '#92400e';
                borderColor = '#92400e';
                icon = '<i class="bi bi-exclamation-triangle-fill"></i>';
                break;
            default: // info
                bgColor = '#e1f5fe';
                textColor = '#0369a1';
                borderColor = '#0369a1';
                icon = '<i class="bi bi-info-circle-fill"></i>';
        }
        
        // Create toast element
        let toast = $(`
            <div class="toast-notification" style="
                background-color: ${bgColor};
                color: ${textColor};
                border-left: 4px solid ${borderColor};
                padding: 12px 20px;
                border-radius: 4px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                margin-bottom: 10px;
                max-width: 350px;
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 14px;
                transform: translateX(400px);
                opacity: 0;
                transition: all 0.3s ease;
            ">
                ${icon}
                <div>${message}</div>
                <button type="button" style="
                    margin-left: auto;
                    background: none;
                    border: none;
                    font-size: 18px;
                    line-height: 1;
                    cursor: pointer;
                    color: ${textColor};
                    opacity: 0.7;
                ">×</button>
            </div>
        `);
        
        // Add toast to container
        $('#toast-container').append(toast);
        
        // Animate toast in
        setTimeout(() => {
            toast.css({
                'transform': 'translateX(0)',
                'opacity': '1'
            });
        }, 10);
        
        // Close button functionality
        toast.find('button').on('click', function() {
            closeToast(toast);
        });
        
        // Auto close after 5 seconds
        setTimeout(() => {
            closeToast(toast);
        }, 5000);
        
        function closeToast(toast) {
            toast.css({
                'transform': 'translateX(400px)',
                'opacity': '0'
            });
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }
    
    // Make featured posts and items in carousel clickable
    $('.featured-post-item, .carousel-item').css('cursor', 'pointer');
    
    // Add click handler to carousel items
    $(document).on('click', '.carousel-item', function(e) {
        // Ignore clicks on carousel controls and buttons
        if ($(e.target).closest('.carousel-control-prev, .carousel-control-next, .carousel-indicators, button, .featured-post-stats').length === 0) {
            const postId = $(this).data('post-id');
            if (postId) {
                window.location.href = '<?= site_url("posts/view") ?>/' + postId;
            }
        }
    });
    
    // Add click handler specifically for featured post titles and images
    $(document).on('click', '.featured-post-title, .featured-post-image', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const postId = $(this).closest('.carousel-item').data('post-id') || 
                      $(this).closest('.featured-post-item').data('post-id');
        
        if (postId) {
            window.location.href = '<?= site_url("posts/view") ?>/' + postId;
        }
    });
    
    // Make feed post items clickable too
    $(document).on('click', '.feed-post-item', function(e) {
        // Don't navigate if clicking action buttons or links
        if (!$(e.target).closest('.action-btn, .action-buttons, button, a, textarea').length) {
            const postId = $(this).data('post-id');
            if (postId) {
                window.location.href = '<?= site_url("posts/view") ?>/' + postId;
            }
        }
    });
    
    // Fix for dark mode
    function applyDarkModeToCustomElements() {
        if ($('body').hasClass('dark-mode')) {
            $('.single-post-container, .comments-section').css({
                'background-color': '#1e1e1e',
                'color': '#e4e6eb'
            });
            
            $('.section-header .back-link').css({
                'background-color': '#333333',
                'color': '#e4e6eb'
            });
            
            $('.single-post-title, .comments-title, .comment-username').css('color', '#e4e6eb');
            $('.single-post-description, .comment-content').css('color', '#b0b3b8');
            $('.category-badge').css({
                'background-color': '#333333',
                'color': '#e4e6eb'
            });
            
            $('.comment-item').css('background-color', '#2c2c2c');
            $('.no-comments-message').css({
                'background-color': '#2c2c2c',
                'border-color': '#3a3a3a',
                'color': '#b0b3b8'
            });
            
            $('.comment-textarea').css({
                'background-color': '#333333',
                'border-color': '#4b4b4b',
                'color': '#e4e6eb'
            });
            
            $('.post-date, .comment-date').css('color', '#b0b3b8');
        } else {
            $('.single-post-container, .comments-section').css({
                'background-color': '',
                'color': ''
            });
            
            $('.section-header .back-link').css({
                'background-color': '',
                'color': ''
            });
            
            $('.single-post-title, .comments-title, .comment-username').css('color', '');
            $('.single-post-description, .comment-content').css('color', '');
            $('.category-badge').css({
                'background-color': '',
                'color': ''
            });
            
            $('.comment-item').css('background-color', '');
            $('.no-comments-message').css({
                'background-color': '',
                'border-color': '',
                'color': ''
            });
            
            $('.comment-textarea').css({
                'background-color': '',
                'border-color': '',
                'color': ''
            });
            
            $('.post-date, .comment-date').css('color', '');
        }
    }
    
    // Apply dark mode initially
    applyDarkModeToCustomElements();
    
    // Listen for theme toggle events
    $(document).on('themeChanged', function() {
        applyDarkModeToCustomElements();
    });
    
    // Watch for body class changes for dark mode
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                applyDarkModeToCustomElements();
            }
        });
    });
    
    observer.observe(document.body, { attributes: true });
    
    // Add click listener to theme toggle
    $('.theme-toggle-btn, button[aria-label="Theme Toggle"], button[aria-label="Toggle dark mode"]').on('click', function() {
        setTimeout(applyDarkModeToCustomElements, 50);
    });
});
</script>