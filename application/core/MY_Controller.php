<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    protected $user_data = array();
    
    public function __construct() {
        parent::__construct();
        $this->load->model('post_model');
        $this->load->library('session');
        
        // If user is logged in, get their post count
        if ($this->session->userdata('isLoggedIn')) {
            $user_id = $this->session->userdata('user_id');
            $post_count = $this->post_model->count_by_user($user_id);
            
            // Store in a class variable that child controllers can access
            $this->user_data['post_count'] = $post_count;
            
            // Also store in session for access in views
            $this->session->set_userdata('post_count', $post_count);
        }
    }
}