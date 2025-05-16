<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Upload $upload
 * @property CI_Image_lib $image_lib
 */
class UserController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('session');
        $this->load->library('upload');
        $this->load->library('image_lib');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        // Check if user is logged in
        if (!$this->session->userdata('isLoggedIn')) {
            redirect('auth/login');
        }
    }

    // Get all users
    public function get_all_users()
    {
        $query = $this->db->get($this->table);
        return $query->result_array();
    }


    // Display profile page
    public function profile()
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->user_model->get_where('user_id', $user_id);

        if (!$user) {
            show_404();
        }

        $viewData['user'] = $user;
        $viewData['securityQuestions'] = [
            'What was your first pet\'s name?',
            'What is your mother\'s maiden name?',
            'What city were you born in?',
            'What was the name of your elementary school?',
            'What is the name of your favorite childhood friend?'
        ];

        $data['title'] = 'User Profile';
        $data['content'] = $this->load->view('user/profile', $viewData, TRUE);
        $this->load->view('layout/main', $data);
    }

    // Process profile update
    public function updateProfile()
    {
        $user_id = $this->session->userdata('user_id');
        $current_user = $this->user_model->get($user_id);

        // Set validation rules
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[30]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('security_question', 'Security Question', 'required');
        $this->form_validation->set_rules('security_answer', 'Security Answer', 'required');

        // If password is provided, add password validation rules
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[8]|callback_check_not_same_as_old_password');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        }

        // Run validation
        if ($this->form_validation->run() == FALSE) {
            // Store validation errors in flash data
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
            redirect('user/profile');
            return;
        }

        // Validate username uniqueness
        if ($this->input->post('username') != $current_user['username']) {
            if ($this->user_model->username_exists($this->input->post('username'), $user_id)) {
                $this->session->set_flashdata('error', 'This username is already taken.');
                redirect('user/profile');
                return;
            }
        }

        // Validate email uniqueness
        if ($this->input->post('email') != $current_user['email']) {
            if ($this->user_model->email_exists($this->input->post('email'), $user_id)) {
                $this->session->set_flashdata('error', 'This email is already registered.');
                redirect('user/profile');
                return;
            }
        }

        // Prepare user data for update
        $updateData = [
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'security_question' => $this->input->post('security_question'),
            'security_answer' => $this->input->post('security_answer')
        ];

        // Only include password if provided
        if ($this->input->post('password')) {
            $updateData['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        }

        // Handle profile photo upload
        if (!empty($_FILES['profile_photo']['name'])) {
            // Manual file validation
            $allowed_types = array('jpg', 'jpeg', 'png');
            $file_extension = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));

            // Check file extension
            if (!in_array($file_extension, $allowed_types)) {
                $this->session->set_flashdata('error', 'Please upload only JPG or PNG images.');
                redirect('user/profile');
                return;
            }

            // Check file size (5MB = 5242880 bytes)
            if ($_FILES['profile_photo']['size'] > 5242880) {
                $this->session->set_flashdata('error', 'File size must be less than 5MB.');
                redirect('user/profile');
                return;
            }

            $uploadResult = $this->uploadProfilePhoto();

            if ($uploadResult['status']) {
                $updateData['profile_photo'] = $uploadResult['filename'];
                error_log('Profile photo uploaded successfully: ' . $uploadResult['filename']);

                // Delete old photo if exists
                if ($current_user['profile_photo'] && file_exists('./uploads/profiles/' . $current_user['profile_photo'])) {
                    unlink('./uploads/profiles/' . $current_user['profile_photo']);
                }
            } else {
                $this->session->set_flashdata('error', $uploadResult['error']);
                redirect('user/profile');
                return;
            }
        }

        // Debug: Check update data
        error_log('Update data being sent to database: ' . print_r($updateData, true));

        // Add update timestamp
        $updateData['updated_at'] = date('Y-m-d H:i:s');

        // Update user in database
        if ($this->user_model->update($user_id, $updateData)) {
            // Get updated user data to include profile photo
            $updated_user = $this->user_model->get($user_id);

            // Update session data completely
            $sessionData = [
                'user_id' => $updated_user['user_id'],
                'username' => $updated_user['username'],
                'email' => $updated_user['email'],
                'is_admin' => $updated_user['is_admin'],
                'isLoggedIn' => true
            ];

            // Add profile photo to session
            if (!empty($updated_user['profile_photo'])) {
                $sessionData['profile_photo'] = $updated_user['profile_photo'];
            } else {
                // Clear profile photo from session if none exists
                $this->session->unset_userdata('profile_photo');
            }

            // Update all session data at once
            $this->session->set_userdata($sessionData);

            $this->session->set_flashdata('message', 'Profile updated successfully!');

            // Redirect to posts page instead of profile
            redirect('posts');
        } else {
            $this->session->set_flashdata('error', 'Failed to update profile. Please try again.');
            redirect('user/profile');
        }
    }

    // Callback function to check if new password is different from old password
    public function check_not_same_as_old_password($newPassword)
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->user_model->get($user_id);

        if ($user) {
            // If password matches old password, fail validation
            if (password_verify($newPassword, $user['password'])) {
                $this->form_validation->set_message('check_not_same_as_old_password', 'The new password cannot be the same as your current password.');
                return FALSE;
            }
        }

        return TRUE;
    }

    // Handle profile photo upload
    private function uploadProfilePhoto()
    {
        // Create upload directory if it doesn't exist
        $uploadPath = FCPATH . 'uploads/profiles/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Configure upload
        $config['upload_path'] = $uploadPath;
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size'] = 5120; // 5MB
        $config['file_name'] = 'profile_' . $this->session->userdata('user_id') . '_' . time();

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('profile_photo')) {
            return ['status' => false, 'error' => $this->upload->display_errors()];
        }

        // Get upload data
        $uploadData = $this->upload->data();

        // Create square crop (for circle display)
        $this->cropToSquare($uploadData['full_path']);

        return ['status' => true, 'filename' => $uploadData['file_name']];
    }

    // Crop image to square
    private function cropToSquare($imagePath)
    {
        // Get image info
        $imageInfo = getimagesize($imagePath);
        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // Calculate crop dimensions
        $size = min($width, $height);
        $x = ($width - $size) / 2;
        $y = ($height - $size) / 2;

        // Configure image manipulation
        $config['image_library'] = 'gd2';
        $config['source_image'] = $imagePath;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $size;
        $config['height'] = $size;
        $config['x_axis'] = $x;
        $config['y_axis'] = $y;

        $this->image_lib->initialize($config);
        $this->image_lib->crop();

        // Resize to standard size (e.g., 200x200)
        $config['width'] = 200;
        $config['height'] = 200;
        $config['x_axis'] = 0;
        $config['y_axis'] = 0;
        $config['new_image'] = $imagePath; // Overwrite the original

        $this->image_lib->clear();
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
    }
}