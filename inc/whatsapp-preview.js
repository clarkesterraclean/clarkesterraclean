/**
 * WhatsApp FAB Customizer Live Preview
 */
(function($) {
    'use strict';

    // Update enable/disable
    wp.customize('enable_whatsapp_fab', function(value) {
        value.bind(function(newval) {
            const fab = document.getElementById('clarkes-wa-fab');
            if (fab) {
                fab.style.display = newval ? 'block' : 'none';
            }
        });
    });

    // Update number and recompute hrefs
    function updateWhatsAppLinks() {
        const rawPhone = wp.customize('whatsapp_number').get();
        const cleanPhone = rawPhone.replace(/\D+/g, '');
        const pretext = wp.customize('whatsapp_pretext').get();
        const prefill = encodeURIComponent(pretext);
        const chatHref = 'https://wa.me/' + cleanPhone + '?text=' + prefill;
        const telHref = 'tel:' + cleanPhone;

        const chatLink = document.querySelector('#clarkes-wa-sheet a[href^="https://wa.me/"]');
        const callLink = document.querySelector('#clarkes-wa-sheet a[href^="tel:"]');
        const noscriptLink = document.querySelector('#clarkes-wa-fab noscript a');

        if (chatLink) {
            chatLink.href = chatHref;
        }
        if (callLink) {
            callLink.href = telHref;
            callLink.textContent = 'Call ' + rawPhone;
        }
        if (noscriptLink) {
            noscriptLink.href = chatHref;
        }
    }

    wp.customize('whatsapp_number', function(value) {
        value.bind(function() {
            updateWhatsAppLinks();
        });
    });

    wp.customize('whatsapp_pretext', function(value) {
        value.bind(function() {
            updateWhatsAppLinks();
        });
    });

    // Update position
    wp.customize('whatsapp_position', function(value) {
        value.bind(function(newval) {
            const fab = document.getElementById('clarkes-wa-fab');
            if (fab) {
                const offsetX = wp.customize('whatsapp_offset_x').get();
                const offsetY = wp.customize('whatsapp_offset_y').get();
                if (newval === 'bottom-right') {
                    fab.style.right = offsetX + 'px';
                    fab.style.left = 'auto';
                } else {
                    fab.style.left = offsetX + 'px';
                    fab.style.right = 'auto';
                }
                fab.style.bottom = offsetY + 'px';
            }
        });
    });

    // Update offsets
    wp.customize('whatsapp_offset_x', function(value) {
        value.bind(function(newval) {
            const fab = document.getElementById('clarkes-wa-fab');
            if (fab) {
                const position = wp.customize('whatsapp_position').get();
                if (position === 'bottom-right') {
                    fab.style.right = newval + 'px';
                } else {
                    fab.style.left = newval + 'px';
                }
            }
        });
    });

    wp.customize('whatsapp_offset_y', function(value) {
        value.bind(function(newval) {
            const fab = document.getElementById('clarkes-wa-fab');
            if (fab) {
                fab.style.bottom = newval + 'px';
            }
        });
    });

    // Update visibility
    wp.customize('whatsapp_show_desktop', function(value) {
        value.bind(function(newval) {
            const fab = document.getElementById('clarkes-wa-fab');
            if (fab) {
                // Toggle visibility class or inline style based on device detection
                // This is a simplified version - full detection would need more logic
                if (!newval && !wp_is_mobile()) {
                    fab.style.display = 'none';
                } else if (wp.customize('whatsapp_show_mobile').get() || newval) {
                    fab.style.display = 'block';
                }
            }
        });
    });

    wp.customize('whatsapp_show_mobile', function(value) {
        value.bind(function(newval) {
            const fab = document.getElementById('clarkes-wa-fab');
            if (fab) {
                if (!newval && wp_is_mobile()) {
                    fab.style.display = 'none';
                } else if (wp.customize('whatsapp_show_desktop').get() || newval) {
                    fab.style.display = 'block';
                }
            }
        });
    });

    wp.customize('whatsapp_show_scope', function(value) {
        value.bind(function(newval) {
            const fab = document.getElementById('clarkes-wa-fab');
            if (fab) {
                // Scope checking would need to be done server-side
                // This is a simplified preview - full implementation would require page context
                // For preview, we'll just show/hide based on current page type
                if (newval === 'front_only' && !window.location.pathname.match(/^\/?$/)) {
                    fab.style.display = 'none';
                } else if (newval === 'pages_only' && !window.location.pathname.match(/\/[a-z-]+\//)) {
                    fab.style.display = 'none';
                } else {
                    fab.style.display = 'block';
                }
            }
        });
    });

})(jQuery);

