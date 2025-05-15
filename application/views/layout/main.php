<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : '' ?> | Project Keoji</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/keoji-styles.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/auth-styles.css'); ?>">


</head>
<body>
    <!-- Page loader -->
    <div class="page-loader">
        <div class="loader-container">
            <div class="loader-logo">Project Keoji</div>
            <div class="loader-dots">
                <div class="loader-dot"></div>
                <div class="loader-dot"></div>
                <div class="loader-dot"></div>
            </div>
        </div>
    </div>
    
    <!-- Background particles -->
    <div class="blog-particles"></div>
    
    <!-- Brand corner -->
    <a href="<?= site_url('/') ?>" class="brand-corner text-decoration-none">
        <div class="logo"><span class="logo-text">K</span></div>
        <div class="brand-name">Project Keoji</div>
    </a>
    
    <!-- Theme toggle button -->
    <button class="theme-toggle" title="Toggle Theme">
        <i class="fas fa-moon"></i>
    </button>
    
    <!-- Main content -->
    <div class="container main-content">
        <?= $content ?>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check for saved dark mode preference
        const isDarkMode = localStorage.getItem('darkMode') === 'enabled';
        
        // Apply dark mode if enabled
        if (isDarkMode) {
            enableDarkMode();
        }
        
        // Hide loader after page loads
        setTimeout(function() {
            const loader = document.querySelector('.page-loader');
            if (loader) {
                loader.style.opacity = '0';
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 500);
            }
        }, 1000);
        
        // Add floating particles
        const particles = document.querySelector('.blog-particles');
        if (particles) {
            for (let i = 0; i < 30; i++) {
                const particle = document.createElement('div');
                particle.className = 'floating-particle';
                
                const size = Math.random() * 10 + 5;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                particle.style.animationDelay = `${Math.random() * 10}s`;
                particle.style.animationDuration = `${Math.random() * 20 + 10}s`;
                
                particles.appendChild(particle);
            }
            
            // Add styles for particles
            const style = document.createElement('style');
            style.textContent = `
                .floating-particle {
                    position: absolute;
                    border-radius: 50%;
                    background: radial-gradient(circle at center, rgba(255, 71, 87, 0.8) 0%, rgba(255, 107, 129, 0.6) 50%, transparent 100%);
                    animation: float-particle 15s infinite ease-in-out;
                    pointer-events: none;
                    filter: blur(1px);
                }
                
                body.dark-theme .floating-particle {
                    background: radial-gradient(circle at center, rgba(255, 71, 87, 0.4) 0%, rgba(255, 107, 129, 0.3) 50%, transparent 100%);
                }
                
                @keyframes float-particle {
                    0%, 100% {
                        transform: translate(0, 0) scale(1);
                        opacity: 0.3;
                    }
                    25% {
                        transform: translate(50px, -100px) scale(1.2);
                        opacity: 0.6;
                    }
                    50% {
                        transform: translate(-50px, -50px) scale(0.8);
                        opacity: 0.4;
                    }
                    75% {
                        transform: translate(100px, 50px) scale(1.1);
                        opacity: 0.5;
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Theme toggle functionality
        const themeToggle = document.querySelector('.theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                const isDark = document.body.classList.contains('dark-theme');
                
                if (isDark) {
                    disableDarkMode();
                } else {
                    enableDarkMode();
                }
            });
        }
        
        // Function to enable dark mode
        function enableDarkMode() {
            document.body.classList.add('dark-theme');
            localStorage.setItem('darkMode', 'enabled');
            
            const toggleIcon = document.querySelector('.theme-toggle i');
            if (toggleIcon) {
                toggleIcon.classList.replace('fa-moon', 'fa-sun');
            }
        }
        
        // Function to disable dark mode
        function disableDarkMode() {
            document.body.classList.remove('dark-theme');
            localStorage.setItem('darkMode', 'disabled');
            
            const toggleIcon = document.querySelector('.theme-toggle i');
            if (toggleIcon) {
                toggleIcon.classList.replace('fa-sun', 'fa-moon');
            }
        }
        
        // Add ripple effect to buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.className = 'ripple';
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
        
        // Add ripple effect styles
        const rippleStyle = document.createElement('style');
        rippleStyle.textContent = `
            .btn {
                position: relative;
                overflow: hidden;
            }
            
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.5);
                transform: scale(0);
                animation: ripple-animation 0.6s ease-out;
                pointer-events: none;
            }
            
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(rippleStyle);
    });
    </script>
</body>
</html>