<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Session $session
 */
class AuthController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('email');
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    public function index()
    {
        redirect('auth/login');
    }

    // In your Auth controller, add this method
    public function check_not_same_as_old_password($newPassword)
    {
        // Get email from session
        $email = $this->session->userdata('reset_email');

        // Get user by email
        $user = $this->user_model->get_where('email', $email);

        if ($user) {
            // If password matches old password, fail validation
            if (password_verify($newPassword, $user['password'])) {
                $this->form_validation->set_message('check_not_same_as_old_password', 'The {field} cannot be the same as your current password.');
                return FALSE;
            }
        }

        return TRUE;
    }

    // Show registration form
    public function register()
    {
        // Get security questions
        $viewData['securityQuestions'] = [
            'What was your first pet\'s name?',
            'What is your mother\'s maiden name?',
            'What city were you born in?',
            'What was the name of your elementary school?',
            'What is the name of your favorite childhood friend?'
        ];

        // Template-related data
        $data['title'] = 'Register';

        // Capture the view output
        $data['content'] = $this->load->view('auth/register', $viewData, TRUE);

        // Load the main template with the view content
        $this->load->view('layout/main', $data);
    }

    // Process registration
    public function processRegistration()
    {
        // Validate form data
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[30]|is_unique[user_table.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user_table.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        $this->form_validation->set_rules('security_question', 'Security Question', 'required');
        $this->form_validation->set_rules('security_answer', 'Security Answer', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
            redirect('auth/register');
        }

        // Save user to database
        $userData = [
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'security_question' => $this->input->post('security_question'),
            'security_answer' => $this->input->post('security_answer'),
            'is_admin' => false
        ];

        $this->user_model->insert($userData);

        // Send welcome email
        $this->sendWelcomeEmail($userData['email'], $userData['username']);

        // Set flash message and redirect to login
        $this->session->set_flashdata('message', 'Registration successful! You can now login.');
        redirect('auth/login');
    }

    // Send welcome email
    private function sendWelcomeEmail($to, $username)
    {
        $this->email->from('noreply@yourdomain.com', 'Your Social Media Platform');
        $this->email->to($to);
        $this->email->subject('Welcome to Our Platform');

        $message = "
            <h1>Welcome to Our Platform, {$username}!</h1>
            <p>Thank you for joining our community. We're excited to have you on board.</p>
            <p>You can now login and start sharing posts, liking content, and connecting with others.</p>
            <p>Best regards,<br>The Team</p>
        ";

        $this->email->message($message);
        $this->email->send();
    }

    // Show login form
    public function login()
    {
        // Template-related data
        $data['title'] = 'Login';

        // Capture the view output (no data needed for login view)
        $data['content'] = $this->load->view('auth/login', '', TRUE);

        // Load the main template with the view content
        $this->load->view('layout/main', $data);

    }

    // Process login
    public function processLogin()
    {
        // Validate form data
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
            redirect('auth/login');
        }

        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Check if username exists
        $user = $this->user_model->get_where('username', $username);

        if (!$user) {
            $this->session->set_flashdata('error', 'Invalid username or password');
            redirect('auth/login');
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            $this->session->set_flashdata('error', 'Invalid username or password');
            redirect('auth/login');
        }

        // Set session data
        $sessionData = [
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'is_admin' => $user['is_admin'],
            'isLoggedIn' => true
        ];

        // Add profile photo to session if exists
        if (isset($user['profile_photo']) && $user['profile_photo']) {
            $sessionData['profile_photo'] = $user['profile_photo'];
        }

        $this->session->set_userdata($sessionData);

        // Redirect to feed page
        redirect('posts');
    }

    // Logout
    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', 'You have been logged out successfully');
        redirect('auth/login');
    }

    // Forgot password page
    public function forgotPassword()
    {
        // Template-related data
        $data['title'] = 'Forgot Password';

        // Capture the view output
        $data['content'] = $this->load->view('auth/forgot_password', '', TRUE);

        // Load the main template with the view content
        $this->load->view('layout/main', $data);
    }

    // Process forgot password request
    public function processForgotPassword()
    {
        // Validate form data
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
            redirect('auth/forgot-password');  // Changed from forgotPassword
        }

        $email = $this->input->post('email');

        // Check if email exists
        $user = $this->user_model->get_where('email', $email);

        if (!$user) {
            $this->session->set_flashdata('error', 'Email not found in our records');
            redirect('auth/forgot-password');  // Changed from forgotPassword
        }

        // Store email in session for security question verification
        $this->session->set_userdata('reset_email', $email);

        // Redirect to security question page
        redirect('auth/security-question');
    }

    // Security question page
    public function securityQuestion()
    {
        if (!$this->session->userdata('reset_email')) {
            redirect('auth/forgot-password');  // Changed from forgotPassword
        }

        $email = $this->session->userdata('reset_email');
        $user = $this->user_model->get_where('email', $email);

        $viewData['security_question'] = $user['security_question'];

        // Template-related data
        $data['title'] = 'Security Question';
        
        // Capture the view output
        $data['content'] = $this->load->view('auth/security_question', $viewData, TRUE);
        
        // Load the main template with the view content
        $this->load->view('layout/main', $data);
    }

    // Process security question answer
    public function processSecurityQuestion()
    {
        if (!$this->session->userdata('reset_email')) {
            redirect('auth/forgot-password');  // Changed from forgotPassword
        }

        // Validate form data
        $this->form_validation->set_rules('security_answer', 'Security Answer', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
            redirect('auth/security-question');  // Already correct
        }

        $email = $this->session->userdata('reset_email');
        $securityAnswer = $this->input->post('security_answer');

        // Check if answer is correct
        $user = $this->user_model->get_where('email', $email);

        if ($user['security_answer'] !== $securityAnswer) {
            $this->session->set_flashdata('error', 'Incorrect security answer');
            redirect('auth/security-question');
        }

        // Redirect to reset password page
        $this->session->set_userdata('can_reset_password', true);
        redirect('auth/reset-password');
    }

    // Reset password page
    public function resetPassword()
    {
        if (!$this->session->userdata('reset_email') || !$this->session->userdata('can_reset_password')) {
            redirect('auth/forgot-password');  // Changed from forgotPassword
        }

        // Template-related data
        $data['title'] = 'Reset Password';
        
        // Capture the view output (no special view data needed)
        $data['content'] = $this->load->view('auth/reset_password', '', TRUE);
        
        // Load the main template with the view content
        $this->load->view('layout/main', $data);
    }

    // Process password reset
    public function processResetPassword()
    {
        if (!$this->session->userdata('reset_email') || !$this->session->userdata('can_reset_password')) {
            redirect('auth/forgot-password');  // Changed from forgotPassword
        }

        // Get email from session
        $email = $this->session->userdata('reset_email');

        // Define validation rules
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|callback_check_not_same_as_old_password');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

        // Validate the form
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('errors', $this->form_validation->error_array());
            redirect('auth/reset-password');  // Changed from resetPassword
        }

        // Proceed with resetting the password
        $password = $this->input->post('password');
        $this->user_model->update_password($email, password_hash($password, PASSWORD_DEFAULT));

        // Clear session and redirect to login
        $this->session->unset_userdata(['reset_email', 'can_reset_password']);
        $this->session->set_flashdata('message', 'Password has been reset successfully!');
        redirect('auth/login');

    }
}