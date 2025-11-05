/**
 * Clarke's TerraClean Theme JavaScript
 */

(function() {
    'use strict';
    
    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileNav = document.getElementById('mobile-nav');
    const menuIcon = document.getElementById('menu-icon');
    const closeIcon = document.getElementById('close-icon');
    
    if (mobileMenuToggle && mobileNav) {
        mobileMenuToggle.addEventListener('click', function() {
            const isHidden = mobileNav.classList.contains('hidden');
            
            if (isHidden) {
                // Open menu
                mobileNav.classList.remove('hidden');
                mobileNav.classList.add('block');
                menuIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
                mobileMenuToggle.setAttribute('aria-expanded', 'true');
            } else {
                // Close menu
                mobileNav.classList.add('hidden');
                mobileNav.classList.remove('block');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
            }
        });
        
        // Close mobile menu when clicking on a link
        const mobileLinks = mobileNav.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileNav.classList.add('hidden');
                mobileNav.classList.remove('block');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
            });
        });
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"], a.js-anchor[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                // Account for fixed header height (64px = h-16)
                const headerOffset = 64;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Contact form handler
    if (typeof CLARKES_CONTACT !== 'undefined') {
        const contactForms = document.querySelectorAll('[data-contact-form]');
        
        contactForms.forEach(form => {
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
                        submitText.textContent = 'Sending...';
                    }
                }
                
                // Build FormData
                const formData = new FormData(form);
                formData.append('action', 'clarkes_contact');
                
                // Send via fetch
                fetch(CLARKES_CONTACT.ajax_url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Success - show success message
                        const isDarkSection = form.closest('.bg-carbon-dark') !== null;
                        const successCard = document.createElement('div');
                        successCard.className = isDarkSection 
                            ? 'bg-carbon-dark/60 border-l-4 border-eco-green p-4 rounded text-text-body'
                            : 'bg-carbon-light/60 border-l-4 border-eco-green p-4 rounded text-text-dark';
                        successCard.innerHTML = '<p class="font-semibold">' + data.data.message + '</p>';
                        
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
                                    const formError = form.querySelector('.text-center .error-message');
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
                                submitText.textContent = 'Send Enquiry';
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const formError = form.querySelector('.text-center .error-message');
                    if (formError) {
                        formError.textContent = 'An error occurred. Please try again or call us directly.';
                        formError.classList.remove('hidden');
                    }
                    
                    // Re-enable submit button
                    if (submitButton) {
                        submitButton.disabled = false;
                        if (submitText) {
                            submitText.textContent = 'Send Enquiry';
                        }
                    }
                });
            });
        });
    }
})();
