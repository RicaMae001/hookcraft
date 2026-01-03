// public/js/footer-handler.js
(function() {
    'use strict';
    
    let clickCount = 0;
    let resetTimer = null;
    const requiredClicks = 5;
    const resetDelay = 2000; // Reset after 2 seconds of inactivity
    
    const trigger = document.getElementById('staffLoginTrigger');
    
    if (trigger) {
        trigger.addEventListener('click', function() {
            clickCount++;
            
            // Add visual feedback
            this.classList.add('clicked');
            setTimeout(() => {
                this.classList.remove('clicked');
            }, 300);
            
            // Clear existing reset timer
            if (resetTimer) {
                clearTimeout(resetTimer);
            }
            
            // Check if reached required clicks
            if (clickCount >= requiredClicks) {
                // Redirect to staff login
                window.location.href = '/staff/login';
                clickCount = 0; // Reset for next time
            } else {
                // Set timer to reset click count
                resetTimer = setTimeout(function() {
                    clickCount = 0;
                }, resetDelay);
            }
        });
    }
})();