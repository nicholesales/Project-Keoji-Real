<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Session $session
 * @property User_model $user_model
 * @property Post_model $post_model
 */
class AdminController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('post_model');
        $this->load->library('session');
        $this->load->helper('url'); // Add this line to load the URL helper

        // Check if user is logged in and is admin
        if (!$this->session->userdata('isLoggedIn') || !$this->session->userdata('is_admin')) {
            redirect('auth/login');
        }
    }

    // Admin dashboard
    public function dashboard()
    {
        // Get regular users count (excluding admins)
        $all_users = $this->user_model->get_all_users();
        $regular_users = array_filter($all_users, function ($user) {
            return $user['is_admin'] == 0;
        });

        $viewData['total_regular_users'] = count($regular_users);
        $viewData['total_posts'] = count($this->post_model->get_all());

        $data['title'] = 'Admin Dashboard';
        $data['content'] = $this->load->view('admin/dashboard', $viewData, TRUE);
        $this->load->view('layout/main', $data);
    }
    // Manage users page
    public function users()
    {
        // Get all users
        $viewData['users'] = $this->user_model->get_all_users();

        $data['title'] = 'Manage Users';
        $data['content'] = $this->load->view('admin/users', $viewData, TRUE);
        $this->load->view('layout/main', $data);
    }

    // View user posts
    public function userPosts($user_id)
    {
        // Get user details
        $viewData['user'] = $this->user_model->get($user_id);

        if (!$viewData['user']) {
            show_404();
        }

        // Get user posts
        $viewData['posts'] = $this->post_model->get_by_user($user_id);

        $data['title'] = 'User Posts';
        $data['content'] = $this->load->view('admin/user_posts', $viewData, TRUE);
        $this->load->view('layout/main', $data);
    }

    // Delete user
    public function deleteUser($user_id)
    {
        // Don't allow admin to delete themselves
        if ($user_id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'You cannot delete your own account!');
            redirect('admin/users');
            return;
        }

        // Get all user posts
        $user_posts = $this->post_model->get_by_user($user_id);

        // Delete all posts by this user
        foreach ($user_posts as $post) {
            $this->post_model->delete($post['post_id']);
        }

        // Delete user account
        if ($this->user_model->delete($user_id)) {
            $this->session->set_flashdata('message', 'User and all associated posts deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user. Please try again.');
        }

        redirect('admin/users');
    }

    // Delete a post
    public function deletePost($post_id)
    {
        // Store the user ID before deleting the post for redirection
        $post = $this->post_model->get($post_id);
        if (!$post) {
            show_404();
        }

        $user_id = $post['user_id'];

        // Delete the post
        if ($this->post_model->delete($post_id)) {
            $this->session->set_flashdata('message', 'Post deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete post. Please try again.');
        }

        // Redirect back to user's posts
        redirect('admin/user-posts/' . $user_id);
    }
}