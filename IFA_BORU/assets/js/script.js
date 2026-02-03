/**
 * ============================================
 * IFA BORU AMURU SMS - JavaScript Functions
 * ============================================
 */

// Confirm delete actions
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
}

// Load sections based on grade selection
function loadSections(gradeId, targetSelectId = 'section_id') {
    if (gradeId) {
        fetch('get_sections.php?grade_id=' + gradeId)
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById(targetSelectId);
                select.innerHTML = '<option value="">Select Section</option>';
                data.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.section_id;
                    option.textContent = 'Section ' + section.section_name;
                    select.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading sections:', error);
            });
    }
}

// Show loading spinner
function showLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = '<div class="loading"></div>';
    }
}

// Hide loading spinner
function hideLoading(elementId, originalContent) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = originalContent;
    }
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// Form validation helpers
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^(\+251|0)?[19]\d{8}$/;
    return re.test(phone);
}

// Print functionality
function printPage() {
    window.print();
}

// Search functionality
function performSearch(searchTerm, targetUrl) {
    if (searchTerm.length > 2) {
        window.location.href = targetUrl + '?search=' + encodeURIComponent(searchTerm);
    }
}

// Mobile menu toggle (if needed)
function toggleMobileMenu() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.toggle('mobile-open');
    }
}