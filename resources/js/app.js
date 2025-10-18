import './bootstrap';

import Chart from "chart.js/auto";

window.Chart = Chart;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Admin Sidebar Functionality
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    const sidebarToggleIcon = document.getElementById('sidebarToggleIcon');
    const mobileOverlay = document.getElementById('mobile-overlay');

    // Desktop sidebar collapse toggle
    window.toggleSidebarCollapse = function() {
        if (sidebar && mainContent) {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
            
            // Update toggle icon
            if (sidebarToggleIcon) {
                if (sidebar.classList.contains('collapsed')) {
                    sidebarToggleIcon.classList.remove('fa-bars');
                    sidebarToggleIcon.classList.add('fa-expand');
                } else {
                    sidebarToggleIcon.classList.remove('fa-expand');
                    sidebarToggleIcon.classList.add('fa-bars');
                }
            }
            
            // Save state to localStorage
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }
    };

    // Mobile sidebar toggle
    window.toggleSidebar = function() {
        if (sidebar) {
            sidebar.classList.toggle('open');
            if (mobileOverlay) {
                mobileOverlay.classList.toggle('show');
            }
        }
    };

    // Close mobile sidebar
    window.closeSidebar = function() {
        if (sidebar) {
            sidebar.classList.remove('open');
            if (mobileOverlay) {
                mobileOverlay.classList.remove('show');
            }
        }
    };

    // Load saved sidebar state
    if (sidebar && mainContent && localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('collapsed');
        if (sidebarToggleIcon) {
            sidebarToggleIcon.classList.remove('fa-bars');
            sidebarToggleIcon.classList.add('fa-expand');
        }
    }

    // Notification dropdown
    window.toggleNotifications = function() {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    };

    // User menu dropdown
    window.toggleUserMenu = function() {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    };

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const notificationDropdown = document.getElementById('notificationDropdown');
        const userDropdown = document.getElementById('userDropdown');
        const notificationBtn = document.querySelector('.notification-btn');
        const userBtn = document.querySelector('.user-menu-btn');

        // Close notification dropdown
        if (notificationDropdown && notificationBtn && !notificationBtn.contains(event.target) && !notificationDropdown.contains(event.target)) {
            notificationDropdown.classList.remove('show');
        }

        // Close user dropdown
        if (userDropdown && userBtn && !userBtn.contains(event.target) && !userDropdown.contains(event.target)) {
            userDropdown.classList.remove('show');
        }
    });

    // Auto-hide mobile sidebar on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeSidebar();
        }
    });
});
