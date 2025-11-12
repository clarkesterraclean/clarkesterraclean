/**
 * CTA Buttons - Call/WhatsApp Options
 */

(function() {
    'use strict';
    
    // Customer info storage
    let customerInfo = null;
    let pendingAction = null;
    
    // Detect WhatsApp
    function hasWhatsApp() {
        const ua = navigator.userAgent || navigator.vendor || window.opera;
        return /android|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(ua.toLowerCase());
    }
    
    function formatCustomerMessage() {
        if (!customerInfo) return '';
        return `Hi Mark,\n\nThis enquiry has come from your website.\n\nName: ${customerInfo.name}\nLocation: ${customerInfo.location}\nVehicle Registration: ${customerInfo.registration}\n\nI'm interested in a DPF/engine service.`;
    }
    
    function formatWhatsAppURL(baseUrl, customerInfo) {
        if (!customerInfo) return baseUrl;
        const message = formatCustomerMessage();
        const encodedMessage = encodeURIComponent(message);
        const cleanUrl = baseUrl.split('?')[0];
        return cleanUrl + '?text=' + encodedMessage;
    }
    
    function showCustomerInfoFormForCTA(action, button) {
        // Check if customer info modal exists (from WhatsApp module)
        const customerInfoModal = document.getElementById('clarkes-customer-info-modal');
        if (!customerInfoModal) {
            // Create modal if it doesn't exist
            createCustomerInfoModal(action, button);
            return;
        }
        
        // Store button reference for later use
        window.clarkesPendingCTAButton = button;
        window.clarkesPendingCTAAction = action;
        
        // Show the modal
        customerInfoModal.style.display = 'flex';
        const nameInput = document.getElementById('customer-name');
        if (nameInput) nameInput.focus();
        
        // Attach handlers when modal is shown (critical!)
        attachCustomerInfoHandlersForCTA(action, button);
    }
    
    function attachCustomerInfoHandlersForCTA(action, button) {
        const modal = document.getElementById('clarkes-customer-info-modal');
        const cancelBtn = document.getElementById('clarkes-customer-info-cancel');
        const form = document.getElementById('clarkes-customer-info-form');
        const content = document.getElementById('clarkes-customer-info-content');
        
        if (!modal) return;
        
        // Remove old handlers by removing and re-adding listeners
        // Cancel button - use multiple event types
        if (cancelBtn) {
            // Ensure button type is 'button' not 'submit'
            cancelBtn.setAttribute('type', 'button');
            
            // Clone to remove all listeners
            const newCancelBtn = cancelBtn.cloneNode(true);
            newCancelBtn.setAttribute('type', 'button'); // Ensure type is set
            cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
            
            // Use onclick attribute
            newCancelBtn.setAttribute('onclick', 'event.preventDefault(); event.stopPropagation(); event.stopImmediatePropagation(); const m = document.getElementById("clarkes-customer-info-modal"); const f = document.getElementById("clarkes-customer-info-form"); if(m) m.style.display="none"; if(f) f.reset(); return false;');
            
            // Use mousedown (fires before click and form validation)
            newCancelBtn.addEventListener('mousedown', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('Cancel mousedown (CTA)');
                const modalEl = document.getElementById('clarkes-customer-info-modal');
                const formEl = document.getElementById('clarkes-customer-info-form');
                if (modalEl) {
                    modalEl.style.display = 'none';
                    if (formEl) formEl.reset();
                }
                customerInfo = null;
                pendingAction = null;
                return false;
            }, true);
            
            // Use click in capture phase
            newCancelBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('Cancel clicked (CTA - capture)');
                const modalEl = document.getElementById('clarkes-customer-info-modal');
                const formEl = document.getElementById('clarkes-customer-info-form');
                if (modalEl) {
                    modalEl.style.display = 'none';
                    if (formEl) formEl.reset();
                }
                customerInfo = null;
                pendingAction = null;
                return false;
            }, true);
            
            // Use click in bubble phase
            newCancelBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('Cancel clicked (CTA - bubble)');
                const modalEl = document.getElementById('clarkes-customer-info-modal');
                const formEl = document.getElementById('clarkes-customer-info-form');
                if (modalEl) {
                    modalEl.style.display = 'none';
                    if (formEl) formEl.reset();
                }
                customerInfo = null;
                pendingAction = null;
                return false;
            }, false);
        }
        
        // Backdrop click - remove old, add new
        const newModal = modal.cloneNode(false);
        while (modal.firstChild) {
            newModal.appendChild(modal.firstChild);
        }
        modal.parentNode.replaceChild(newModal, modal);
        
        newModal.addEventListener('click', function(e) {
            if (e.target === newModal) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('Backdrop clicked (CTA handler)');
                newModal.style.display = 'none';
                if (form) form.reset();
                customerInfo = null;
                pendingAction = null;
                return false;
            }
        }, true);
        
        // Content click - prevent bubbling
        if (content) {
            content.addEventListener('click', function(e) {
                e.stopPropagation();
            }, true);
        }
        
        // Form submission
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                handleCustomerInfoSubmit(action, button);
            }, true);
        }
    }
    
    function createCustomerInfoModal(action, button) {
        const modal = document.createElement('div');
        modal.id = 'clarkes-customer-info-modal';
        modal.style.cssText = 'display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 10003; align-items: center; justify-content: center;';
        
        modal.innerHTML = `
            <div id="clarkes-customer-info-content" style="background: white; border-radius: 16px; padding: 24px; max-width: 450px; width: 90%; margin: 20px; max-height: 90vh; overflow-y: auto; position: relative;">
                <h3 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 600; color: #1f2937;">Quick Contact Form</h3>
                <p style="margin: 0 0 20px 0; font-size: 14px; color: #6b7280;">Please provide a few details to help us assist you better:</p>
                <form id="clarkes-customer-info-form" style="display: flex; flex-direction: column; gap: 16px;">
                    <div>
                        <label for="customer-name" style="display: block; margin-bottom: 6px; font-size: 14px; font-weight: 500; color: #374151;">Your Name *</label>
                        <input type="text" id="customer-name" name="customer_name" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; font-family: inherit;" placeholder="Enter your name">
                    </div>
                    <div>
                        <label for="customer-location" style="display: block; margin-bottom: 6px; font-size: 14px; font-weight: 500; color: #374151;">Location *</label>
                        <input type="text" id="customer-location" name="customer_location" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; font-family: inherit;" placeholder="e.g., Maidstone, Kent">
                    </div>
                    <div>
                        <label for="vehicle-registration" style="display: block; margin-bottom: 6px; font-size: 14px; font-weight: 500; color: #374151;">Vehicle Registration *</label>
                        <input type="text" id="vehicle-registration" name="vehicle_registration" required style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; font-family: inherit; text-transform: uppercase;" placeholder="e.g., AB12 CDE" maxlength="10">
                    </div>
                    <div style="display: flex; gap: 12px; margin-top: 8px;">
                        <button type="button" id="clarkes-customer-info-cancel" style="flex: 1; padding: 12px; background: #f3f4f6; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; color: #6b7280; font-size: 14px;">Cancel</button>
                        <button type="submit" id="clarkes-customer-info-submit" style="flex: 1; padding: 12px; background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); border: none; border-radius: 8px; cursor: pointer; font-weight: 600; color: white; font-size: 14px;">Continue</button>
                    </div>
                </form>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        const form = modal.querySelector('#clarkes-customer-info-form');
        const cancelBtn = modal.querySelector('#clarkes-customer-info-cancel');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            handleCustomerInfoSubmit(action, button);
        });
        
        // Use document-level event delegation for reliability
        // This ensures handlers work even if modal is dynamically created
        const handleModalClick = function(e) {
            // Check if cancel button was clicked
            if (e.target && e.target.id === 'clarkes-customer-info-cancel') {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('Cancel button clicked (CTA - document delegation)');
                modal.style.display = 'none';
                const form = modal.querySelector('#clarkes-customer-info-form');
                if (form) form.reset();
                customerInfo = null;
                pendingAction = null;
                document.removeEventListener('click', handleModalClick, true);
                return false;
            }
            
            // Check if backdrop was clicked (modal itself, not its children)
            if (e.target === modal) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('Backdrop clicked (CTA - document delegation)');
                modal.style.display = 'none';
                const form = modal.querySelector('#clarkes-customer-info-form');
                if (form) form.reset();
                customerInfo = null;
                pendingAction = null;
                document.removeEventListener('click', handleModalClick, true);
                return false;
            }
        };
        
        // Attach handler when modal is shown
        document.addEventListener('click', handleModalClick, true);
        
        modal.style.display = 'flex';
        const nameInput = modal.querySelector('#customer-name');
        if (nameInput) nameInput.focus();
    }
    
    function handleCustomerInfoSubmit(action, button) {
        const name = document.getElementById('customer-name').value.trim();
        const location = document.getElementById('customer-location').value.trim();
        const registration = document.getElementById('vehicle-registration').value.trim().toUpperCase();
        
        if (!name || !location || !registration) {
            alert('Please fill in all fields.');
            return;
        }
        
        customerInfo = {
            name: name,
            location: location,
            registration: registration
        };
        
        const modal = document.getElementById('clarkes-customer-info-modal');
        if (modal) {
            modal.style.display = 'none';
            const form = document.getElementById('clarkes-customer-info-form');
            if (form) form.reset();
        }
        
        if (action === 'whatsapp') {
            const waUrl = button.getAttribute('data-wa-url');
            if (!waUrl) {
                console.warn('WhatsApp URL not found');
                return;
            }
            
            const formattedUrl = formatWhatsAppURL(waUrl, customerInfo);
            
            if (hasWhatsApp()) {
                window.location.href = formattedUrl;
            } else {
                // Open chat window if available
                if (typeof window.clarkesWhatsApp !== 'undefined' && document.getElementById('clarkes-wa-chat-window')) {
                    const chatWindow = document.getElementById('clarkes-wa-chat-window');
                    if (chatWindow) {
                        chatWindow.style.display = 'flex';
                        const messageInput = document.getElementById('clarkes-wa-message-input');
                        if (messageInput) {
                            messageInput.value = formatCustomerMessage();
                            messageInput.focus();
                        }
                    } else {
                        window.open(formattedUrl, '_blank');
                    }
                } else {
                    window.open(formattedUrl, '_blank');
                }
            }
        }
    }
    
    // Create modal for call options (phone or WhatsApp)
    function createCTAModal(phone, phoneClean, waUrl) {
        const modal = document.createElement('div');
        modal.id = 'clarkes-cta-modal';
        modal.style.cssText = 'display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 10002; align-items: center; justify-content: center;';
        
        // Remove text parameter from WhatsApp URL for calls (we want to open chat without pre-filled message)
        // This allows user to tap the call button in WhatsApp
        const waCallUrl = waUrl.split('?')[0]; // Remove any query parameters
        
        modal.innerHTML = `
            <div style="background: white; border-radius: 16px; padding: 24px; max-width: 400px; width: 90%; margin: 20px;">
                <h3 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 600; color: #1f2937;">Call ${phone}</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <a href="tel:${phoneClean}" style="display: flex; align-items: center; gap: 12px; padding: 14px; background: #f3f4f6; border-radius: 8px; text-decoration: none; color: #1f2937; transition: background 0.2s;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#3b82f6">
                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                        <div>
                            <div style="font-weight: 600;">Call via Phone</div>
                            <div style="font-size: 12px; color: #6b7280;">Use your cellular network</div>
                        </div>
                    </a>
                    <button id="cta-wa-call" style="display: flex; align-items: center; gap: 12px; padding: 14px; background: #f3f4f6; border: none; border-radius: 8px; text-align: left; cursor: pointer; color: #1f2937; transition: background 0.2s;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#25D366">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        <div>
                            <div style="font-weight: 600;">Call via WhatsApp</div>
                            <div style="font-size: 12px; color: #6b7280;">Tap call button in WhatsApp</div>
                        </div>
                    </button>
                </div>
                <button id="cta-close-modal" style="margin-top: 16px; width: 100%; padding: 12px; background: #f3f4f6; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; color: #6b7280;">Cancel</button>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close modal
        const closeBtn = modal.querySelector('#cta-close-modal');
        const waCallBtn = modal.querySelector('#cta-wa-call');
        
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
        
        // WhatsApp call button - opens WhatsApp without pre-filled message
        // User can then tap the call button in WhatsApp to make a voice/video call
        waCallBtn.addEventListener('click', function() {
            // Remove query parameters to avoid pre-filling a message
            // This opens WhatsApp chat cleanly, allowing user to tap call button
            if (hasWhatsApp()) {
                // On mobile, use location.href to open WhatsApp app
                window.location.href = waCallUrl;
            } else {
                // For desktop, open WhatsApp Web
                window.open(waCallUrl, '_blank');
            }
            modal.style.display = 'none';
        });
        
        return modal;
    }
    
    // Document-level handler for cancel button - must be BEFORE form handlers
    document.addEventListener('click', function(e) {
        // Check if cancel button was clicked
        if (e.target && e.target.id === 'clarkes-customer-info-cancel') {
            const modal = document.getElementById('clarkes-customer-info-modal');
            if (modal && modal.style.display !== 'none') {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('Cancel button clicked (document-level handler - CTA)');
                modal.style.display = 'none';
                const form = document.getElementById('clarkes-customer-info-form');
                if (form) form.reset();
                customerInfo = null;
                pendingAction = null;
                return false;
            }
        }
    }, true); // Capture phase - fires FIRST
    
    // Wait for DOM to be ready
    function initCTAButtons() {
        // Handle CTA buttons
        document.addEventListener('click', function(e) {
            const clickedBtn = e.target.closest('.clarkes-cta-button');
            if (!clickedBtn || !clickedBtn.id) return;
            
            // Handle Call CTA buttons
            if (clickedBtn.id.includes('call-cta')) {
                e.preventDefault();
                e.stopPropagation();
                const phone = clickedBtn.getAttribute('data-phone');
                const phoneClean = clickedBtn.getAttribute('data-phone-clean');
                const waUrl = clickedBtn.getAttribute('data-wa-url');
                
                let modal = document.getElementById('clarkes-cta-modal');
                if (!modal) {
                    modal = createCTAModal(phone, phoneClean, waUrl);
                }
                modal.style.display = 'flex';
                return;
            }
            
            // Handle WhatsApp CTA buttons
            if (clickedBtn.id.includes('whatsapp-cta')) {
                e.preventDefault();
                e.stopPropagation();
                
                // Show customer info form first
                showCustomerInfoFormForCTA('whatsapp', clickedBtn);
                return;
            }
        });
        
        // Escape key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('clarkes-cta-modal');
                if (modal && modal.style.display !== 'none') {
                    modal.style.display = 'none';
                }
            }
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCTAButtons);
    } else {
        // DOM is already ready
        initCTAButtons();
    }
})();

