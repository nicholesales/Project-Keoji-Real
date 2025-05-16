<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    protected $user_data = array();
    
    public function __construct() {
        parent::__construct();
        $this->load->model('post_model');
        $this->load->model('comment_model'); // Added comment model
        $this->load->library('session');
        
        // If user is logged in, get their post count and comment counts
        if ($this->session->userdata('isLoggedIn')) {
            $user_id = $this->session->userdata('user_id');
            
            // Get post count
            $post_count = $this->post_model->count_by_user($user_id);
            
            // Get comment counts
            $my_comment_count = $this->comment_model->count_comments($user_id);
            $received_comment_count = $this->comment_model->count_received_comments($user_id);
            
            // Store in a class variable that child controllers can access
            $this->user_data['post_count'] = $post_count;
            $this->user_data['my_comment_count'] = $my_comment_count;
            $this->user_data['received_comment_count'] = $received_comment_count;
            
            // Also store in session for access in views
            $this->session->set_userdata('post_count', $post_count);
            $this->session->set_userdata('my_comment_count', $my_comment_count);
            $this->session->set_userdata('received_comment_count', $received_comment_count);
        }
    }
    
    protected function _init_user_data() {
        // If user is logged in, reload their data
        if ($this->session->userdata('isLoggedIn')) {
            $user_id = $this->session->userdata('user_id');
            
            // Get counts for user
            $post_count = $this->post_model->count_by_user($user_id);
            $my_comment_count = $this->comment_model->count_comments($user_id);
            $received_comment_count = $this->comment_model->count_received_comments($user_id);
            
            // Update class variable
            $this->user_data['post_count'] = $post_count;
            $this->user_data['my_comment_count'] = $my_comment_count;
            $this->user_data['received_comment_count'] = $received_comment_count;
            
            // Update session data
            $this->session->set_userdata('post_count', $post_count);
            $this->session->set_userdata('my_comment_count', $my_comment_count);
            $this->session->set_userdata('received_comment_count', $received_comment_count);
        }
    }
}