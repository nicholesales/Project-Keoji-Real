<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Like_model extends CI_Model {
    
    private $table = 'likes_table';
    
    public function __construct() {
        parent::__construct();
    }
    
    // Check if a post is liked by a user
    public function is_post_liked($post_id, $user_id) {
        $this->db->where('post_id', $post_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get($this->table);
        
        return ($query->num_rows() > 0);
    }
    
    // Toggle like for a post
    public function toggle_like($post_id, $user_id) {
        // Check if already liked
        if ($this->is_post_liked($post_id, $user_id)) {
            // Unlike
            $this->db->where('post_id', $post_id);
            $this->db->where('user_id', $user_id);
            $this->db->delete($this->table);
            return false; // Returned false means unliked
        } else {
            // Like
            $data = [
                'post_id' => $post_id,
                'user_id' => $user_id,
                'date_liked' => date('Y-m-d H:i:s')
            ];
            $this->db->insert($this->table, $data);
            return true; // Returned true means liked
        }
    }
    
    // Count likes for a post
    public function count_likes($post_id) {
        $this->db->where('post_id', $post_id);
        return $this->db->count_all_results($this->table);
    }
    
    // Get users who liked a post
    public function get_likes_with_users($post_id, $limit = null, $offset = null) {
        $this->db->select('likes_table.*, user_table.username, user_table.profile_photo');
        $this->db->from($this->table);
        $this->db->join('user_table', 'user_table.user_id = likes_table.user_id');
        $this->db->where('likes_table.post_id', $post_id);
        $this->db->order_by('likes_table.date_liked', 'DESC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result_array();
    }
}