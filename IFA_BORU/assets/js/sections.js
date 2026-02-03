/**
 * ============================================
 * DYNAMIC SECTION MANAGEMENT JAVASCRIPT
 * ============================================
 * Handles dynamic loading of sections based on grade selection
 */

// Load sections when grade is selected
function loadSections(gradeId, targetSelectId, selectedSectionId = null) {
    const sectionSelect = document.getElementById(targetSelectId);
    
    if (!sectionSelect) {
        console.error('Section select element not found:', targetSelectId);
        return;
    }
    
    // Clear existing options
    sectionSelect.innerHTML = '<option value="">Loading sections...</option>';
    sectionSelect.disabled = true;
    
    if (!gradeId) {
        sectionSelect.innerHTML = '<option value="">Select Grade First</option>';
        return;
    }
    
    // Fetch sections via AJAX
    fetch(`api/get_sections.php?grade_id=${gradeId}`)
        .then(response => response.json())
        .then(data => {
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            
            if (data.success && data.sections.length > 0) {
                data.sections.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.section_id;
                    option.textContent = `Section ${section.section_name}`;
                    
                    // Add student count if available
                    if (section.student_count !== undefined) {
                        option.textContent += ` (${section.student_count} students)`;
                    }
                    
                    // Select if this was the previously selected section
                    if (selectedSectionId && section.section_id == selectedSectionId) {
                        option.selected = true;
                    }
                    
                    sectionSelect.appendChild(option);
                });
            } else {
                sectionSelect.innerHTML = '<option value="">No sections available</option>';
            }
            
            sectionSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error loading sections:', error);
            sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
            sectionSelect.disabled = false;
        });
}

// Initialize section loading on page load
document.addEventListener('DOMContentLoaded', function() {
    // Find grade select elements and attach event listeners
    const gradeSelects = document.querySelectorAll('select[name="grade_id"], select[id*="grade"]');
    
    gradeSelects.forEach(gradeSelect => {
        gradeSelect.addEventListener('change', function() {
            const gradeId = this.value;
            
            // Find corresponding section select
            let sectionSelect = null;
            
            // Try different common naming patterns
            const possibleSectionIds = [
                'section_id',
                'section',
                this.id.replace('grade', 'section'),
                this.name.replace('grade_id', 'section_id')
            ];
            
            for (const id of possibleSectionIds) {
                sectionSelect = document.getElementById(id);
                if (sectionSelect) break;
            }
            
            if (sectionSelect) {
                loadSections(gradeId, sectionSelect.id);
            }
        });
        
        // Load sections if grade is already selected (for edit forms)
        if (gradeSelect.value) {
            const sectionSelect = document.getElementById('section_id');
            if (sectionSelect) {
                const selectedSection = sectionSelect.value;
                loadSections(gradeSelect.value, 'section_id', selectedSection);
            }
        }
    });
});

// Utility function to get available sections for a grade
function getAvailableSections(gradeId, callback) {
    fetch(`api/get_sections.php?grade_id=${gradeId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                callback(data.sections);
            } else {
                callback([]);
            }
        })
        .catch(error => {
            console.error('Error fetching sections:', error);
            callback([]);
        });
}

// Function to update section capacity display
function updateSectionCapacity(sectionId, displayElementId) {
    const displayElement = document.getElementById(displayElementId);
    if (!displayElement || !sectionId) return;
    
    // This would require an additional API endpoint to get section details
    // For now, we'll just show a loading message
    displayElement.textContent = 'Loading capacity...';
}

// Function to validate section capacity before enrollment
function validateSectionCapacity(sectionId, callback) {
    fetch(`api/get_section_details.php?section_id=${sectionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const available = data.section.max_students - data.section.student_count;
                callback(available > 0, available, data.section);
            } else {
                callback(false, 0, null);
            }
        })
        .catch(error => {
            console.error('Error validating section capacity:', error);
            callback(false, 0, null);
        });
}

// Export functions for use in other scripts
window.SectionManager = {
    loadSections,
    getAvailableSections,
    updateSectionCapacity,
    validateSectionCapacity
};