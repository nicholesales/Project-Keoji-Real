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
        return $this->db->get_where($this->table, [$this->primaryKey => $id])->row_array();
    }
    
    // Get user by specific field
    public function get_where($field, $value)
    {
        return $this->db->get_where($this->table, [$field => $value])->row_array();
    }
    
    // Get all users
    public function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }
    
    // Insert new user
    public function insert($data)
    {
        // Validate data before insert
        if ($this->_validate($data)) {
            // Password is already hashed in the controller
            $this->db->set('created_at', date('Y-m-d H:i:s'));
            $this->db->set('updated_at', date('Y-m-d H:i:s'));
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
        return false;
    }
    
    // Update user
    public function update($id, $data)
    {
        // Validate data before update
        if ($this->_validate($data, $id)) {
            // If password is present, hash it
            if (isset($data['password']) && !empty($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            
            $this->db->set('updated_at', date('Y-m-d H:i:s'));
            $this->db->where($this->primaryKey, $id);
            $this->db->update($this->table, $data);
            return $this->db->affected_rows();
        }
        return false;
    }
    
    // Update password directly
    public function update_password($email, $password)
    {
        $this->db->set('password', $password);
        $this->db->set('updated_at', date('Y-m-d H:i:s'));
        $this->db->where('email', $email);
        $this->db->update($this->table);
        return $this->db->affected_rows();
    }
    
    // Delete user
    public function delete($id)
    {
        $this->db->where($this->primaryKey, $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
    
    // Validation (similar to CI4's validation rules)
    private function _validate($data, $id = null)
    {
        $this->load->library('form_validation');
        
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($data);
        
        if (isset($data['username'])) {
            $is_unique = '';
            if ($id) {
                $is_unique = '|is_unique[user_table.username.user_id.'.$id.']';
            } else {
                $is_unique = '|is_unique[user_table.username]';
            }
            $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[30]'.$is_unique);
        }
        
        if (isset($data['email'])) {
            $is_unique = '';
            if ($id) {
                $is_unique = '|is_unique[user_table.email.user_id.'.$id.']';
            } else {
                $is_unique = '|is_unique[user_table.email]';
            }
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email'.$is_unique);
        }
        
        if (isset($data['password']) && empty($id)) {
            // Only validate password for new users or if password is being updated
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        }
        
        if (isset($data['security_question'])) {
            $this->form_validation->set_rules('security_question', 'Security Question', 'required');
        }
        
        if (isset($data['security_answer'])) {
            $this->form_validation->set_rules('security_answer', 'Security Answer', 'required');
        }
        
        // Custom error messages
        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('min_length', '%s must be at least %s characters long');
        $this->form_validation->set_message('is_unique', 'This %s is already taken');
        $this->form_validation->set_message('valid_email', 'Please enter a valid email address');
        
        return $this->form_validation->run();
    }
}