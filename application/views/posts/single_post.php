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
    // Like/Unlike Post
    $('.like-btn').on('click', function() {
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
                    } else {
                        likeBtn.removeClass('liked');
                        likeBtn.find('i').removeClass('bi-heart-fill').addClass('bi-heart');
                    }
                    
                    likeBtn.find('.like-count').text(response.like_count);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error toggling like:', error);
            }
        });
    });
    
    // Submit Comment
    $('#comment-form').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var postId = <?= $post['post_id'] ?>;
        var commentText = form.find('textarea[name="comment_text"]').val();
        
        if (!commentText.trim()) {
            return;
        }
        
        $.ajax({
            url: '<?= site_url('posts/add_comment') ?>',
            type: 'POST',
            data: {
                post_id: postId,
                comment_text: commentText
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Clear the form
                    form.find('textarea[name="comment_text"]').val('');
                    
                    // Update comment count
                    var commentCount = parseInt($('.comment-count').text()) + 1;
                    $('.comment-count').text(commentCount);
                    $('.comments-title').text('Comments (' + commentCount + ')');
                    
                    // Remove no comments message if it exists
                    $('#comments-container .no-comments-message').remove();
                    
                    // Add the new comment to the comments container
                    var commentHtml = `
                        <div class="comment-item">
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
                    
                    $('#comments-container').prepend(commentHtml);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error adding comment:', error);
            }
        });
    });
    
    // Load More Comments
    $(document).on('click', '.load-comments-btn', function() {
        var postId = $(this).data('post-id');
        var offset = $(this).data('offset');
        var loadMoreBtn = $(this);
        
        $.ajax({
            url: '<?= site_url('posts/load_comments') ?>',
            type: 'POST',
            data: {
                post_id: postId,
                offset: offset
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.comments.length > 0) {
                        // Add the new comments
                        $.each(response.comments, function(index, comment) {
                            var commentHtml = `
                                <div class="comment-item">
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
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading comments:', error);
            }
        });
    });
});

// Image zoom functionality
$(document).ready(function() {
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
    $('.feed-post-image, .single-post-image').on('click', function() {
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
    $('#zoomedImage').on('click', function(e) {
        e.stopPropagation();
    });
    
    // Close modal on escape key
    $(document).keydown(function(e) {
        if (e.keyCode === 27) { // ESC key
            $('#imageModal').fadeOut(300);
            $('body').css('overflow', 'auto');
        }
    });
});
</script>