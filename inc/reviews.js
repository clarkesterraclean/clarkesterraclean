/**
 * Reviews Form Handler
 */
(function($) {
    'use strict';

    if (typeof CLARKES_REVIEWS === 'undefined') {
        return;
    }

    const form = document.getElementById('clarkes-review-form');
    if (!form) {
        return;
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        const submitText = form.querySelector('.submit-text');
        const errorMessages = form.querySelectorAll('.error-message');

        // Clear previous errors
        errorMessages.forEach(error => {
            error.textContent = '';
            error.classList.add('hidden');
        });

        // Disable submit button
        if (submitButton) {
            submitButton.disabled = true;
            if (submitText) {
                submitText.textContent = 'Submitting...';
            }
        }

        // Build FormData
        const formData = new FormData(form);
        formData.append('action', 'clarkes_submit_review');

        // Send via fetch
        fetch(CLARKES_REVIEWS.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success - show success message
                const successCard = document.createElement('div');
                successCard.className = 'bg-carbon-dark/60 border-l-4 border-eco-green p-6 rounded-lg text-text-body';
                successCard.setAttribute('role', 'status');
                successCard.setAttribute('aria-live', 'polite');
                successCard.innerHTML = '<p class="font-semibold text-eco-green">' + data.data.message + '</p>';

                form.parentNode.insertBefore(successCard, form);
                form.style.display = 'none';
            } else {
                // Errors - show inline
                if (data.data && data.data.errors) {
                    Object.keys(data.data.errors).forEach(field => {
                        const fieldElement = form.querySelector('[name="' + field + '"]');
                        if (fieldElement) {
                            const errorElement = fieldElement.parentNode.querySelector('.error-message');
                            if (errorElement) {
                                errorElement.textContent = data.data.errors[field];
                                errorElement.classList.remove('hidden');
                            }
                        } else if (field === 'form') {
                            // General form error
                            const formError = form.querySelector('.text-red-600.text-sm.mt-2');
                            if (formError) {
                                formError.textContent = data.data.errors[field];
                                formError.classList.remove('hidden');
                            }
                        }
                    });
                }

                // Re-enable submit button
                if (submitButton) {
                    submitButton.disabled = false;
                    if (submitText) {
                        submitText.textContent = 'Submit Review';
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const formError = form.querySelector('.text-red-600.text-sm.mt-2');
            if (formError) {
                formError.textContent = 'An error occurred. Please try again.';
                formError.classList.remove('hidden');
            }

            // Re-enable submit button
            if (submitButton) {
                submitButton.disabled = false;
                if (submitText) {
                    submitText.textContent = 'Submit Review';
                }
            }
        });
    });
})(jQuery);

