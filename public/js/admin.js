// Admin Dashboard JavaScript
let currentRole = 'admin';
let sidebarOpen = false;
let sidebarCollapsed = false;
let notificationOpen = false;
let userMenuOpen = false;

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
});

function initializeDashboard() {
    // Set current role from session or default
    const roleElement = document.getElementById('userRole');
    if (roleElement) {
        currentRole = roleElement.textContent;
    }
    
    // Restore sidebar collapsed state from localStorage
    const savedCollapsedState = localStorage.getItem('sidebarCollapsed');
    if (savedCollapsedState === 'true' && window.innerWidth >= 768) {
        sidebarCollapsed = true;
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');
        const toggleIcon = document.getElementById('sidebarToggleIcon');
        sidebar.classList.add('collapsed');
        mainContent.classList.add('collapsed');
        if (toggleIcon) {
            toggleIcon.className = 'fas fa-angle-right';
        }
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const isNotificationBtn = event.target.closest('.notification-btn');
        const isNotificationDropdown = event.target.closest('.notification-dropdown-content');
        
        if (!isNotificationBtn && !isNotificationDropdown) {
            closeNotifications();
        }
        
        const isUserBtn = event.target.closest('.user-menu-btn');
        const isUserDropdown = event.target.closest('.user-dropdown-content');
        
        if (!isUserBtn && !isUserDropdown) {
            closeUserMenu();
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            closeSidebar();
        } else {
            // Reset collapsed state on mobile
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('collapsed');
            sidebarCollapsed = false;
        }
    });
    
    // Initialize tooltips or other components here
    console.log('Dashboard initialized for role:', currentRole);
}

// Sidebar Functions
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-overlay');
    
    sidebarOpen = !sidebarOpen;
    
    if (sidebarOpen) {
        sidebar.classList.add('open');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    } else {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-overlay');
    
    sidebarOpen = false;
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
    document.body.style.overflow = '';
}

// Desktop Sidebar Collapse Functions
function toggleSidebarCollapse() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    const toggleIcon = document.getElementById('sidebarToggleIcon');
    
    console.log('Toggling sidebar collapse. Current state:', sidebarCollapsed);
    
    sidebarCollapsed = !sidebarCollapsed;
    
    if (sidebarCollapsed) {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('collapsed');
        if (toggleIcon) {
            toggleIcon.className = 'fas fa-angle-right';
        }
        localStorage.setItem('sidebarCollapsed', 'true');
        console.log('Sidebar collapsed');
    } else {
        sidebar.classList.remove('collapsed');
        mainContent.classList.remove('collapsed');
        if (toggleIcon) {
            toggleIcon.className = 'fas fa-bars';
        }
        localStorage.setItem('sidebarCollapsed', 'false');
        console.log('Sidebar expanded');
    }
    
    // Force redraw
    sidebar.style.display = 'none';
    sidebar.offsetHeight; // Trigger reflow
    sidebar.style.display = 'flex';
}


// Navigation Functions
function setActiveMenu(menu, element) {
    // Remove active class from all menu items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Add active class to clicked item
    if (element) {
        element.classList.add('active');
    }
    
    // Update page title
    const roleText = currentRole.charAt(0).toUpperCase() + currentRole.slice(1);
    const pageTitle = document.getElementById('pageTitle');
    if (pageTitle) {
        pageTitle.textContent = `${menu.charAt(0).toUpperCase() + menu.slice(1)} - ${roleText}`;
    }
    
    // Close mobile sidebar
    if (window.innerWidth < 768) {
        closeSidebar();
    }
}

// Notification Functions
function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    if (!dropdown) return;
    
    notificationOpen = !notificationOpen;
    
    if (notificationOpen) {
        dropdown.classList.add('show');
        closeUserMenu(); // Close other dropdowns
    } else {
        dropdown.classList.remove('show');
    }
}

function closeNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown) {
        dropdown.classList.remove('show');
        notificationOpen = false;
    }
}

// User Menu Functions
function toggleUserMenu() {
    const dropdown = document.getElementById('userDropdown');
    if (!dropdown) return;
    
    userMenuOpen = !userMenuOpen;
    
    if (userMenuOpen) {
        dropdown.classList.add('show');
        closeNotifications(); // Close other dropdowns
    } else {
        dropdown.classList.remove('show');
    }
}

function closeUserMenu() {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown) {
        dropdown.classList.remove('show');
        userMenuOpen = false;
    }
}

