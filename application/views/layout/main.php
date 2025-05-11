<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : '' ?> | Project Keoji</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
    <style>
        /* Base styling */
        :root {
            --primary: #6d5bf1;
            --primary-light: #8a7cf5;
            --primary-dark: #4c6ef5;
            --accent: #ff7b54;
            --accent-light: #ffb26b;
            --text-dark: #333;
            --text-muted: #777;
            --bg-light: #f8f9fd;
            --bg-gradient: linear-gradient(135deg, #f8f9fd 0%, #eef1f9 100%);
            --white: #fff;
            --shadow-sm: 0 5px 15px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 10px 30px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 15px 35px rgba(0, 0, 0, 0.1);
            --radius-sm: 0.8rem;
            --radius-md: 1.5rem;
            
            /* Dark theme variables */
            --dark-bg: #1a1c25;
            --dark-bg-secondary: #282c34;
            --dark-bg-tertiary: #2d3748;
            --dark-border: #3a3f4b;
            --dark-text: #e0e0e0;
            --dark-text-muted: #a0a0a0;
            --dark-shadow-sm: 0 5px 15px rgba(0, 0, 0, 0.2);
            --dark-shadow-md: 0 10px 30px rgba(0, 0, 0, 0.25);
            --dark-shadow-lg: 0 15px 35px rgba(0, 0, 0, 0.3);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            position: relative;
            overflow-x: hidden;
            min-height: 100vh;
            color: var(--text-dark);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Animated background */
        .blog-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: var(--bg-gradient);
            overflow: hidden;
            transition: background 0.3s ease;
        }
        
        .blog-particles:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 30% 20%, rgba(109, 91, 241, 0.05) 0%, transparent 150px),
                radial-gradient(circle at 70% 65%, rgba(76, 110, 245, 0.05) 0%, transparent 150px);
            animation: pulse 15s infinite alternate;
            transition: background-image 0.3s ease;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.2); }
        }
        
        /* Content container */
        .main-content {
            position: relative;
            z-index: 1;
            padding: 2rem 0;
        }
        
        /* Brand icon in corner */
        .brand-corner {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            display: flex;
            align-items: center;
            opacity: 0.9;
            transition: all 0.3s ease;
        }
        
        .brand-corner:hover {
            opacity: 1;
        }
        
        .brand-corner .logo {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 700;
            font-size: 22px;
            box-shadow: 0 5px 15px rgba(108, 95, 241, 0.3);
            margin-right: 12px;
            position: relative;
            overflow: hidden;
            transform: rotate(-5deg);
        }
        
        .brand-corner .logo:before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 80%);
            top: -50%;
            left: -50%;
            transform: rotate(35deg);
        }
        
        .brand-corner .logo .logo-text {
            position: relative;
            z-index: 2;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            animation: pulse-subtle 2s infinite alternate;
        }
        
        @keyframes pulse-subtle {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }
        
        .brand-corner .brand-name {
            font-weight: 700;
            font-size: 18px;
            color: var(--primary);
            letter-spacing: 1px;
            text-transform: uppercase;
            background: linear-gradient(to right, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
        }
        
        .brand-corner .brand-name:after {
            content: '';
            position: absolute;
            width: 30px;
            height: 2px;
            background: linear-gradient(to right, var(--accent) 0%, var(--accent-light) 100%);
            bottom: -4px;
            left: 0;
            transition: all 0.3s ease;
        }
        
        .brand-corner:hover .brand-name:after {
            width: 100%;
        }
        
        /* Theme toggle button */
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-sm);
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }
        
        .theme-toggle:hover {
            transform: translateY(-3px);
            color: var(--primary);
            box-shadow: var(--shadow-md);
        }
        
        /* Hover effects */
        .hover-effect {
            position: relative;
            color: var(--primary);
            transition: all 0.3s ease;
        }
        
        .hover-effect:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: var(--primary);
            transition: all 0.3s ease;
        }
        
        .hover-effect:hover:after {
            width: 100%;
        }
        
        /* Card styling */
        .blog-card {
            border-radius: var(--radius-md);
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: var(--shadow-md);
            transition: all 0.5s ease;
            margin-bottom: 1.5rem;
        }
        
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        
        /* Animations for page transitions */
        .page-transition {
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Button styling */
        .btn-primary {
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            padding: 0.5rem 1.5rem;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(108, 95, 241, 0.4);
        }
        
        .btn-accent {
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
            border: none;
            color: white;
            transition: all 0.4s ease;
        }
        
        .btn-accent:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255, 123, 84, 0.4);
        }
        
        /* Page loader */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: all 0.5s ease;
        }
        
        .loader-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .loader-logo {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 20px;
            letter-spacing: 2px;
            position: relative;
            text-transform: uppercase;
            background: linear-gradient(45deg, var(--primary) 0%, var(--primary-dark) 50%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: pulse-scale 2s infinite alternate;
        }
        
        .loader-logo:after {
            content: '';
            position: absolute;
            width: 10px;
            height: 10px;
            background: var(--accent);
            border-radius: 50%;
            bottom: 5px;
            right: -15px;
            animation: blink 1.5s infinite;
        }
        
        .loader-dots {
            display: flex;
        }
        
        .loader-dot {
            width: 12px;
            height: 12px;
            margin: 0 5px;
            border-radius: 50%;
            background: var(--primary);
            animation: loader-bounce 0.8s infinite alternate;
        }
        
        .loader-dot:nth-child(2) {
            animation-delay: 0.2s;
            background: var(--primary-light);
        }
        
        .loader-dot:nth-child(3) {
            animation-delay: 0.4s;
            background: var(--primary-dark);
        }
        
        @keyframes loader-bounce {
            0% { transform: translateY(0); }
            100% { transform: translateY(-15px); }
        }
        
        @keyframes pulse-scale {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        
        /* Dark theme styles */
        body.dark-theme {
            color: var(--dark-text);
        }
        
        /* Adjust card styles in dark mode */
        body.dark-theme .card {
            background-color: var(--dark-bg-secondary);
            border-color: var(--dark-border);
            box-shadow: var(--dark-shadow-md);
        }
        
        body.dark-theme .card-header,
        body.dark-theme .card-footer {
            background-color: var(--dark-bg-secondary);
            border-color: var(--dark-border);
        }
        
        /* Form controls in dark mode */
        body.dark-theme .form-control,
        body.dark-theme .form-select {
            background-color: var(--dark-bg-tertiary);
            border-color: var(--dark-border);
            color: var(--dark-text);
        }
        
        body.dark-theme .form-floating label {
            color: var(--dark-text-muted);
        }
        
        /* Button styles in dark mode */
        body.dark-theme .btn-primary {
            box-shadow: 0 5px 15px rgba(70, 60, 150, 0.4);
        }
        
        body.dark-theme .btn-primary:hover {
            box-shadow: 0 8px 25px rgba(70, 60, 150, 0.5);
        }
        
        /* Theme toggle in dark mode */
        body.dark-theme .theme-toggle {
            background: var(--dark-bg-tertiary);
            color: var(--dark-text-muted);
            box-shadow: var(--dark-shadow-sm);
        }
        
        body.dark-theme .theme-toggle:hover {
            color: var(--primary);
            box-shadow: var(--dark-shadow-md);
        }
        
        /* Featured post in dark mode */
        body.dark-theme .featured-post {
            background-color: var(--dark-bg-secondary);
            box-shadow: var(--dark-shadow-md);
        }
        
        body.dark-theme .featured-post h4 {
            color: var(--dark-text);
        }
        
        body.dark-theme .featured-post p {
            color: var(--dark-text-muted);
        }
        
        /* Security question display in dark mode */
        body.dark-theme .question-display {
            background-color: var(--dark-bg-tertiary);
            border-color: var(--dark-border);
        }
        
        body.dark-theme .security-text {
            color: var(--dark-text);
        }
        
        /* Alert styling in dark mode */
        body.dark-theme .alert-danger {
            background-color: rgba(255, 92, 92, 0.15);
            color: #ff7a7a;
        }
        
        /* Text colors in dark mode */
        body.dark-theme .text-muted {
            color: var(--dark-text-muted) !important;
        }
        
        body.dark-theme .text-dark {
            color: var(--dark-text) !important;
        }
        
        /* Particles background in dark mode */
        body.dark-theme .blog-particles:before {
            background-image: 
                radial-gradient(circle at 30% 20%, rgba(109, 91, 241, 0.1) 0%, transparent 150px),
                radial-gradient(circle at 70% 65%, rgba(76, 110, 245, 0.1) 0%, transparent 150px);
        }
        
        /* Page loader in dark mode */
        body.dark-theme .page-loader {
            background: var(--dark-bg);
        }
        
        /* Blog card in dark mode */
        body.dark-theme .blog-card {
            background-color: var(--dark-bg-secondary);
        }
        
        /* Dark mode scrollbar */
        body.dark-theme::-webkit-scrollbar {
            width: 12px;
        }
        
        body.dark-theme::-webkit-scrollbar-track {
            background: var(--dark-bg);
        }
        
        body.dark-theme::-webkit-scrollbar-thumb {
            background-color: var(--dark-bg-tertiary);
            border-radius: 6px;
            border: 3px solid var(--dark-bg);
        }
        
        /* Transition styles for smoother theme changes */
        body, body * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        /* Responsiveness */
        @media (max-width: 768px) {
            .brand-corner .brand-name {
                display: none;
            }
        }
    </style>
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
    <div class="container main-content page-transition">
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
        }, 800);
        
        // Add particles to background
        const particles = document.querySelector('.blog-particles');
        if (particles) {
            // Create floating particles
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('span');
                particle.classList.add('particle');
                
                // Random styling
                particle.style.left = Math.random() * 100 + 'vw';
                particle.style.top = Math.random() * 100 + 'vh';
                particle.style.width = Math.random() * 5 + 3 + 'px';
                particle.style.height = particle.style.width;
                particle.style.opacity = Math.random() * 0.5;
                
                // Set particle color based on current theme
                if (isDarkMode) {
                    particle.style.backgroundColor = `rgba(${Math.floor(Math.random() * 70) + 50}, ${Math.floor(Math.random() * 70) + 50}, ${Math.floor(Math.random() * 120) + 50}, 0.7)`;
                } else {
                    particle.style.backgroundColor = `rgba(${Math.floor(Math.random() * 100) + 100}, ${Math.floor(Math.random() * 100) + 100}, ${Math.floor(Math.random() * 155) + 100}, 0.7)`;
                }
                
                // Set animation
                const duration = Math.random() * 20 + 10;
                particle.style.animation = `float ${duration}s infinite alternate`;
                
                particles.appendChild(particle);
            }
            
            // Add animation keyframes
            const style = document.createElement('style');
            style.textContent = `
                .particle {
                    position: absolute;
                    border-radius: 50%;
                    pointer-events: none;
                }
                
                @keyframes float {
                    0% {
                        transform: translate(0, 0) rotate(0deg);
                    }
                    100% {
                        transform: translate(${Math.random() * 100 - 50}px, ${Math.random() * 100 - 50}px) rotate(360deg);
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Theme toggle functionality
        const themeToggle = document.querySelector('.theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                // Check current state
                const isDark = document.body.classList.contains('dark-theme');
                
                if (isDark) {
                    disableDarkMode();
                } else {
                    enableDarkMode();
                }
            });
        }
        
        // Add animations to links
        document.querySelectorAll('a').forEach(link => {
            if (!link.classList.contains('brand-corner')) {
                link.classList.add('hover-effect');
            }
        });
        
        // Add input focus animations
        const inputs = document.querySelectorAll('.form-control, .form-select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
        
        // Function to enable dark mode
        function enableDarkMode() {
            // Add class to body
            document.body.classList.add('dark-theme');
            
            // Store preference
            localStorage.setItem('darkMode', 'enabled');
            
            // Update toggle icon
            const toggleIcon = document.querySelector('.theme-toggle i');
            if (toggleIcon) {
                toggleIcon.classList.replace('fa-moon', 'fa-sun');
            }
            
            // Set CSS variables for dark theme
            document.documentElement.style.setProperty('--bg-light', '#1a1c25');
            document.documentElement.style.setProperty('--bg-gradient', 'linear-gradient(135deg, #1a1c25 0%, #262a3c 100%)');
            document.documentElement.style.setProperty('--text-dark', '#e0e0e0');
            document.documentElement.style.setProperty('--text-muted', '#a0a0a0');
            
            // Update particles color if they exist
            document.querySelectorAll('.particle').forEach(particle => {
                particle.style.backgroundColor = `rgba(${Math.floor(Math.random() * 70) + 50}, ${Math.floor(Math.random() * 70) + 50}, ${Math.floor(Math.random() * 120) + 50}, 0.7)`;
            });
        }
        
        // Function to disable dark mode
        function disableDarkMode() {
            // Remove class from body
            document.body.classList.remove('dark-theme');
            
            // Remove from storage
            localStorage.setItem('darkMode', 'disabled');
            
            // Update toggle icon
            const toggleIcon = document.querySelector('.theme-toggle i');
            if (toggleIcon) {
                toggleIcon.classList.replace('fa-sun', 'fa-moon');
            }
            
            // Reset CSS variables for light theme
            document.documentElement.style.setProperty('--bg-light', '#f8f9fd');
            document.documentElement.style.setProperty('--bg-gradient', 'linear-gradient(135deg, #f8f9fd 0%, #eef1f9 100%)');
            document.documentElement.style.setProperty('--text-dark', '#333');
            document.documentElement.style.setProperty('--text-muted', '#777');
            
            // Update particles color if they exist
            document.querySelectorAll('.particle').forEach(particle => {
                particle.style.backgroundColor = `rgba(${Math.floor(Math.random() * 100) + 100}, ${Math.floor(Math.random() * 100) + 100}, ${Math.floor(Math.random() * 155) + 100}, 0.7)`;
            });
        }
    });
    </script>
</body>
</html>