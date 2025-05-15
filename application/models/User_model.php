<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    protected $table = 'user_table';
    protected $primaryKey = 'user_id';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    // Get user by ID
    public function get($id)
    {
        $this->db->where($this->primaryKey, $id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }
    
    // Get user by ID (alias for compatibility)
    public function get_by_id($id)
    {
        return $this->get($id);
    }
    
    // Get user by field
    public function get_where($field, $value)
    {
        $this->db->where($field, $value);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }
    
    // Insert new user
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }
    
    // Update user
    public function update($user_id, $data)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->update($this->table, $data);
    }
    
    // Update password
    public function update_password($email, $password)
    {
        $this->db->where('email', $email);
        return $this->db->update($this->table, ['password' => $password]);
    }
    
    // Delete user
    public function delete($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->delete($this->table);
    }
    
    // Check if username exists (excluding specific user)
    public function username_exists($username, $exclude_user_id = null)
    {
        $this->db->where('username', $username);
        if ($exclude_user_id) {
            $this->db->where('user_id !=', $exclude_user_id);
        }
        $query = $this->db->get($this->table);
        return $query->num_rows() > 0;
    }
    
    // Check if email exists (excluding specific user)
    public function email_exists($email, $exclude_user_id = null)
    {
        $this->db->where('email', $email);
        if ($exclude_user_id) {
            $this->db->where('user_id !=', $exclude_user_id);
        }
        $query = $this->db->get($this->table);
        return $query->num_rows() > 0;
    }
}