<div class="blog-feed-section">
    <div class="section-header">
        <h2>Blog Feed</h2>
    </div>
    
    <div class="feed-container">
        <!-- Main Feed Content -->
        <div class="main-feed">
            <?php if (empty($posts)): ?>
                <div class="empty-state">
                    <i class="bi bi-journal-text"></i>
                    <p>No published posts yet. Follow more users or create your own posts!</p>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="feed-post-item" data-post-id="<?= $post['post_id'] ?>">
                        <!-- Post Header with User Info -->
                        <div class="feed-post-header">
                            <div class="feed-post-user">
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
                        </div>
                        
                        <!-- Post Content -->
                        <div class="feed-post-content">
                            <h3 class="feed-post-title">
                                <?= htmlspecialchars($post['title']) ?>
                            </h3>
                            
                            <?php if (!empty($post['category'])): ?>
                                <div class="feed-post-category">
                                    <span class="category-badge">
                                        <i class="bi bi-tag"></i> <?= htmlspecialchars($post['category']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($post['image'])): ?>
                                <div class="feed-post-image-container">
                                    <img src="<?= base_url('uploads/posts/' . $post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="feed-post-image">
                                </div>
                            <?php endif; ?>
                            
                            <div class="feed-post-description">
                                <?php 
                                // Limit description length to 300 characters
                                $description = $post['description'];
                                if (strlen($description) > 300) {
                                    $description = substr($description, 0, 300) . '...';
                                }
                                echo nl2br(htmlspecialchars($description));
                                ?>
                            </div>
                            
                            <?php if (strlen($post['description']) > 300): ?>
                                <div class="read-more-container">
                                    <a href="<?= site_url('posts/view/' . $post['post_id']) ?>" class="read-more-link">
                                        Read More <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Post Actions -->
                        <div class="feed-post-actions">
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
                        
                        <!-- Comments Section (Initially Hidden) -->
                        <div class="feed-post-comments" id="comments-section-<?= $post['post_id'] ?>" style="display: none;">
                            <div class="comments-container" id="comments-container-<?= $post['post_id'] ?>">
                                <?php if (empty($post['comments'])): ?>
                                    <div class="no-comments-message">
                                        <p>No comments yet. Be the first to comment!</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($post['comments'] as $comment): ?>
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
                                    
                                    <?php if ($post['comment_count'] > count($post['comments'])): ?>
                                        <div class="load-more-comments">
                                            <button class="load-comments-btn" data-post-id="<?= $post['post_id'] ?>" data-offset="<?= count($post['comments']) ?>">
                                                Load More Comments <i class="bi bi-arrow-down"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="add-comment-form">
                                <form id="comment-form-<?= $post['post_id'] ?>">
                                    <div class="form-group">
                                        <textarea class="form-control comment-textarea" placeholder="Write a comment..." rows="2" name="comment_text" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm submit-comment-btn">
                                        Post Comment
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar Content -->
        <div class="feed-sidebar">
            <!-- Trending Posts Section -->
            <div class="trending-section">
                <h3 class="sidebar-title">Trending Posts</h3>
                <div class="trending-item">
                    <div class="trending-number">01</div>
                    <div class="trending-title">How to improve your photography skills</div>
                </div>
                <div class="trending-item">
                    <div class="trending-number">02</div>
                    <div class="trending-title">Top destinations for summer vacation</div>
                </div>
                <div class="trending-item">
                    <div class="trending-number">03</div>
                    <div class="trending-title">Quick and healthy breakfast ideas</div>
                </div>
                <div class="trending-item">
                    <div class="trending-number">04</div>
                    <div class="trending-title">Latest tech gadgets worth buying</div>
                </div>
            </div>
            
            <!-- Categories Section -->
            <div class="categories-section">
                <h3 class="sidebar-title">Categories</h3>
                <div class="category-cloud">
                    <div class="category-tag">Travel</div>
                    <div class="category-tag">Food</div>
                    <div class="category-tag">Technology</div>
                    <div class="category-tag">Health</div>
                    <div class="category-tag">Lifestyle</div>
                    <div class="category-tag">Sports</div>
                    <div class="category-tag">Business</div>
                </div>
            </div>
            
            <!-- Recent Activity Section -->
            <div class="activity-section">
                <h3 class="sidebar-title">Recent Activity</h3>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <div class="activity-content">
                        <div>John liked your post "Photography Tips"</div>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-chat"></i>
                    </div>
                    <div class="activity-content">
                        <div>Sarah commented on "Travel Guide"</div>
                        <div class="activity-time">5 hours ago</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <div class="activity-content">
                        <div>Michael started following you</div>
                        <div class="activity-time">Yesterday</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Zoom Modal -->