// Chart Functions
function setChartFilter(period, element) {
    // Remove active class from all buttons
    document.querySelectorAll('.chart-filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Add active class to clicked button
    element.classList.add('active');
    
    // Show loading state
    const chartContainer = document.getElementById('chartContainer');
    if (chartContainer) {
        chartContainer.innerHTML = `
            <i class="fas fa-spinner fa-spin"></i>
            <p style="color: #6b7280; margin-top: 8px;">Loading ${period} data...</p>
        `;
        
        // Simulate loading with timeout
        setTimeout(() => {
            chartContainer.innerHTML = `
                <i class="fas fa-chart-area"></i>
                <p style="color: #6b7280; margin-top: 8px;">Chart for ${period} period</p>
                <p style="color: #9ca3af; font-size: 12px; margin-top: 4px;">Data updated successfully</p>
            `;
        }, 1000);
    }
}

// Activity Functions
function refreshActivity() {
    const refreshBtn = document.querySelector('.refresh-btn i');
    if (refreshBtn) {
        refreshBtn.classList.add('fa-spin');
    }
    
    // Make AJAX request to refresh activities
    fetch(window.location.pathname + '/activities')
        .then(response => response.json())
        .then(data => {
            updateActivityList(data);
        })
        .catch(error => {
            console.error('Error refreshing activities:', error);
            showToast('Failed to refresh activities', 'error');
        })
        .finally(() => {
            setTimeout(() => {
                if (refreshBtn) {
                    refreshBtn.classList.remove('fa-spin');
                }
            }, 1000);
        });
}

function updateActivityList(activities) {
    const activityList = document.getElementById('activityList');
    if (!activityList || !activities) return;
    
    activityList.innerHTML = '';
    
    activities.forEach(activity => {
        const activityItem = createActivityItem(activity);
        activityList.appendChild(activityItem);
    });
}

function createActivityItem(activity) {
    const item = document.createElement('div');
    item.className = 'activity-item';
    
    item.innerHTML = `
        <div class="activity-icon ${activity.type}">
            <i class="fas ${activity.icon}"></i>
        </div>
        <div class="activity-content">
            <h4>${activity.title}</h4>
            <p>${activity.description}</p>
            <span class="activity-time">${activity.time}</span>
        </div>
    `;
    
    return item;
}

// Modal Functions
function openModal(type) {
    switch(type) {
        case 'addUser':
            openAddUserModal();
            break;
        case 'addProduct':
            openAddProductModal();
            break;
        case 'addCustomer':
            openAddCustomerModal();
            break;
        case 'updateStock':
            openUpdateStockModal();
            break;
        default:
            showToast(`Opening ${type} modal...`, 'info');
    }
}

function openAddUserModal() {
    // This would typically open a modal
    showToast('Add User modal would open here', 'info');
}

function openAddProductModal() {
    showToast('Add Product modal would open here', 'info');
}

function openAddCustomerModal() {
    showToast('Add Customer modal would open here', 'info');
}

function openUpdateStockModal() {
    showToast('Update Stock modal would open here', 'info');
}

// Help Functions
function openHelp() {
    // Open help in new window or modal
    const helpUrl = '/admin/help';
    window.open(helpUrl, '_blank', 'width=800,height=600');
}

// Utility Functions
function showLoading(message = 'Loading...') {
    // You could implement a loading overlay here
    console.log(message);
}

function hideLoading() {
    // Hide loading overlay
    console.log('Loading complete');
}

function showToast(message, type = 'success') {
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas fa-${getToastIcon(type)}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Style the toast
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${getToastColor(type)};
        color: white;
        padding: 12px 16px;
        border-radius: 8px;
        z-index: 1000;
        animation: slideInRight 0.3s ease-out;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    document.body.appendChild(toast);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

function getToastIcon(type) {
    switch(type) {
        case 'success': return 'check-circle';
        case 'error': return 'exclamation-circle';
        case 'warning': return 'exclamation-triangle';
        case 'info': return 'info-circle';
        default: return 'info-circle';
    }
}

function getToastColor(type) {
    switch(type) {
        case 'success': return '#10b981';
        case 'error': return '#ef4444';
        case 'warning': return '#f59e0b';
        case 'info': return '#3b82f6';
        default: return '#6b7280';
    }
}

// Statistics Functions
function updateStats(stats) {
    // Update stat values in the UI
    Object.keys(stats).forEach(key => {
        const element = document.querySelector(`[data-stat="${key}"]`);
        if (element) {
            element.textContent = stats[key];
        }
    });
}

// AJAX Helper Functions
function makeRequest(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };
    
    // Add CSRF token if available
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        defaultOptions.headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
    }
    
    const finalOptions = { ...defaultOptions, ...options };
    
    return fetch(url, finalOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        });
}

// Auto-refresh functionality
function startAutoRefresh() {
    // Refresh activities every 5 minutes
    setInterval(refreshActivity, 5 * 60 * 1000);
    
    // Refresh stats every 10 minutes
    setInterval(() => {
        makeRequest('/admin/stats')
            .then(data => {
                updateStats(data);
            })
            .catch(error => {
                console.error('Error refreshing stats:', error);
            });
    }, 10 * 60 * 1000);
}

// Start auto-refresh when page loads
document.addEventListener('DOMContentLoaded', startAutoRefresh);

// Keyboard shortcuts
document.addEventListener('keydown', function(event) {
    // Ctrl/Cmd + K to focus search (if search exists)
    if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
        event.preventDefault();
        const searchInput = document.querySelector('input[type="search"]');
        if (searchInput) {
            searchInput.focus();
        }
    }
    
    // Escape to close modals/dropdowns
    if (event.key === 'Escape') {
        closeNotifications();
        closeUserMenu();
        closeSidebar();
    }
});

// Export functions for global access
window.toggleSidebar = toggleSidebar;
window.closeSidebar = closeSidebar;
window.toggleSidebarCollapse = toggleSidebarCollapse;
window.setActiveMenu = setActiveMenu;
window.toggleNotifications = toggleNotifications;
window.toggleUserMenu = toggleUserMenu;
window.setChartFilter = setChartFilter;
window.refreshActivity = refreshActivity;
window.openModal = openModal;
window.openHelp = openHelp;
