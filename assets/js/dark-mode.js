
// dark-mode.js - Add this to your assets/js directory
$(document).ready(function() {
    // Initialize dark mode from localStorage
    if (localStorage.getItem('darkMode') === 'true') {
        $('body').addClass('dark-mode');
        updateThemeIcon(true);
        updateEditorTheme(true);
    }

    // Find the existing moon button in the navbar
    const themeToggleBtn = $('.navbar .btn-link .bi-moon-fill').parent();
    
    // Add sun icon for toggle functionality
    if (themeToggleBtn.length > 0) {
        // Add the sun icon (initially hidden via CSS)
        themeToggleBtn.addClass('theme-toggle-btn');
        
        if (themeToggleBtn.find('.bi-sun-fill').length === 0) {
            themeToggleBtn.append('<i class="bi bi-sun-fill text-warning"></i>');
        }
        
        // Add click event to toggle dark mode
        themeToggleBtn.on('click', function(e) {
            e.preventDefault();
            toggleDarkMode();
        });
    }
    
    // Setup observer for dynamic content
    setupDynamicContentObserver();
});

// Toggle dark mode function
function toggleDarkMode() {
    const isDarkMode = $('body').hasClass('dark-mode');
    
    if (isDarkMode) {
        // Switch to light mode
        $('body').removeClass('dark-mode');
        localStorage.setItem('darkMode', 'false');
        updateThemeIcon(false);
        updateEditorTheme(false);
    } else {
        // Switch to dark mode
        $('body').addClass('dark-mode');
        localStorage.setItem('darkMode', 'true');
        updateThemeIcon(true);
        updateEditorTheme(true);
    }
    
    // Dispatch an event for other scripts to react
    window.dispatchEvent(new CustomEvent('themeChanged', {
        detail: { darkMode: !isDarkMode }
    }));
}

// Update theme toggle icon appearance
function updateThemeIcon(isDarkMode) {
    const themeToggleBtn = $('.theme-toggle-btn');
    
    if (themeToggleBtn.length > 0) {
        if (isDarkMode) {
            // Rotate the button to indicate it's in dark mode
            themeToggleBtn.css('transform', 'rotate(180deg)');
        } else {
            // Reset the button to normal state
            themeToggleBtn.css('transform', 'rotate(0deg)');
        }
    }
}

// Update any editor instances to match theme
function updateEditorTheme(isDarkMode) {
    // Check if CKEditor exists
    if (typeof CKEDITOR !== 'undefined') {
        for (const name in CKEDITOR.instances) {
            const editor = CKEDITOR.instances[name];
            
            if (isDarkMode) {
                editor.setUiColor('#333333');
                
                // Try to update editor content area
                try {
                    const editorDocument = editor.document.$;
                    let darkStyle = editorDocument.getElementById('ckeditor-dark-mode');
                    
                    if (!darkStyle) {
                        darkStyle = editorDocument.createElement('style');
                        darkStyle.id = 'ckeditor-dark-mode';
                        darkStyle.textContent = `
                            body.cke_editable {
                                background-color: #333333 !important;
                                color: #e4e6eb !important;
                            }
                        `;
                        editorDocument.querySelector('head').appendChild(darkStyle);
                    }
                } catch (e) {
                    console.error('Could not update CKEditor theme:', e);
                }
            } else {
                editor.setUiColor('#f8f9fa');
                
                // Remove dark styling
                try {
                    const editorDocument = editor.document.$;
                    const darkStyle = editorDocument.getElementById('ckeditor-dark-mode');
                    
                    if (darkStyle) {
                        darkStyle.remove();
                    }
                } catch (e) {
                    console.error('Could not update CKEditor theme:', e);
                }
            }
        }
    }
}

// Watch for dynamic content changes
function setupDynamicContentObserver() {
    // Create a mutation observer for DOM changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                // Check if dark mode is enabled
                if ($('body').hasClass('dark-mode')) {
                    // Update editors in new content
                    updateEditorTheme(true);
                }
            }
        });
    });
    
    // Start observing the document body
    observer.observe(document.body, { childList: true, subtree: true });
    
    // Also listen for AJAX completions
    $(document).ajaxComplete(function() {
        if ($('body').hasClass('dark-mode')) {
            updateEditorTheme(true);
        }
    });
}