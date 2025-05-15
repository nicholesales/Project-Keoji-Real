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

    // Display profile page
    public function profile()
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->user_model->get($user_id);
        
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
    
    // Handle profile photo upload
    private function uploadProfilePhoto()
    {
        // Create upload directory if it doesn't exist
        $uploadPath = './uploads/profiles/';
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
        
        $this->image_lib->clear();
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
    }
}