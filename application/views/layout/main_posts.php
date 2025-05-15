<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Blog App' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f5f5f5;
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        /* Sidebar Styles */
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #fff;
            color: #333;
            transition: all 0.3s;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 999;
        }

        #sidebar.active {
            margin-left: -250px;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #sidebar .sidebar-header h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: bold;
        }

        #sidebar ul {
            padding: 0;
            list-style: none;
        }

        #sidebar ul li {
            position: relative;
        }

        #sidebar ul li a {
            padding: 15px 20px;
            font-size: 0.95rem;
            display: block;
            color: #333;
            text-decoration: none;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s;
        }

        #sidebar ul li a:hover {
            background-color: #f5f5f5;
        }

        #sidebar ul li.active>a {
            background-color: #e8e8e8;
            font-weight: bold;
        }

        #sidebar ul ul a {
            font-size: 0.9rem;
            padding-left: 30px;
            background: #f8f8f8;
        }

        #sidebar .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            border-top: 1px solid #e0e0e0;
        }

        /* Collapsible menu button */
        .dropdown-toggle::after {
            display: inline-block;
            margin-left: auto;
            vertical-align: 0.255em;
            content: "▾";
            transition: transform 0.3s;
        }

        .dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        #sidebar ul ul {
            display: none;
        }

        #sidebar ul li.menu-open>ul {
            display: block;
        }

        /* Main Content */
        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s;
            padding-left: 250px;
        }

        #content.active {
            padding-left: 0;
        }

        /* Navigation Bar */
        .navbar {
            background-color: #fff !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
            border-bottom: 1px solid #e0e0e0;
        }

        .navbar-brand {
            color: #333 !important;
            font-weight: bold;
        }

        #sidebarCollapse {
            background: transparent;
            border: none;
            color: #333;
            font-size: 1.5rem;
            padding: 0;
            margin-right: 15px;
            cursor: pointer;
        }

        #sidebarCollapse:hover {
            color: #555;
        }

        /* Feed and Post Icons */
        .nav-icon {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .nav-count {
            background-color: #ff4458;
            color: white;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: auto;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }

            #sidebar.active {
                margin-left: 0;
            }

            #content {
                padding-left: 0;
            }

            #content.active {
                padding-left: 250px;
            }
        }

        /* Overlay for mobile */
        .overlay {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 998;
            background: rgba(0, 0, 0, 0.5);
        }

        .overlay.active {
            display: block;
        }

        /* Post item styling */
        .post-item:hover {
            background-color: #f8f9fa;
        }

        /* Sidebar Icons */
        .sidebar-icon {
            width: 20px;
            margin-right: 10px;
            color: #666;
        }

        /* Profile section for logged in users */
        .user-profile {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #666;
            overflow: hidden;
            flex-shrink: 0;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info {
            flex: 1;
        }

        .user-info .username {
            font-weight: bold;
            margin: 0;
            font-size: 0.95rem;
        }

        .user-info .role {
            font-size: 0.85rem;
            color: #666;
            margin: 0;
        }

        /* Profile image in navbar */
        .profile-image-dropdown {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }

        .dropdown button {
            padding: 0;
            border: none;
        }

        .dropdown button:focus {
            box-shadow: none;
        }
    </style>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><i class="bi bi-journal-bookmark"></i> Logo</h3>
            </div>

            <?php if ($this->session->userdata('isLoggedIn')): ?>
                <div class="user-profile">
                    <div class="user-avatar">
                        <?php if ($this->session->userdata('profile_photo')): ?>
                            <img src="<?= base_url('uploads/profiles/' . $this->session->userdata('profile_photo')) ?>" alt="Profile">
                        <?php else: ?>
                            <?= strtoupper(substr($this->session->userdata('username'), 0, 1)) ?>
                        <?php endif; ?>
                    </div>
                    <div class="user-info">
                        <p class="username">
                            <?= htmlspecialchars($this->session->userdata('username'), ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="role"><?= $post_count ?? $this->session->userdata('post_count') ?? 0 ?> Posts</p>
                    </div>
                </div>
            <?php endif; ?>

            <ul class="list-unstyled components">
                <li class="active">
                    <a href="<?= site_url('posts') ?>">
                        <i class="bi bi-grid sidebar-icon"></i>Feed
                        <?php if(isset($post_count) && $post_count > 0): ?>
                            <span class="nav-count"><?= $post_count ?></span>
                        <?php elseif($this->session->userdata('post_count') && $this->session->userdata('post_count') > 0): ?>
                            <span class="nav-count"><?= $this->session->userdata('post_count') ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('posts/create') ?>">
                        <i class="bi bi-file-earmark-text sidebar-icon"></i>Post
                    </a>
                </li>
                <?php if ($this->session->userdata('isLoggedIn')): ?>
                    <li>
                        <a href="#commentSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <i class="bi bi-chat-left-text sidebar-icon"></i>Comments
                        </a>
                        <ul class="collapse list-unstyled" id="commentSubmenu">
                            <li>
                                <a href="<?= site_url('comments/my-comments') ?>">My Comments</a>
                            </li>
                            <li>
                                <a href="<?= site_url('comments/received') ?>">Received Comments</a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>

            <?php if ($this->session->userdata('isLoggedIn')): ?>
                <div class="sidebar-footer">
                    <a href="<?= site_url('auth/logout') ?>" class="btn btn-outline-danger btn-sm w-100">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            <?php else: ?>
                <div class="sidebar-footer">
                    <a href="<?= site_url('auth/login') ?>" class="btn btn-primary btn-sm w-100 mb-2">Login</a>
                    <a href="<?= site_url('auth/register') ?>" class="btn btn-outline-primary btn-sm w-100">Register</a>
                </div>
            <?php endif; ?>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" aria-label="Toggle Sidebar">
                        <i class="bi bi-list"></i>
                    </button>
                    <h4 class="m-0"><?= isset($title) ? $title : 'POST' ?></h4>

                    <div class="ms-auto d-flex align-items-center">
                        <button class="btn btn-link position-relative me-3" aria-label="Theme Toggle">
                            <i class="bi bi-moon-fill text-dark"></i>
                        </button>
                        <?php if ($this->session->userdata('isLoggedIn')): ?>
                            <div class="dropdown">
                                <button class="btn btn-link dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <?php if ($this->session->userdata('profile_photo')): ?>
                                        <img src="<?= base_url('uploads/profiles/' . $this->session->userdata('profile_photo')) ?>" 
                                             alt="Profile" 
                                             class="profile-image-dropdown">
                                    <?php else: ?>
                                        <i class="bi bi-person-circle text-dark fs-4"></i>
                                    <?php endif; ?>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?= site_url('profile') ?>">Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="<?= site_url('auth/logout') ?>">Logout</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="<?= site_url('auth/login') ?>" class="btn btn-link">
                                <i class="bi bi-person-circle text-dark fs-4"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('message')): ?>
                <div class="container-fluid mt-3">
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $this->session->flashdata('message') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="container-fluid mt-3">
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Main Content -->
            <div class="container-fluid">
                <?= $content ?>
            </div>
        </div>
    </div>

    <!-- Overlay for mobile -->
    <div class="overlay"></div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Script for Sidebar Toggle -->
    <script>
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
                $('.overlay').toggleClass('active');
            });

            $('.overlay').on('click', function () {
                $('#sidebar').removeClass('active');
                $('#content').removeClass('active');
                $('.overlay').removeClass('active');
            });

            // Handle collapsible menu items
            $('#sidebar .dropdown-toggle').on('click', function (e) {
                e.preventDefault();
                $(this).parent().toggleClass('menu-open');
                $(this).attr('aria-expanded', $(this).parent().hasClass('menu-open'));
            });
        });
    </script>
</body>

</html>