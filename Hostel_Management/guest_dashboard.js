document.addEventListener('DOMContentLoaded', () => {
    // Sidebar menu active state
    const menuItems = document.querySelectorAll('.sidebar-menu li');
    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            menuItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');
        });
    });

    // Notification interaction
    const notificationIcon = document.querySelector('.notification-icon');
    notificationIcon.addEventListener('click', () => {
        // Future: Add notification dropdown or modal
        alert('You have 3 new notifications');
    });

    // Simple responsive sidebar toggle for mobile
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('mobile-open');
    }

    // Add hamburger menu for mobile view
    function addMobileMenu() {
        if (window.innerWidth <= 768) {
            const header = document.querySelector('.dashboard-header');
            const hamburger = document.createElement('div');
            hamburger.classList.add('hamburger-menu');
            hamburger.innerHTML = '☰';
            hamburger.addEventListener('click', toggleSidebar);
            header.insertBefore(hamburger, header.firstChild);
        }
    }

    // Initialize mobile menu
    addMobileMenu();
    window.addEventListener('resize', addMobileMenu);
});