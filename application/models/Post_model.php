<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model
{
    protected $table = 'posts_table';
    protected $primaryKey = 'post_id';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    // Get post by ID
    public function get($id)
    {
        return $this->db->get_where($this->table, [$this->primaryKey => $id])->row_array();
    }
    
    // Get post by specific field
    public function get_where($field, $value)
    {
        return $this->db->get_where($this->table, [$field => $value])->row_array();
    }
    
    // Get all posts with optional conditions
    public function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }
    
    // Get all posts for a specific user
    public function get_by_user($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->get($this->table)->result_array();
    }
    
    // Get posts by status for a specific user
    public function get_by_status($user_id, $status)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('status', $status);
        return $this->db->get($this->table)->result_array();
    }
    
    // Get featured posts for a specific user
    public function get_featured_by_user($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('featured', true);
        return $this->db->get($this->table)->result_array();
    }
    
    // Insert new post
    public function insert($data)
    {
        // Validate data before insert
        if ($this->_validate($data)) {
            $this->db->set('date_created', date('Y-m-d H:i:s'));
            $this->db->set('updated_at', date('Y-m-d H:i:s'));
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
        return false;
    }
    
    // Update post
    public function update($id, $data)
    {
        // Validate data before update
        if ($this->_validate($data)) {
            $this->db->set('updated_at', date('Y-m-d H:i:s'));
            $this->db->where($this->primaryKey, $id);
            $this->db->update($this->table, $data);
            return $this->db->affected_rows();
        }
        return false;
    }
    
    // Delete post
    public function delete($id)
    {
        $this->db->where($this->primaryKey, $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
    
    // Count posts by user
    public function count_by_user($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->count_all_results($this->table);
    }
    
    // Count posts by user and status
    public function count_by_user_status($user_id, $status)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('status', $status);
        return $this->db->count_all_results($this->table);
    }
    
    // Check if a post exists
    public function exists($id)
    {
        $this->db->where($this->primaryKey, $id);
        return ($this->db->count_all_results($this->table) > 0);
    }
    
    // Check if a post belongs to a user
    public function belongs_to_user($post_id, $user_id)
    {
        $this->db->where($this->primaryKey, $post_id);
        $this->db->where('user_id', $user_id);
        return ($this->db->count_all_results($this->table) > 0);
    }
    
    // Validation (similar to CI4's validation rules)
    private function _validate($data)
    {
        $this->load->library('form_validation');
        
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($data);
        
        if (isset($data['title'])) {
            $this->form_validation->set_rules('title', 'Title', 'required|min_length[3]|max_length[255]');
        }
        
        if (isset($data['category'])) {
            $this->form_validation->set_rules('category', 'Category', 'required');
        }
        
        if (isset($data['description'])) {
            $this->form_validation->set_rules('description', 'Description', 'required');
        }
        
        return $this->form_validation->run();
    }
    
    // Function to handle errors (useful for debugging)
    public function errors()
    {
        return $this->form_validation->error_array();
    }
}