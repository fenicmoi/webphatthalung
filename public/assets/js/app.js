/**
 * Modern Interactive Application Script (Vanilla JS + SPA utilities)
 * Handles Dark/Light mode switching, Asynchronous Fetch API with CSRF protection, and UI Toasts.
 */

const App = (function() {
    'use strict';

    // 1. Theme Manager (Dark/Light Mode)
    const ThemeManager = {
        init: function() {
            const savedTheme = localStorage.getItem('app_theme') || this.getSystemPreference();
            this.setTheme(savedTheme, false);
            
            const toggleBtn = document.getElementById('theme-toggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => this.toggle());
            }
        },
        getSystemPreference: function() {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        },
        setTheme: function(theme, notify = true) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('app_theme', theme);
            
            // Update Toggle Icon if applicable
            const toggleBtn = document.getElementById('theme-toggle');
            if (toggleBtn) {
                toggleBtn.innerHTML = theme === 'dark' ? '<i class="fa-solid fa-sun text-warning"></i>' : '<i class="fa-solid fa-moon text-indigo-500"></i>';
            }
            if (notify) {
                App.toast(`เปลี่ยนเป็น${theme === 'dark' ? 'โหมดกลางคืน 🌙' : 'โหมดกลางวัน ☀️'} เรียบร้อยแล้ว`, 'info');
            }
        },
        toggle: function() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const nextTheme = currentTheme === 'light' ? 'dark' : 'light';
            this.setTheme(nextTheme, true);
        }
    };

    // 2. Toast Notification Helper (No heavy dependencies required)
    const ToastManager = {
        show: function(message, type = 'success') {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            toast.className = 'modern-toast';

            // Select appropriate icon
            let iconHtml = '<i class="fa-solid fa-check-circle text-success" style="color: var(--accent-success); font-size: 1.4rem;"></i>';
            if (type === 'info') {
                iconHtml = '<i class="fa-solid fa-info-circle text-primary" style="color: var(--accent-primary); font-size: 1.4rem;"></i>';
            } else if (type === 'error' || type === 'danger') {
                iconHtml = '<i class="fa-solid fa-triangle-exclamation text-danger" style="color: var(--accent-danger); font-size: 1.4rem;"></i>';
            }

            toast.innerHTML = `
                <div>${iconHtml}</div>
                <div style="flex: 1; font-weight: 500; font-size: 0.95rem;">${message}</div>
            `;

            container.appendChild(toast);

            // Auto dismiss after 3.5 seconds
            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.35s forwards';
                setTimeout(() => toast.remove(), 350);
            }, 3500);
        }
    };

    // 3. Asynchronous Fetch API Helper with auto CSRF injection
    const FetchHelper = async function(url, options = {}) {
        options.headers = options.headers || {};
        options.headers['X-Requested-With'] = 'XMLHttpRequest';
        options.headers['Accept'] = 'application/json';

        // Check for CI4 CSRF token in HTML meta tag
        const csrfMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
        const csrfHeader = document.querySelector('meta[name="X-CSRF-HEADER"]');
        if (csrfMeta && csrfHeader) {
            options.headers[csrfHeader.content] = csrfMeta.content;
        }

        try {
            const response = await fetch(url, options);
            
            // Check if CSRF token returned in header for update
            const newCsrf = response.headers.get('X-CSRF-TOKEN');
            if (newCsrf && csrfMeta) {
                csrfMeta.content = newCsrf;
            }

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Fetch API Error:', error);
            App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อข้อมูล: ' + error.message, 'error');
            throw error;
        }
    };

    // Initialize application on DOM content loaded
    document.addEventListener('DOMContentLoaded', function() {
        ThemeManager.init();
        console.log('🚀 Modern CI4 Portal Application Initialized successfully!');
    });

    // Public API Methods
    return {
        theme: ThemeManager,
        toast: ToastManager.show,
        fetch: FetchHelper
    };
})();

// Assign globally for easy access
window.App = App;
