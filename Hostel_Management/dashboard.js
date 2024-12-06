// Top Navigation Interactions
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    }

    // Close sidebar when clicking outside
    document.addEventListener('click', (e) => {
        if (sidebar.classList.contains('active') && 
            !sidebar.contains(e.target) && 
            !sidebarToggle.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    });

    // Search Functionality
    const searchInput = document.querySelector('.search-bar input');
    const searchResults = document.querySelector('.search-results');
    
    if (searchInput) {
        searchInput.addEventListener('focus', () => {
            searchResults.style.display = 'block';
        });

        searchInput.addEventListener('blur', (e) => {
            // Small delay to allow for result clicking
            setTimeout(() => {
                searchResults.style.display = 'none';
            }, 200);
        });

        // Basic search functionality
        searchInput.addEventListener('input', (e) => {
            // Add your search logic here
            console.log('Searching for:', e.target.value);
        });
    }

  
});