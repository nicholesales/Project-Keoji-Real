<div class="blog-posts-section">
    <div class="section-header">
        <h2>Comments on My Posts</h2>
    </div>
    
    <div class="comments-container posts-container">
        <?php if (empty($comments)): ?>
            <div class="empty-state">
                <i class="bi bi-chat-dots"></i>
                <p>No comments on your posts yet.</p>
            </div>
        <?php else: ?>
            <p class="mb-3">Your posts have received <?= $comment_count ?> comment<?= $comment_count != 1 ? 's' : '' ?>.</p>
            
            <?php foreach ($comments as $comment): ?>
                <div class="received-comment-item" data-comment-id="<?= $comment['comment_id'] ?>">
    <a href="<?= site_url('posts/view/' . $comment['post_id']) ?>" class="comment-link">
        <div class="comment-post-info">
            <div class="post-title">
                <?= htmlspecialchars($comment['post_title']) ?>
                <i class="bi bi-arrow-right-circle"></i>
            </div>
        </div>
        
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
                    <i class="bi bi-calendar3"></i> <?= date('M d, Y', strtotime($comment['date_commented'])) ?>
                </div>
            </div>
        </div>
        
        <div class="comment-content">
            <?= nl2br(htmlspecialchars($comment['comment_text'])) ?>
        </div>
    </a>
    
    <div class="comment-meta">
        <div class="view-post-hint">View Post</div>
        
        <div class="comment-actions">
            <?php if ($comment['user_id'] == $this->session->userdata('user_id')): ?>
                <button class="action-btn edit edit-received-comment" data-id="<?= $comment['comment_id'] ?>" title="Edit Comment">
                    <i class="bi bi-pencil"></i>
                </button>
            <?php endif; ?>
            <button class="action-btn delete delete-received-comment" data-id="<?= $comment['comment_id'] ?>" title="Delete Comment">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>
</div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    // Add hover effects to delete button
    $('.delete-received-comment').hover(
        function() {
            $(this).find('i').removeClass('bi-trash').addClass('bi-trash-fill');
        },
        function() {
            $(this).find('i').removeClass('bi-trash-fill').addClass('bi-trash');
        }
    );
    
    // Delete Comment on Received Comments page
    $('.delete-received-comment').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent the click from propagating to the parent link
        
        var commentId = $(this).data('id');
        var commentElement = $(this).closest('.received-comment-item');
        
        if (confirm('Are you sure you want to delete this comment?')) {
            $.ajax({
                url: '<?= site_url('comments/delete/') ?>' + commentId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);
                    
                    if (response.success) {
                        commentElement.fadeOut(300, function() {
                            $(this).remove();
                            
                            // Update comment count
                            var count = $('.received-comment-item').length;
                            var countText = count + ' comment' + (count != 1 ? 's' : '');
                            $('.comments-container > p').text('Your posts have received ' + countText + '.');
                            
                            // Show empty state if no comments left
                            if (count === 0) {
                                $('.comments-container').html(
                                    '<div class="empty-state">' +
                                    '<i class="bi bi-chat-dots"></i>' +
                                    '<p>No comments on your posts yet.</p>' +
                                    '</div>'
                                );
                            }
                        });
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                    console.log('Status:', status);
                    console.log('Response:', xhr.responseText);
                    alert('An error occurred: ' + error);
                }
            });
        }
    });
});
</script>