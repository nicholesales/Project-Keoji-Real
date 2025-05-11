<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Like_model extends CI_Model
{
    protected $table = 'likes_table';
    protected $primaryKey = 'like_id';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    // Get like by ID
    public function get($id)
    {
        return $this->db->get_where($this->table, [$this->primaryKey => $id])->row_array();
    }
    
    // Get like by specific field
    public function get_where($field, $value)
    {
        return $this->db->get_where($this->table, [$field => $value])->row_array();
    }
    
    // Get all likes
    public function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }
    
    // Insert new like
    public function insert($data)
    {
        // Validate data before insert
        if ($this->_validate($data)) {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
        return false;
    }
    
    // Delete like
    public function delete($id)
    {
        $this->db->where($this->primaryKey, $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
    
    // Delete like by user and post
    public function delete_by_user_post($userId, $postId)
    {
        $this->db->where('user_id', $userId);
        $this->db->where('post_id', $postId);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
    
    // Check if user has liked post
    public function hasUserLiked($userId, $postId)
    {
        $this->db->where('user_id', $userId);
        $this->db->where('post_id', $postId);
        return $this->db->count_all_results($this->table) > 0;
    }
    
    // Count likes on a post
    public function countLikesOnPost($postId)
    {
        $this->db->where('post_id', $postId);
        return $this->db->count_all_results($this->table);
    }
    
    // Get likes by user
    public function getLikesByUser($userId)
    {
        $this->db->where('user_id', $userId);
        return $this->db->get($this->table)->result_array();
    }
    
    // Validation
    private function _validate($data)
    {
        $this->load->library('form_validation');
        
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($data);
        
        if (isset($data['user_id'])) {
            $this->form_validation->set_rules('user_id', 'User ID', 'required|integer');
        }
        
        if (isset($data['post_id'])) {
            $this->form_validation->set_rules('post_id', 'Post ID', 'required|integer');
        }
        
        return $this->form_validation->run();
    }
}