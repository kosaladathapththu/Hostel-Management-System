document.addEventListener('DOMContentLoaded', () => {
    // Dynamic background gradient
    function updateBackgroundGradient(e) {
        const body = document.body;
        const x = e ? e.pageX / window.innerWidth : 0.5;
        const y = e ? e.pageY / window.innerHeight : 0.5;
        
        body.style.background = `linear-gradient(${135 + x * 50}deg, #f6d365 0%, #fda085 ${y * 100}%)`;
    }

    // Initial gradient
    updateBackgroundGradient();

    // Update gradient on mouse move
    window.addEventListener('mousemove', updateBackgroundGradient);

    // Card hover effects
    const dashboardCards = document.querySelectorAll('.dashboard-card');
    
    dashboardCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            // Optional: Add more interactive effects
            const iconWrapper = card.querySelector('.icon-wrapper');
            iconWrapper.style.transform = 'scale(1.1)';
        });

        card.addEventListener('mouseleave', () => {
            const iconWrapper = card.querySelector('.icon-wrapper');
            iconWrapper.style.transform = 'scale(1)';
        });
    });

    // Simple role-based hover highlight
    dashboardCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.boxShadow = '0 25px 50px rgba(0,0,0,0.2)';
        });

        card.addEventListener('mouseleave', () => {
            card.style.boxShadow = '0 15px 30px rgba(0,0,0,0.1)';
        });
    });

    // Console log for debugging and user interaction tracking
    dashboardCards.forEach(card => {
        card.addEventListener('click', (e) => {
            const role = card.getAttribute('data-role');
            console.log(`Accessing ${role} dashboard`);
            // You could add more tracking or analytics here
        });
    });
});