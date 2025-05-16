<div class="blog-posts-section">
    <div class="section-header">
        <h2>My Comments</h2>
    </div>
    
    <div class="comments-container posts-container">
        <?php if (empty($comments)): ?>
            <div class="empty-state">
                <i class="bi bi-chat-dots"></i>
                <p>You haven't made any comments yet.</p>
            </div>
        <?php else: ?>
            <p class="mb-3">You have made <?= $comment_count ?> comment<?= $comment_count != 1 ? 's' : '' ?>.</p>
            
            <?php foreach ($comments as $comment): ?>
                <div class="user-comment-item" data-comment-id="<?= $comment['comment_id'] ?>">
                    <a href="<?= site_url('posts/view/' . $comment['post_id']) ?>" class="comment-link">
                        <div class="comment-post-info">
                            <div class="post-title">
                                <?= htmlspecialchars($comment['post_title']) ?>
                                <i class="bi bi-arrow-right-circle"></i>
                            </div>
                            <div class="post-author">
                                <span>Posted by: <?= htmlspecialchars($comment['username']) ?></span>
                            </div>
                        </div>
                        
                        <div class="comment-content">
                            <?= nl2br(htmlspecialchars($comment['comment_text'])) ?>
                        </div>
                    </a>
                    
                    <div class="comment-meta">
                        <div class="comment-date">
                            <i class="bi bi-calendar3"></i> <?= date('M d, Y', strtotime($comment['date_commented'])) ?>
                        </div>
                        
                        <div class="comment-actions">
                            <button class="action-btn edit edit-comment" data-id="<?= $comment['comment_id'] ?>" title="Edit Comment">
                                <i class="bi bi-pencil"></i>
                            </button>
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

<!-- Modal for editing comments -->
<div class="comment-edit-modal" id="commentEditModal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Edit Comment</h5>
            <button type="button" class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <textarea id="editCommentText" class="form-control" rows="4"></textarea>
            <input type="hidden" id="editCommentId">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel">Cancel</button>
            <button type="button" class="btn-save">Save Changes</button>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Add hover effects to action buttons
    $('.delete-comment').hover(
        function() {
            $(this).find('i').removeClass('bi-trash').addClass('bi-trash-fill');
        },
        function() {
            $(this).find('i').removeClass('bi-trash-fill').addClass('bi-trash');
        }
    );
    
    $('.edit-comment').hover(
        function() {
            $(this).find('i').removeClass('bi-pencil').addClass('bi-pencil-fill');
        },
        function() {
            $(this).find('i').removeClass('bi-pencil-fill').addClass('bi-pencil');
        }
    );
    
    // Delete Comment
    $('.delete-comment').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent the click from propagating to the parent link
        
        var commentId = $(this).data('id');
        var commentElement = $(this).closest('.user-comment-item');
        
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
                            var count = $('.user-comment-item').length;
                            var countText = count + ' comment' + (count != 1 ? 's' : '');
                            $('.comments-container > p').text('You have made ' + countText + '.');
                            
                            // Show empty state if no comments left
                            if (count === 0) {
                                $('.comments-container').html(
                                    '<div class="empty-state">' +
                                    '<i class="bi bi-chat-dots"></i>' +
                                    '<p>You haven\'t made any comments yet.</p>' +
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
    
    // Edit Comment - Open Modal
    $('.edit-comment').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var commentId = $(this).data('id');
        var commentElement = $(this).closest('.user-comment-item');
        var commentText = commentElement.find('.comment-content').text().trim();
        
        // Set values in the modal
        $('#editCommentId').val(commentId);
        $('#editCommentText').val(commentText);
        
        // Show modal with flex display
        $('#commentEditModal').css('display', 'flex');
        $('#editCommentText').focus();
    });
    
    // Close modal
    $('.close-modal, .btn-cancel').on('click', function() {
        $('#commentEditModal').fadeOut(200);
    });
    
    // Save edited comment
    $('.btn-save').on('click', function() {
        var commentId = $('#editCommentId').val();
        var commentText = $('#editCommentText').val();
        var commentElement = $('.user-comment-item[data-comment-id="' + commentId + '"]');
        
        if (commentText.trim() === '') {
            alert('Comment cannot be empty');
            return;
        }
        
        $.ajax({
            url: '<?= site_url('comments/update') ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                comment_id: commentId,
                comment_text: commentText
            },
            success: function(response) {
                if (response.success) {
                    // Update the comment text in the DOM
                    commentElement.find('.comment-content').html(nl2br(response.comment_text));
                    
                    // Close the modal
                    $('#commentEditModal').fadeOut(200);
                    
                    // Show success message or visual feedback
                    commentElement.addClass('comment-updated');
                    setTimeout(function() {
                        commentElement.removeClass('comment-updated');
                    }, 2000);
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
    });
    
    // Helper function to convert newlines to <br> tags
    function nl2br(str) {
        return str.replace(/\n/g, '<br>');
    }
    
    // Allow ESC key to close modal
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#commentEditModal').is(':visible')) {
            $('#commentEditModal').fadeOut(200);
        }
    });
});
</script>