<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_model extends CI_Model {
    
    private $table = 'comments_table';
    
    public function __construct() {
        parent::__construct();
    }
    
    // Add a new comment
    public function add_comment($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    
    // Get a single comment by ID
    public function get_comment($comment_id) {
        $this->db->where('comment_id', $comment_id);
        return $this->db->get($this->table)->row_array();
    }
    
    // Get a comment with user information
    public function get_comment_with_user($comment_id) {
        $this->db->select('comments_table.*, user_table.username, user_table.profile_photo');
        $this->db->from($this->table);
        $this->db->join('user_table', 'user_table.user_id = comments_table.user_id');
        $this->db->where('comments_table.comment_id', $comment_id);
        return $this->db->get()->row_array();
    }
    
    // Get comments for a post
    public function get_comments_for_post($post_id, $limit = null, $offset = null) {
        $this->db->select('comments_table.*, user_table.username, user_table.profile_photo');
        $this->db->from($this->table);
        $this->db->join('user_table', 'user_table.user_id = comments_table.user_id');
        $this->db->where('comments_table.post_id', $post_id);
        $this->db->order_by('comments_table.date_commented', 'DESC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result_array();
    }
    
    // Count comments for a post
    public function count_comments($post_id) {
        $this->db->where('post_id', $post_id);
        return $this->db->count_all_results($this->table);
    }
    
    // Delete a comment
    public function delete_comment($comment_id, $user_id) {
        $this->db->where('comment_id', $comment_id);
        $this->db->where('user_id', $user_id); // Ensure the user owns the comment
        return $this->db->delete($this->table);
    }
}