<div id="imageModal" class="image-modal">
    <span class="close-modal">&times;</span>
    <div class="modal-content">
        <img id="zoomedImage" class="zoomed-image">
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
    
    // Show/Hide Comments
    $('.comment-btn').on('click', function() {
        var postId = $(this).data('post-id');
        $('#comments-section-' + postId).slideToggle();
    });
    
    // Submit Comment
    $(document).on('submit', '[id^=comment-form-]', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var postId = form.attr('id').replace('comment-form-', '');
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
                    var commentCount = parseInt($('.comment-btn[data-post-id="' + postId + '"] .comment-count').text()) + 1;
                    $('.comment-btn[data-post-id="' + postId + '"] .comment-count').text(commentCount);
                    
                    // Remove no comments message if it exists
                    $('#comments-container-' + postId + ' .no-comments-message').remove();
                    
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
                    
                    $('#comments-container-' + postId).prepend(commentHtml);
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
    
    // Image zoom functionality
    $('.feed-post-image').on('click', function() {
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
    
    // Make category tags clickable
    $('.category-tag').on('click', function() {
        var category = $(this).text();
        // You can implement category filtering here in the future
        alert('Filter by category: ' + category);
    });
    
    // Add animation to posts when they appear in viewport
    function revealOnScroll() {
        var windowHeight = $(window).height();
        var scrollTop = $(window).scrollTop();
        
        $('.feed-post-item').each(function() {
            var elementTop = $(this).offset().top;
            
            if (elementTop < (scrollTop + windowHeight - 100)) {
                $(this).addClass('visible');
            }
        });
    }
    
    // Run on page load
    revealOnScroll();
    
    // Run on scroll
    $(window).on('scroll', revealOnScroll);
});
</script>
<!-- Add this at the bottom of your feed_page.php file, just before the closing </div> or </body> tag -->
<script>
$(document).ready(function() {
    // Function to apply dark mode specifically to sidebar elements
    function applySidebarDarkMode() {
        if ($('body').hasClass('dark-mode')) {
            // Sidebar titles
            $('.sidebar-title, .trending-section h3, .categories-section h3, .activity-section h3').css('color', '#e4e6eb');
            
            // Trending posts titles and numbers
            $('.trending-title').css('color', '#e4e6eb');
            $('.trending-number').css('color', '#dc2626');
            
            // Category tags
            $('.category-tag').css({
                'background-color': '#333333',
                'color': '#e4e6eb'
            });
            
            // Activity content and time
            $('.activity-content div').css('color', '#e4e6eb');
            $('.activity-time').css('color', '#b0b3b8');
            
            // Recent Activity title
            $('.feed-sidebar h3').css('color', '#e4e6eb');
            
            // Categories title
            $('.categories-section h3').css('color', '#e4e6eb');
            
            // Feed sidebar background
            $('.feed-sidebar').css({
                'background-color': '#1e1e1e',
                'border-color': '#2c2c2c'
            });
        } else {
            // Reset to light mode colors
            $('.sidebar-title, .trending-section h3, .categories-section h3, .activity-section h3').css('color', '');
            $('.trending-title').css('color', '');
            $('.trending-number').css('color', '');
            $('.category-tag').css({
                'background-color': '',
                'color': ''
            });
            $('.activity-content div').css('color', '');
            $('.activity-time').css('color', '');
            $('.feed-sidebar h3').css('color', '');
            $('.categories-section h3').css('color', '');
            $('.feed-sidebar').css({
                'background-color': '',
                'border-color': ''
            });
        }
    }
    
    // Apply immediately on page load
    applySidebarDarkMode();
    
    // Listen for dark mode toggle events
    $(document).on('themeChanged', function() {
        applySidebarDarkMode();
    });
    
    // Also watch for body class changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                applySidebarDarkMode();
            }
        });
    });
    
    observer.observe(document.body, { attributes: true });
    
    // Add click listener to the theme toggle button to ensure our function runs
    $('.theme-toggle-btn, button[aria-label="Theme Toggle"], button[aria-label="Toggle dark mode"]').on('click', function() {
        // Wait a small amount of time for the dark-mode class to be applied
        setTimeout(applySidebarDarkMode, 50);
    });
});
</script>