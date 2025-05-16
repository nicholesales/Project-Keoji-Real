<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CommentController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('comment_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        // Check if user is logged in
        if (!$this->session->userdata('isLoggedIn')) {
            redirect('auth/login');
        }
    }
    
    // Display all comments made by current user
    public function my_comments()
    {
        $user_id = $this->session->userdata('user_id');
        
        // Get all comments by this user
        $this->db->select('comments_table.*, posts_table.title as post_title, user_table.username');
        $this->db->from('comments_table');
        $this->db->join('posts_table', 'posts_table.post_id = comments_table.post_id');
        $this->db->join('user_table', 'user_table.user_id = posts_table.user_id');
        $this->db->where('comments_table.user_id', $user_id);
        $this->db->order_by('comments_table.date_commented', 'DESC');
        $data['comments'] = $this->db->get()->result_array();
        
        // Get comment count
        $data['comment_count'] = count($data['comments']);
        
        // Set the page title
        $data['title'] = 'My Comments';
        
        // Pass user data to view for sidebar
        $data['user_data'] = $this->user_data;
        
        // Load the view into a variable
        $data['content'] = $this->load->view('posts/my_comments', $data, TRUE);
        
        // Load the main template with our content
        $this->load->view('layout/main_posts', $data);
    }
    
    // Display all comments received on current user's posts
    public function received_comments()
    {
        $user_id = $this->session->userdata('user_id');
        
        // Get all comments on this user's posts
        $this->db->select('comments_table.*, posts_table.title as post_title, user_table.username, user_table.profile_photo');
        $this->db->from('comments_table');
        $this->db->join('posts_table', 'posts_table.post_id = comments_table.post_id');
        $this->db->join('user_table', 'user_table.user_id = comments_table.user_id');
        $this->db->where('posts_table.user_id', $user_id);
        $this->db->order_by('comments_table.date_commented', 'DESC');
        $data['comments'] = $this->db->get()->result_array();
        
        // Get comment count
        $data['comment_count'] = count($data['comments']);
        
        // Set the page title
        $data['title'] = 'Received Comments';
        
        // Pass user data to view for sidebar
        $data['user_data'] = $this->user_data;
        
        // Load the view into a variable
        $data['content'] = $this->load->view('posts/received_comments', $data, TRUE);
        
        // Load the main template with our content
        $this->load->view('layout/main_posts', $data);
    }
    
    // Delete a comment
    public function delete($id = null)
    {
        // Check if request is AJAX
        if (!$this->input->is_ajax_request()) {
            show_error('Direct access not allowed.');
            return;
        }
        
        // Check if user is logged in
        if (!$this->session->userdata('isLoggedIn')) {
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            return;
        }
        
        // Make sure we have an ID
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'No comment ID provided']);
            return;
        }
        
        // Get the comment
        $comment = $this->comment_model->get($id);
        
        // Check if comment exists
        if (!$comment) {
            echo json_encode(['success' => false, 'message' => 'Comment not found']);
            return;
        }
        
        // Get the post to check ownership
        $this->db->where('post_id', $comment['post_id']);
        $post = $this->db->get('posts_table')->row_array();
        
        // Check permissions: Either the user created the comment OR the user owns the post the comment was made on
        $is_comment_owner = ($comment['user_id'] == $this->session->userdata('user_id'));
        $is_post_owner = ($post && $post['user_id'] == $this->session->userdata('user_id'));
        
        if (!$is_comment_owner && !$is_post_owner) {
            echo json_encode(['success' => false, 'message' => 'You do not have permission to delete this comment']);
            return;
        }
        
        // Delete comment from database
        $result = $this->comment_model->delete($id);
        
        // Update user's comment counts
        $this->_init_user_data();
        
        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => 'Comment deleted successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Failed to delete comment'
            ]);
        }
    }
    
    // Get comment for editing
    public function edit($id = null)
    {
        // Check if request is AJAX
        if (!$this->input->is_ajax_request()) {
            show_error('Direct access not allowed.');
            return;
        }
        
        // Check if user is logged in
        if (!$this->session->userdata('isLoggedIn')) {
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            return;
        }
        
        // Get the comment
        $comment = $this->comment_model->get($id);
        
        // Check if comment exists
        if (!$comment) {
            echo json_encode(['success' => false, 'message' => 'Comment not found']);
            return;
        }
        
        // Check permissions: Only the comment owner can edit their comment
        if ($comment['user_id'] != $this->session->userdata('user_id')) {
            echo json_encode(['success' => false, 'message' => 'You do not have permission to edit this comment']);
            return;
        }
        
        // Return the comment data
        echo json_encode([
            'success' => true,
            'comment' => [
                'id' => $comment['comment_id'],
                'text' => $comment['comment_text']
            ]
        ]);
    }
    
    // Update comment
    public function update()
    {
        // Check if request is AJAX
        if (!$this->input->is_ajax_request()) {
            show_error('Direct access not allowed.');
            return;
        }
        
        // Check if user is logged in
        if (!$this->session->userdata('isLoggedIn')) {
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            return;
        }
        
        // Get form data
        $comment_id = $this->input->post('comment_id');
        $comment_text = $this->input->post('comment_text');
        
        // Validate input
        if (!$comment_id || !$comment_text) {
            echo json_encode(['success' => false, 'message' => 'Comment ID and text are required']);
            return;
        }
        
        // Get the existing comment
        $comment = $this->comment_model->get($comment_id);
        
        // Check if comment exists
        if (!$comment) {
            echo json_encode(['success' => false, 'message' => 'Comment not found']);
            return;
        }
        
        // Check permissions: Only the comment owner can edit their comment
        if ($comment['user_id'] != $this->session->userdata('user_id')) {
            echo json_encode(['success' => false, 'message' => 'You do not have permission to edit this comment']);
            return;
        }
        
        // Update comment
        $data = [
            'comment_text' => $comment_text
        ];
        
        $result = $this->comment_model->update($comment_id, $data);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Comment updated successfully',
                'comment_text' => $comment_text
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update comment'
            ]);
        }
    }
}