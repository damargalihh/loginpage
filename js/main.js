/**
 * Hotspot UMPKU - Main JavaScript
 * Universitas Muhammadiyah Palangkaraya
 */

// Toggle mobile menu
function toggleMenu() {
    const navMenu = document.getElementById('navMenu');
    navMenu.classList.toggle('active');
}

// Toggle password visibility
function togglePassword(inputId = 'password', iconId = 'toggleIcon') {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Scroll to top functionality
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Show/hide scroll to top button
window.addEventListener('scroll', function() {
    const scrollTopBtn = document.getElementById('scrollTop');
    if (scrollTopBtn) {
        if (window.pageYOffset > 300) {
            scrollTopBtn.classList.add('show');
        } else {
            scrollTopBtn.classList.remove('show');
        }
    }
});

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const navMenu = document.getElementById('navMenu');
    const menuToggle = document.querySelector('.menu-toggle');
    
    if (navMenu && menuToggle) {
        if (!navMenu.contains(event.target) && !menuToggle.contains(event.target)) {
            navMenu.classList.remove('active');
        }
    }
});

// Form validation enhancement
document.addEventListener('DOMContentLoaded', function() {
    // Add floating label effect
    const formControls = document.querySelectorAll('.form-control');
    
    formControls.forEach(input => {
        // Add focus effect
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
            if (this.value) {
                this.parentElement.classList.add('has-value');
            } else {
                this.parentElement.classList.remove('has-value');
            }
        });
        
        // Check initial value
        if (input.value) {
            input.parentElement.classList.add('has-value');
        }
    });

    // NIM input - only numbers
    const nimInput = document.getElementById('nim');
    if (nimInput) {
        nimInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // Phone input - only numbers and +
    const phoneInput = document.getElementById('no_hp');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+]/g, '');
        });
    }

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    if (passwordInput && document.getElementById('registerForm')) {
        const strengthIndicator = document.createElement('div');
        strengthIndicator.className = 'password-strength';
        strengthIndicator.innerHTML = '<span class="strength-text"></span><div class="strength-bar"><div class="strength-fill"></div></div>';
        passwordInput.parentElement.parentElement.appendChild(strengthIndicator);
        
        passwordInput.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            updateStrengthIndicator(strength);
        });
    }
});

// Check password strength
function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 6) strength++;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    return strength;
}

// Update password strength indicator
function updateStrengthIndicator(strength) {
    const strengthText = document.querySelector('.strength-text');
    const strengthFill = document.querySelector('.strength-fill');
    
    if (!strengthText || !strengthFill) return;
    
    const levels = ['', 'Sangat Lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
    const colors = ['', '#dc3545', '#ff6b35', '#ffc107', '#28a745', '#20c997'];
    const widths = ['0%', '20%', '40%', '60%', '80%', '100%'];
    
    strengthText.textContent = levels[strength] || '';
    strengthText.style.color = colors[strength] || '';
    strengthFill.style.width = widths[strength] || '0%';
    strengthFill.style.background = colors[strength] || '';
}

// Toast notification
function showToast(message, type = 'info') {
    // Remove existing toast
    const existingToast = document.querySelector('.toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Create new toast
    const toast = document.createElement('div');
    toast.className = 'toast';
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };
    
    const colors = {
        success: 'var(--success-color)',
        error: 'var(--danger-color)',
        warning: 'var(--warning-color)',
        info: 'var(--info-color)'
    };
    
    toast.innerHTML = `
        <i class="${icons[type]}" style="color: ${colors[type]}; font-size: 1.2rem;"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Hide toast after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

// Loading spinner
function showLoading(element) {
    element.innerHTML = '<div class="spinner"></div>';
    element.disabled = true;
}

function hideLoading(element, originalContent) {
    element.innerHTML = originalContent;
    element.disabled = false;
}

// Confirm dialog
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Format date
function formatDate(dateString) {
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Check network status
function checkNetworkStatus() {
    if (navigator.onLine) {
        showToast('Koneksi internet tersedia', 'success');
    } else {
        showToast('Tidak ada koneksi internet', 'error');
    }
}

// Listen for network changes
window.addEventListener('online', () => {
    showToast('Koneksi internet kembali', 'success');
});

window.addEventListener('offline', () => {
    showToast('Koneksi internet terputus', 'error');
});

// Add CSS for password strength
const strengthCSS = `
    .password-strength {
        margin-top: 8px;
    }
    .strength-text {
        font-size: 0.8rem;
        margin-bottom: 4px;
        display: block;
    }
    .strength-bar {
        height: 4px;
        background: var(--gray-200);
        border-radius: 2px;
        overflow: hidden;
    }
    .strength-fill {
        height: 100%;
        border-radius: 2px;
        transition: all 0.3s ease;
    }
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = strengthCSS;
document.head.appendChild(styleSheet);

// Console welcome message
console.log('%c🌐 Hotspot UMPKU', 'color: #1a5f7a; font-size: 24px; font-weight: bold;');
console.log('%cUniversitas Muhammadiyah Palangkaraya', 'color: #6c757d; font-size: 14px;');
console.log('%c⚠️ Jangan pernah memasukkan kode apapun di sini!', 'color: #dc3545; font-size: 12px;');
