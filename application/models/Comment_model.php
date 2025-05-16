<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_model extends CI_Model
{
    protected $table = 'comments_table';
    protected $primaryKey = 'comment_id';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    // Get comment by ID
    public function get($id)
    {
        return $this->db->get_where($this->table, [$this->primaryKey => $id])->row_array();
    }
    
    // Alias for get() to match controller method name
    public function get_comment($id)
    {
        return $this->get($id);
    }
    
    // Get comment by specific field
    public function get_where($field, $value)
    {
        return $this->db->get_where($this->table, [$field => $value])->row_array();
    }
    
    // Get all comments
    public function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }
    
    // Insert new comment
    public function insert($data)
    {
        // Validate data before insert
        if ($this->_validate($data)) {
            // Add timestamp if your table has it
            if ($this->db->field_exists('date_commented', $this->table)) {
                $this->db->set('date_commented', date('Y-m-d H:i:s'));
            }
            
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
        return false;
    }
    
    // Update comment
    public function update($id, $data)
    {
        // Validate data before update
        if ($this->_validate($data)) {
            $this->db->where($this->primaryKey, $id);
            $this->db->update($this->table, $data);
            return $this->db->affected_rows();
        }
        return false;
    }
    
    // Delete comment
    public function delete($id)
    {
        $this->db->where($this->primaryKey, $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
    
    // Alias for delete() to match controller method name
    public function delete_comment($id, $user_id = null)
    {
        if ($user_id !== null) {
            $this->db->where('user_id', $user_id);
        }
        $this->db->where($this->primaryKey, $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
    
    // Get comments with user information for a post
    public function getCommentsWithUser($postId)
    {
        $this->db->select('comments_table.*, user_table.username, user_table.profile_photo');
        $this->db->from($this->table);
        $this->db->join('user_table', 'user_table.user_id = comments_table.user_id');
        $this->db->where('comments_table.post_id', $postId);
        $this->db->order_by('date_commented', 'DESC');
        return $this->db->get()->result_array();
    }
    
    // Count comments on a post
    public function countCommentsOnPost($postId)
    {
        $this->db->where('post_id', $postId);
        return $this->db->count_all_results($this->table);
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
        
        if (isset($data['comment_text'])) {
            $this->form_validation->set_rules('comment_text', 'Comment text', 'required');
        }
        
        // Custom error messages
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('integer', '%s must be a number');
        
        return $this->form_validation->run();
    }
}