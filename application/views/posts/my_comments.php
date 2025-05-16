<div class="blog-posts-section">
    <div class="section-header">
        <h2>My Comments</h2>
    </div>
    
    <div class="comments-container posts-container">  <!-- Added posts-container class for consistent styling -->
        <?php if (empty($comments)): ?>
            <div class="empty-state">
                <i class="bi bi-chat-dots"></i>
                <p>You haven't made any comments yet.</p>
            </div>
        <?php else: ?>
            <p class="mb-3">You have made <?= $comment_count ?> comment<?= $comment_count != 1 ? 's' : '' ?>.</p>
            
            <?php foreach ($comments as $comment): ?>
                <div class="user-comment-item" data-comment-id="<?= $comment['comment_id'] ?>">
                    <div class="comment-post-info">
                        <div class="post-title">
                            <a href="<?= site_url('posts/view/' . $comment['post_id']) ?>">
                                <?= htmlspecialchars($comment['post_title']) ?>
                            </a>
                        </div>
                        <div class="post-author">
                            <span>Posted by: <?= htmlspecialchars($comment['username']) ?></span>
                        </div>
                    </div>
                    
                    <div class="comment-content">
                        <?= nl2br(htmlspecialchars($comment['comment_text'])) ?>
                    </div>
                    
                    <div class="comment-meta">
                        <div class="comment-date">
                            <i class="bi bi-calendar3"></i> <?= date('M d, Y', strtotime($comment['date_commented'])) ?>
                        </div>
                        
                        <div class="comment-actions">
                            <button class="action-btn delete delete-comment" data-id="<?= $comment['comment_id'] ?>" title="Delete Comment">
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
    // Delete Comment
    $('.delete-comment').on('click', function() {
        if (confirm('Are you sure you want to delete this comment?')) {
            var commentId = $(this).data('id');
            var commentElement = $(this).closest('.user-comment-item');
            
            $.ajax({
                url: '<?= site_url('comments/delete/') ?>' + commentId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        commentElement.fadeOut(300, function() {
                            $(this).remove();
                            
                            // Update comment count
                            var count = $('.user-comment-item').length;
                            var countText = count + ' comment' + (count != 1 ? 's' : '');
                            $('.comments-container > p').text('You have made ' + countText + '.');
                            
                            // Show empty state if no comments left
                            if (count === 0) {
                                $('.comments-container').html(`
                                    <div class="empty-state">
                                        <i class="bi bi-chat-dots"></i>
                                        <p>You haven't made any comments yet.</p>
                                    </div>
                                `);
                            }
                        });
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while deleting the comment.');
                }
            });
        }
    });
});
</script>