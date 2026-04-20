// script.js
console.log('Seed Store JS loaded');

document.addEventListener('DOMContentLoaded', function() {
    // Confirm remove from cart
    const removeLinks = document.querySelectorAll('a[href*="remove="]');
    removeLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                e.preventDefault();
            }
        });
    });
});