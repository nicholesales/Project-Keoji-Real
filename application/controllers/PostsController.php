<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PostsController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        // Parent constructor already loads post_model and session
    }
    
    // Display all posts (main page after login)
    public function index()
    {
        // Check if user is logged in
        if (!$this->session->userdata('isLoggedIn')) {
            redirect('auth/login');
        }
    
        // Debug - check database connection and table structure
        try {
            // Debug - check tables
            $tables = $this->db->list_tables();
            log_message('debug', 'Database tables: ' . json_encode($tables));
            
            // Check if posts_table exists
            if (in_array('posts_table', $tables)) {
                $fields = $this->db->list_fields('posts_table');
                log_message('debug', 'posts_table fields: ' . json_encode($fields));
                
                // Check posts_table data
                $query = $this->db->query('SELECT * FROM posts_table LIMIT 5');
                $results = $query->result_array();
                log_message('debug', 'Sample posts data: ' . json_encode($results));
            } else {
                log_message('error', 'posts_table does not exist!');
            }
        } catch (Exception $e) {
            log_message('error', 'Database error: ' . $e->getMessage());
        }
        
        // Debug - Log session data
        log_message('debug', 'User session data: ' . json_encode($this->session->userdata()));
        
        // Get all recent posts - ensure we're getting unique records
        $this->db->order_by('date_created', 'DESC');
        $this->db->group_by('post_id'); // Add this line
        $data['recentPosts'] = $this->post_model->get_all();

        // Get featured posts - ensure we're getting unique records
        $this->db->where('featured', true);
        $this->db->group_by('post_id'); // Add this line
        $data['featuredPosts'] = $this->post_model->get_all();
        
        // Get categories for dropdown
        $data['categories'] = ['Lifestyle', 'Travel', 'Food', 'Sports', 'News'];
        
        // Set the page title
        $data['title'] = 'Blog Posts';
        
        // Add post count to the view data
        $data['post_count'] = $this->user_data['post_count'];
        
        // Load the view into a variable
        $data['content'] = $this->load->view('posts/posts_page', $data, TRUE);
        
        // Load the main template with our content
        $this->load->view('layout/main_posts', $data);
    }
    
    // Rest of the controller remains the same...
    // Create a new post
    public function create()
    {
        // Simplified CSRF check - security is already part of CI core
        if (!$this->input->is_ajax_request()) {
            show_error('Direct access not allowed.');
            return;
        }
        
        // Check if user is logged in
        if (!$this->session->userdata('isLoggedIn')) {
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            return;
        }
        
        // Get database connection for transaction
        $this->db->trans_begin();
        
        log_message('debug', 'POST data: ' . json_encode($this->input->post()));
        log_message('debug', 'FILES data: ' . json_encode($_FILES));
        
        try {
            // Make image field optional - this might be causing validation to fail
            $this->form_validation->set_rules('title', 'Title', 'required|min_length[3]|max_length[255]');
            $this->form_validation->set_rules('category', 'Category', 'required');
            $this->form_validation->set_rules('content', 'Content', 'required');
            
            // Check if image is uploaded
            if (isset($_FILES['image']) && $_FILES['image']['error'] != 4) { // 4 means no file was uploaded
                // Set validation for image - CI3 uses do_upload() with config instead of rules
                $config['upload_path'] = FCPATH . 'uploads/posts/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = '10240'; // 10MB
                $this->load->library('upload', $config);
            }
            
            if ($this->form_validation->run() == FALSE) {
                log_message('error', 'Validation errors: ' . json_encode($this->form_validation->error_array()));
                $this->db->trans_rollback();
                echo json_encode([
                    'success' => false, 
                    'errors' => $this->form_validation->error_array()
                ]);
                return;
            }
            
            // Handle file upload if provided
            $newName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] != 4) {
                // Ensure upload directory exists
                $uploadPath = FCPATH . 'uploads/posts';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                try {
                    if (!$this->upload->do_upload('image')) {
                        log_message('error', 'File upload error: ' . $this->upload->display_errors());
                        $this->db->trans_rollback();
                        echo json_encode([
                            'success' => false,
                            'message' => 'Error uploading file: ' . $this->upload->display_errors()
                        ]);
                        return;
                    } else {
                        $uploadData = $this->upload->data();
                        $newName = $uploadData['file_name'];
                        log_message('debug', 'File uploaded successfully: ' . $newName);
                    }
                } catch (Exception $e) {
                    log_message('error', 'File upload error: ' . $e->getMessage());
                    $this->db->trans_rollback();
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error uploading file: ' . $e->getMessage()
                    ]);
                    return;
                }
            }
            
            // Determine post status (draft or published)
            $status = $this->input->post('status');
            
            // Save post to database
            $postData = [
                'user_id' => $this->session->userdata('user_id'),
                'title' => $this->input->post('title'),
                'category' => $this->input->post('category'),
                'description' => $this->input->post('content'),
                'image' => $newName,
                'featured' => $this->input->post('featured') ? true : false,
                'status' => $status
            ];
            
            $result = $this->post_model->insert($postData);
            log_message('debug', 'Post insert result: ' . json_encode($result));
            
            if (!$result) {
                log_message('error', 'Database insert error: ' . json_encode($this->post_model->errors()));
                $this->db->trans_rollback();
                echo json_encode([
                    'success' => false,
                    'message' => 'Error saving post to database',
                    'dbErrors' => $this->post_model->errors()
                ]);
                return;
            }
            
            // If we got here, commit the transaction
            $this->db->trans_commit();
            
            // Update the post count in session
            $post_count = $this->post_model->count_by_user($this->session->userdata('user_id'));
            $this->session->set_userdata('post_count', $post_count);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Post ' . ($status === 'draft' ? 'saved as draft' : 'published') . ' successfully',
                'post_id' => $result
            ]);
            
        } catch (Exception $e) {
            // Roll back transaction on any exception
            $this->db->trans_rollback();
            
            log_message('error', 'Exception during post creation: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    // Edit an existing post
    public function edit($id = null)
    {
        // Check if user is logged in
        if (!$this->session->userdata('isLoggedIn')) {
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            return;
        }
        
        $post = $this->post_model->get($id);
        
        // Check if post exists and belongs to the user
        if (!$post || $post['user_id'] != $this->session->userdata('user_id')) {
            echo json_encode(['success' => false, 'message' => 'Post not found or access denied']);
            return;
        }
        
        echo json_encode(['success' => true, 'post' => $post]);
    }
    
    // Update an existing post
    public function update($id = null)
    {
        // Simplified CSRF check - use input class methods
        if (!$this->input->is_ajax_request()) {
            show_error('Direct access not allowed.');
            return;
        }
        
        // Check if user is logged in
        if (!$this->session->userdata('isLoggedIn')) {
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            return;
        }
        
        $post = $this->post_model->get($id);
        
        // Check if post exists and belongs to the user
        if (!$post || $post['user_id'] != $this->session->userdata('user_id')) {
            echo json_encode(['success' => false, 'message' => 'Post not found or access denied']);
            return;
        }
        
        // Validate form data
        $this->form_validation->set_rules('title', 'Title', 'required|min_length[3]|max_length[255]');
        $this->form_validation->set_rules('category', 'Category', 'required');
        $this->form_validation->set_rules('content', 'Content', 'required');
        
        // Check if image is uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] != 4) {
            $config['upload_path'] = FCPATH . 'uploads/posts/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = '1024'; // 1MB
            $this->load->library('upload', $config);
        }
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false, 
                'errors' => $this->form_validation->error_array()
            ]);
            return;
        }
        
        // Handle file upload if new image is provided
        $imageName = $post['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] != 4) {
            if (!$this->upload->do_upload('image')) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error uploading file: ' . $this->upload->display_errors()
                ]);
                return;
            } else {
                $uploadData = $this->upload->data();
                $imageName = $uploadData['file_name'];
                
                // Delete old image
                if (file_exists(FCPATH . 'uploads/posts/' . $post['image'])) {
                    unlink(FCPATH . 'uploads/posts/' . $post['image']);
                }
            }
        }
        
        // Determine post status (draft or published)
        $status = $this->input->post('status');
        
        // Update post in database
        $postData = [
            'title' => $this->input->post('title'),
            'category' => $this->input->post('category'),
            'description' => $this->input->post('content'),
            'image' => $imageName,
            'featured' => $this->input->post('featured') ? true : false,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->post_model->update($id, $postData);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Post updated successfully'
        ]);
    }
    
    // Delete a post
    public function delete($post_id = null)
{
    // Get post ID from URL or form
    if (!$post_id) {
        $post_id = $this->input->post('post_id');
    }
    
    if (!$post_id) {
        // No post ID provided
        $this->session->set_flashdata('error', 'No post ID provided');
        redirect('posts');
        return;
    }
    
    // Get post details before deleting
    $post = $this->post_model->get($post_id);
    
    if (!$post) {
        $this->session->set_flashdata('error', 'Post not found');
        redirect('posts');
        return;
    }
    
    // Check if user has permission to delete this post
    if ($this->session->userdata('user_id') && $post['user_id'] != $this->session->userdata('user_id')) {
        $this->session->set_flashdata('error', 'You do not have permission to delete this post');
        redirect('posts');
        return;
    }
    
    // Delete post record from database
    $deleted = $this->post_model->delete($post_id);
    
    if (!$deleted) {
        $this->session->set_flashdata('error', 'Failed to delete post');
        redirect('posts');
        return;
    }
    
    // Handle image deletion if post had an image
    if (!empty($post['image'])) {
        $image_path = FCPATH . 'uploads/posts/' . $post['image'];
        if (file_exists($image_path)) {
            @unlink($image_path);
        }
    }
    
    // Set success message
    $this->session->set_flashdata('message', 'Post deleted successfully');
    
    // Redirect back to posts page
    redirect('posts');
}
  public function get_drafts()
{
    // Debug information
    log_message('debug', '==== get_drafts() called ====');
    log_message('debug', 'Is AJAX request: ' . ($this->input->is_ajax_request() ? 'Yes' : 'No'));
    log_message('debug', 'User logged in: ' . ($this->session->userdata('isLoggedIn') ? 'Yes' : 'No'));
    
    // Check if request is AJAX
    if (!$this->input->is_ajax_request()) {
        log_message('error', 'Direct access attempted to get_drafts()');
        show_error('Direct access not allowed.');
        return;
    }
    
    // Check if user is logged in
    if (!$this->session->userdata('isLoggedIn')) {
        log_message('error', 'Non-logged in user attempted to get_drafts()');
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        return;
    }
    
    try {
        // Get user ID from session
        $user_id = $this->session->userdata('user_id');
        log_message('debug', 'User ID: ' . $user_id);
        
        // IMPORTANT: Reset query builder to prevent conflicts
        $this->db->reset_query();
        
        // Get draft posts for this user - Direct query approach
        $query = $this->db->select('*')
                         ->from('posts_table')
                         ->where('user_id', $user_id)
                         ->where('status', 'draft')
                         ->order_by('date_created', 'DESC')
                         ->get();
        
        // Log the SQL query for debugging
        log_message('debug', 'SQL Query: ' . $this->db->last_query());
        
        // Get the results as an array
        $drafts = $query->result_array();
        log_message('debug', 'Number of drafts found: ' . count($drafts));
        
        // Debug: Log the post IDs and statuses
        foreach ($drafts as $draft) {
            log_message('debug', 'Post ID: ' . $draft['post_id'] . ', Title: ' . $draft['title'] . ', Status: ' . $draft['status']);
        }
        
        // Prepare the HTML for drafts
        $html = '';
        
        if (empty($drafts)) {
            $html .= '<div class="empty-state">';
            $html .= '<i class="bi bi-file-earmark-text"></i>';
            $html .= '<p>No draft posts yet. Save a post as draft to see it here!</p>';
            $html .= '</div>';
        } else {
            foreach ($drafts as $draft) {
                $html .= '<div class="draft-item" data-post-id="' . $draft['post_id'] . '">';
                $html .= '<div class="draft-icon">';
                $html .= '<i class="bi bi-file-earmark"></i>';
                $html .= '</div>';
                $html .= '<div class="draft-content">';
                $html .= '<div class="draft-title">';
                $html .= '<span class="draft-title-text">' . htmlspecialchars($draft['title'], ENT_QUOTES, 'UTF-8') . '</span>';
                $html .= '</div>';
                $html .= '<div class="draft-meta">';
                $html .= '<span><i class="bi bi-tag"></i> ' . htmlspecialchars($draft['category'], ENT_QUOTES, 'UTF-8') . '</span>';
                $html .= '<span><i class="bi bi-calendar3"></i> Last edited: ' . date('M d, Y', strtotime($draft['updated_at'] ?? $draft['date_created'])) . '</span>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="draft-actions">';
                $html .= '<button class="action-btn edit-post" data-id="' . $draft['post_id'] . '" title="Edit">';
                $html .= '<i class="bi bi-pencil"></i>';
                $html .= '</button>';
                $html .= '<button class="action-btn delete delete-post" data-id="' . $draft['post_id'] . '" title="Delete">';
                $html .= '<i class="bi bi-trash"></i>';
                $html .= '</button>';
                $html .= '</div>';
                $html .= '</div>';
            }
        }
        
        log_message('debug', 'HTML generated successfully');
        echo json_encode(['success' => true, 'html' => $html]);
        
    } catch (Exception $e) {
        log_message('error', 'Exception in get_drafts(): ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
public function get_published()
{
    // Check if request is AJAX
    if (!$this->input->is_ajax_request()) {
        show_error('Direct access not allowed.');
        return;
    }
    
    // Check if user is logged in
    if (!$this->session->userdata('isLoggedIn')) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        return;
    }
    
    // Get user ID from session
    $user_id = $this->session->userdata('user_id');
    
    // Get published posts for this user
    $this->db->where('user_id', $user_id);
    $this->db->where('status', 'published');
    $this->db->order_by('date_created', 'DESC');
    $published = $this->post_model->get_all();
    
    // Prepare the HTML for published posts
    $html = '';
    
    if (empty($published)) {
        $html .= '<div class="empty-state">';
        $html .= '<i class="bi bi-file-earmark-text"></i>';
        $html .= '<p>No published posts yet. Publish a post to see it here!</p>';
        $html .= '</div>';
    } else {
        foreach ($published as $post) {
            $html .= '<div class="post-item" data-post-id="' . $post['post_id'] . '">';
            $html .= '<div class="post-icon">';
            $html .= '<i class="bi bi-file-earmark-text"></i>';
            $html .= '</div>';
            $html .= '<div class="post-content">';
            $html .= '<div class="post-title">';
            $html .= '<a href="' . site_url('posts/view/' . $post['post_id']) . '">';
            $html .= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8');
            $html .= '</a>';
            $html .= '</div>';
            $html .= '<div class="post-meta">';
            $html .= '<span class="status-badge published">Published</span>';
            $html .= '<span><i class="bi bi-calendar3"></i> ' . date('M d, Y', strtotime($post['date_created'])) . '</span>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="post-actions">';
            $html .= '<button class="action-btn edit-post" data-id="' . $post['post_id'] . '" title="Edit">';
            $html .= '<i class="bi bi-pencil"></i>';
            $html .= '</button>';
            $html .= '<button class="action-btn delete delete-post" data-id="' . $post['post_id'] . '" title="Delete">';
            $html .= '<i class="bi bi-trash"></i>';
            $html .= '</button>';
            $html .= '</div>';
            $html .= '</div>';
        }
    }
    
    echo json_encode(['success' => true, 'html' => $html]);
}

}