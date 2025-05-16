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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('assets/css/posts-styles.css'); ?>">
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><i class="bi bi-journal-bookmark"></i> <span>Logo</span></h3>
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
                <li>
                    <a href="<?= site_url('posts/create') ?>">
                        <i class="bi bi-grid sidebar-icon"></i><span>Feed</span>
                        
                    </a>
                </li>
                <li class="active">
                    <a href="<?= site_url('posts') ?>">
                        <i class="bi bi-file-earmark-text sidebar-icon"></i><span>Post</span>
                        <?php if(isset($post_count) && $post_count > 0): ?>
                            <span class="nav-count"><?= $post_count ?></span>
                        <?php elseif($this->session->userdata('post_count') && $this->session->userdata('post_count') > 0): ?>
                            <span class="nav-count"><?= $this->session->userdata('post_count') ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php if ($this->session->userdata('isLoggedIn')): ?>
                    <li>
                        <a href="#commentSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <i class="bi bi-chat-left-text sidebar-icon"></i><span>Comments</span>
                        </a>
                        <ul class="collapse list-unstyled" id="commentSubmenu">
                            <li>
                                <a href="<?= site_url('comments/my-comments') ?>"><span>My Comments</span></a>
                            </li>
                            <li>
                                <a href="<?= site_url('comments/received') ?>"><span>Received Comments</span></a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>

            <?php if ($this->session->userdata('isLoggedIn')): ?>
                <div class="sidebar-footer">
                    <a href="<?= site_url('auth/logout') ?>" class="btn btn-outline-danger btn-sm w-100">
                        <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                    </a>
                </div>
            <?php else: ?>
                <div class="sidebar-footer">
                    <a href="<?= site_url('auth/login') ?>" class="btn btn-primary btn-sm w-100 mb-2">
                        <i class="bi bi-box-arrow-in-right"></i> <span>Login</span>
                    </a>
                    <a href="<?= site_url('auth/register') ?>" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-person-plus"></i> <span>Register</span>
                    </a>
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
                    <h4 class="m-0">POST</h4>

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
            // Check if we're on mobile
            const isMobile = function() {
                return window.innerWidth <= 768;
            };
            
            // Toggle sidebar with different behavior on mobile vs desktop
            $('#sidebarCollapse').on('click', function () {
                if (isMobile()) {
                    // On mobile, we'll still use the full hide/show behavior
                    $('#sidebar').toggleClass('active');
                    $('#content').toggleClass('active');
                    $('.overlay').toggleClass('active');
                } else {
                    // On desktop, toggle between full sidebar and icon-only sidebar
                    $('#sidebar').toggleClass('active');
                    $('#content').toggleClass('active');
                    // Don't show overlay on desktop
                    $('.overlay').removeClass('active');
                }
            });

            // Handle window resize
            $(window).resize(function() {
                if (!isMobile()) {
                    // Remove mobile-specific classes if window is resized larger
                    $('#sidebar').removeClass('mobile');
                    $('#content').removeClass('mobile');
                    $('.overlay').removeClass('active');
                }
            });

            // Handle overlay click (mobile only)
            $('.overlay').on('click', function () {
                $('#sidebar').removeClass('active');
                $('#content').removeClass('active');
                $('.overlay').removeClass('active');
            });

            // Handle collapsible menu items
            $('#sidebar .dropdown-toggle').on('click', function (e) {
                e.preventDefault();
                // Only expand submenu if sidebar is not in collapsed state
                if (!$('#sidebar').hasClass('active')) {
                    $(this).parent().toggleClass('menu-open');
                    $(this).attr('aria-expanded', $(this).parent().hasClass('menu-open'));
                } else {
                    // If sidebar is collapsed, we should first expand it
                    $('#sidebar').removeClass('active');
                    $('#content').removeClass('active');
                    // Then open the submenu after a slight delay
                    setTimeout(() => {
                        $(this).parent().addClass('menu-open');
                        $(this).attr('aria-expanded', true);
                    }, 300);
                }
            });
        });
    </script>
</body>

</html>