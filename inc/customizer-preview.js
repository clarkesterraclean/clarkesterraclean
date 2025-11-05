/**
 * Customizer Live Preview
 */
(function($) {
    'use strict';
    
    // Update hero title
    wp.customize('hero_title', function(value) {
        value.bind(function(newval) {
            $('.hero-title, #top h1').text(newval);
        });
    });
    
    // Update hero subtitle
    wp.customize('hero_subtitle', function(value) {
        value.bind(function(newval) {
            $('.hero-subtitle, #top .hero-subtitle').text(newval);
        });
    });
    
    // Update brand tagline
    wp.customize('brand_tagline', function(value) {
        value.bind(function(newval) {
            $('.brand-tagline').text(newval);
        });
    });
    
    // Update business phone
    wp.customize('business_phone', function(value) {
        value.bind(function(newval) {
            $('a[href^="tel:"]').each(function() {
                var $this = $(this);
                var currentText = $this.text();
                // Update phone number in text and href
                if (currentText.match(/\d{5}\s?\d{6}/)) {
                    $this.text(currentText.replace(/\d{5}\s?\d{6}/, newval));
                }
                $this.attr('href', 'tel:' + newval.replace(/\s/g, ''));
            });
        });
    });
    
    // Update CSS variables for colors
    function updateCSSVariable(property, value) {
        document.documentElement.style.setProperty(property, value);
    }
    
    wp.customize('color_accent', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('--accent', newval);
        });
    });
    
    wp.customize('color_dark', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('--carbon-dark', newval);
        });
    });
    
    wp.customize('color_light', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('--carbon-light', newval);
        });
    });
    
    wp.customize('color_text_body', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('--text-body', newval);
        });
    });
    
    wp.customize('color_text_dark', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('--text-dark', newval);
        });
    });
    
    // Update container max width
    wp.customize('container_max', function(value) {
        value.bind(function(newval) {
            var widthMap = {
                '6xl': '72rem',
                '7xl': '80rem',
                'full': '100%'
            };
            updateCSSVariable('--container-max', widthMap[newval] || '80rem');
        });
    });
    
    // Update card radius
    wp.customize('card_roundness', function(value) {
        value.bind(function(newval) {
            var radiusMap = {
                'md': '.375rem',
                'lg': '.5rem',
                'xl': '.75rem',
                '2xl': '1rem'
            };
            updateCSSVariable('--card-radius', radiusMap[newval] || '.5rem');
        });
    });
    
    // Update hero height
    wp.customize('hero_height', function(value) {
        value.bind(function(newval) {
            $('#top').css('min-height', newval);
        });
    });
    
})(jQuery);

