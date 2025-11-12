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
    
    // ========================================
    // HEADER PREVIEW UPDATES
    // ========================================
    
    // Header Layout
    wp.customize('header_layout', function(value) {
        value.bind(function(newval) {
            var $header = $('#site-header');
            $header.removeClass('header-layout-default header-layout-centered header-layout-split header-layout-minimal');
            $header.addClass('header-layout-' + newval);
            $header.attr('data-header-layout', newval);
        });
    });
    
    // Header Height
    wp.customize('header_height', function(value) {
        value.bind(function(newval) {
            $('#site-header').css('height', newval + 'px');
            $('#site-header > div').css('height', newval + 'px');
            $('#header-spacer').css('height', newval + 'px');
        });
    });
    
    // Header Background Color
    wp.customize('header_bg_color', function(value) {
        value.bind(function(newval) {
            $('#site-header').css('background-color', newval);
        });
    });
    
    // Header Text Color
    wp.customize('header_text_color', function(value) {
        value.bind(function(newval) {
            $('#site-header').css('color', newval);
        });
    });
    
    // Header Link Color
    wp.customize('header_link_color', function(value) {
        value.bind(function(newval) {
            $('#site-header a, #site-header nav a, .header-logo').css('color', newval);
        });
    });
    
    // Header Link Hover Color
    wp.customize('header_link_hover_color', function(value) {
        value.bind(function(newval) {
            // Update CSS variable or add inline style
            var style = '<style id="header-hover-color">#site-header a:hover { color: ' + newval + ' !important; }</style>';
            $('#header-hover-color').remove();
            $('head').append(style);
        });
    });
    
    // Header Border Color
    wp.customize('header_border_color', function(value) {
        value.bind(function(newval) {
            $('#site-header').css('border-bottom-color', newval);
            $('#mobile-nav').css('border-top-color', newval);
        });
    });
    
    // Header Padding Vertical
    wp.customize('header_padding_vertical', function(value) {
        value.bind(function(newval) {
            $('#site-header').css({
                'padding-top': newval + 'px',
                'padding-bottom': newval + 'px'
            });
        });
    });
    
    // Header Padding Horizontal
    wp.customize('header_padding_horizontal', function(value) {
        value.bind(function(newval) {
            $('#site-header').css({
                'padding-left': newval + 'px',
                'padding-right': newval + 'px'
            });
        });
    });
    
    // Navigation Font Size
    wp.customize('header_nav_font_size', function(value) {
        value.bind(function(newval) {
            $('#site-header nav').css('font-size', newval + 'px');
        });
    });
    
    // Navigation Font Weight
    wp.customize('header_nav_font_weight', function(value) {
        value.bind(function(newval) {
            $('#site-header nav').css('font-weight', newval);
        });
    });
    
    // Navigation Letter Spacing
    wp.customize('header_nav_letter_spacing', function(value) {
        value.bind(function(newval) {
            $('#site-header nav').css('letter-spacing', newval + 'px');
        });
    });
    
    // Logo Size
    wp.customize('header_logo_size', function(value) {
        value.bind(function(newval) {
            var $logo = $('#site-header > div > div > a');
            $logo.removeClass('text-sm text-base text-lg text-xl text-2xl md:text-lg md:text-xl md:text-2xl');
            var sizeMap = {
                'small': 'text-sm',
                'medium': 'text-base md:text-lg',
                'large': 'text-lg md:text-xl',
                'xlarge': 'text-xl md:text-2xl'
            };
            if (sizeMap[newval]) {
                $logo.addClass(sizeMap[newval]);
            }
        });
    });
    
    // Mobile Menu Background Color
    wp.customize('mobile_menu_bg_color', function(value) {
        value.bind(function(newval) {
            $('#mobile-nav').css('background-color', newval);
        });
    });
    
    // Phone Button Style
    wp.customize('header_phone_button_style', function(value) {
        value.bind(function(newval) {
            var $button = $('.header-phone-button');
            if ($button.length === 0) return;
            
            // Remove all style classes
            $button.removeClass('bg-eco-green text-carbon-dark hover:bg-eco-green/90 border border-eco-green text-eco-green hover:bg-eco-green hover:text-carbon-dark hover:text-eco-green/80');
            
            // Add classes based on style
            switch(newval) {
                case 'solid':
                    $button.addClass('bg-eco-green text-carbon-dark hover:bg-eco-green/90');
                    $button.removeClass('border');
                    break;
                case 'text':
                    $button.addClass('text-eco-green hover:text-eco-green/80');
                    $button.removeClass('border');
                    break;
                case 'outline':
                default:
                    $button.addClass('border border-eco-green text-eco-green hover:bg-eco-green hover:text-carbon-dark');
                    break;
            }
            
            // Update data attribute
            $button.attr('data-button-style', newval);
        });
    });
    
    // ========================================
    // FOOTER PREVIEW UPDATES
    // ========================================
    
    // Footer Background Color
    wp.customize('footer_bg_color', function(value) {
        value.bind(function(newval) {
            $('footer, #site-footer').css('background-color', newval);
        });
    });
    
    // Footer Text Color
    wp.customize('footer_text_color', function(value) {
        value.bind(function(newval) {
            $('footer, #site-footer').css('color', newval);
        });
    });
    
    // Footer Link Color
    wp.customize('footer_link_color', function(value) {
        value.bind(function(newval) {
            $('#site-footer a, .footer-link, .footer-links a').css('color', newval);
        });
    });
    
    // Footer Link Hover Color
    wp.customize('footer_link_hover_color', function(value) {
        value.bind(function(newval) {
            var style = '<style id="footer-hover-color">#site-footer a:hover, .footer-link:hover, .footer-links a:hover { color: ' + newval + ' !important; }</style>';
            $('#footer-hover-color').remove();
            $('head').append(style);
        });
    });
    
    // Footer Border Color
    wp.customize('footer_border_color', function(value) {
        value.bind(function(newval) {
            $('#site-footer').css('border-top-color', newval);
            $('#site-footer .border-t').css('border-top-color', newval);
        });
    });
    
    // Footer Padding Top
    wp.customize('footer_padding_top', function(value) {
        value.bind(function(newval) {
            $('#site-footer').css('padding-top', newval + 'px');
        });
    });
    
    // Footer Padding Bottom
    wp.customize('footer_padding_bottom', function(value) {
        value.bind(function(newval) {
            $('#site-footer').css('padding-bottom', newval + 'px');
        });
    });
    
    // Footer Padding Horizontal
    wp.customize('footer_padding_horizontal', function(value) {
        value.bind(function(newval) {
            $('#site-footer').css({
                'padding-left': newval + 'px',
                'padding-right': newval + 'px'
            });
        });
    });
    
    // Footer Font Size
    wp.customize('footer_font_size', function(value) {
        value.bind(function(newval) {
            $('#site-footer').css('font-size', newval + 'px');
        });
    });
    
    // Footer Heading Font Size
    wp.customize('footer_heading_font_size', function(value) {
        value.bind(function(newval) {
            $('#site-footer h3, #site-footer h4').css('font-size', newval + 'px');
        });
    });
    
    // Footer Copyright Text
    wp.customize('footer_copyright_text', function(value) {
        value.bind(function(newval) {
            $('#site-footer .border-t .max-w-7xl').text(newval);
        });
    });
    
    // ========================================
    // TYPOGRAPHY PREVIEW UPDATES
    // ========================================
    
    // Body Font Size
    wp.customize('body_font_size', function(value) {
        value.bind(function(newval) {
            $('body').css('font-size', newval + 'px');
        });
    });
    
    // Body Line Height
    wp.customize('body_line_height', function(value) {
        value.bind(function(newval) {
            $('body').css('line-height', newval);
        });
    });
    
    // H1 Font Size
    wp.customize('h1_font_size', function(value) {
        value.bind(function(newval) {
            $('h1').css('font-size', newval + 'px');
        });
    });
    
    // H2 Font Size
    wp.customize('h2_font_size', function(value) {
        value.bind(function(newval) {
            $('h2').css('font-size', newval + 'px');
        });
    });
    
    // H3 Font Size
    wp.customize('h3_font_size', function(value) {
        value.bind(function(newval) {
            $('h3').css('font-size', newval + 'px');
        });
    });
    
    // Heading Font Weight
    wp.customize('heading_font_weight', function(value) {
        value.bind(function(newval) {
            $('h1, h2, h3, h4, h5, h6').css('font-weight', newval);
        });
    });
    
    // ========================================
    // BUTTON PREVIEW UPDATES
    // ========================================
    
    // Primary Button Background
    wp.customize('button_primary_bg', function(value) {
        value.bind(function(newval) {
            var style = '<style id="button-primary-bg">.border-eco-green, button[type="submit"], .bg-eco-green { background-color: ' + newval + ' !important; border-color: ' + newval + ' !important; }</style>';
            $('#button-primary-bg').remove();
            $('head').append(style);
        });
    });
    
    // Primary Button Text Color
    wp.customize('button_primary_text', function(value) {
        value.bind(function(newval) {
            var style = '<style id="button-primary-text">.border-eco-green, button[type="submit"], .bg-eco-green { color: ' + newval + ' !important; }</style>';
            $('#button-primary-text').remove();
            $('head').append(style);
        });
    });
    
    // Primary Button Hover Background
    wp.customize('button_primary_hover_bg', function(value) {
        value.bind(function(newval) {
            var style = '<style id="button-primary-hover">.border-eco-green:hover, button[type="submit"]:hover, .bg-eco-green:hover { background-color: ' + newval + ' !important; }</style>';
            $('#button-primary-hover').remove();
            $('head').append(style);
        });
    });
    
    // Button Font Size
    wp.customize('button_font_size', function(value) {
        value.bind(function(newval) {
            $('button, .border-eco-green, a[class*="rounded-full"]').css('font-size', newval + 'px');
        });
    });
    
    // Button Font Weight
    wp.customize('button_font_weight', function(value) {
        value.bind(function(newval) {
            $('button, .border-eco-green, a[class*="rounded-full"]').css('font-weight', newval);
        });
    });
    
    // Button Padding Vertical
    wp.customize('button_padding_vertical', function(value) {
        value.bind(function(newval) {
            $('button, .border-eco-green, a[class*="rounded-full"]').css({
                'padding-top': newval + 'px',
                'padding-bottom': newval + 'px'
            });
        });
    });
    
    // Button Padding Horizontal
    wp.customize('button_padding_horizontal', function(value) {
        value.bind(function(newval) {
            $('button, .border-eco-green, a[class*="rounded-full"]').css({
                'padding-left': newval + 'px',
                'padding-right': newval + 'px'
            });
        });
    });
    
    // ========================================
    // COLOR SCHEME UPDATES
    // ========================================
    
    // Update CSS variables for colors
    function updateCSSVariable(property, value) {
        document.documentElement.style.setProperty(property, value);
    }
    
    wp.customize('color_accent', function(value) {
        value.bind(function(newval) {
            updateCSSVariable('--accent', newval);
            updateCSSVariable('--eco-green', newval);
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
    
    // ========================================
    // LAYOUT UPDATES
    // ========================================
    
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
    
    // Hero Background Color
    wp.customize('hero_bg_color', function(value) {
        value.bind(function(newval) {
            if ($('#top').hasClass('hero-section')) {
                $('#top').css('background-color', newval);
            }
        });
    });
    
    // Section Spacing
    wp.customize('section_padding_vertical', function(value) {
        value.bind(function(newval) {
            $('section').css('padding-top', newval + 'px');
            $('section').css('padding-bottom', newval + 'px');
        });
    });
    
    wp.customize('hero_section_padding_top', function(value) {
        value.bind(function(newval) {
            if (newval !== '') {
                $('#top').css('padding-top', newval + 'px');
            }
        });
    });
    
    wp.customize('hero_section_padding_bottom', function(value) {
        value.bind(function(newval) {
            if (newval !== '') {
                $('#top').css('padding-bottom', newval + 'px');
            }
        });
    });
    
    wp.customize('about_section_padding_top', function(value) {
        value.bind(function(newval) {
            if (newval !== '') {
                $('#about').css('padding-top', newval + 'px');
            }
        });
    });
    
    wp.customize('about_section_padding_bottom', function(value) {
        value.bind(function(newval) {
            if (newval !== '') {
                $('#about').css('padding-bottom', newval + 'px');
            }
        });
    });
    
    wp.customize('services_section_padding_top', function(value) {
        value.bind(function(newval) {
            if (newval !== '') {
                $('#services').css('padding-top', newval + 'px');
            }
        });
    });
    
    wp.customize('services_section_padding_bottom', function(value) {
        value.bind(function(newval) {
            if (newval !== '') {
                $('#services').css('padding-bottom', newval + 'px');
            }
        });
    });
    
    wp.customize('case_studies_section_padding_top', function(value) {
        value.bind(function(newval) {
            if (newval !== '') {
                $('#case-studies').css('padding-top', newval + 'px');
            }
        });
    });
    
    wp.customize('case_studies_section_padding_bottom', function(value) {
        value.bind(function(newval) {
            if (newval !== '') {
                $('#case-studies').css('padding-bottom', newval + 'px');
            }
        });
    });
    
    wp.customize('testimonials_section_padding_top', function(value) {
        value.bind(function(newval) {
            if (newval !== '') {
                $('#testimonials').css('padding-top', newval + 'px');
            }
        });
    });
    
    wp.customize('testimonials_section_padding_bottom', function(value) {
        value.bind(function(newval) {
            if (newval !== '') {
                $('#testimonials').css('padding-bottom', newval + 'px');
            }
        });
    });
    
    wp.customize('contact_section_padding_top', function(value) {
        value.bind(function(newval) {
            if (newval !== '') {
                $('#contact').css('padding-top', newval + 'px');
            }
        });
    });
    
    wp.customize('contact_section_padding_bottom', function(value) {
        value.bind(function(newval) {
            if (newval !== '') {
                $('#contact').css('padding-bottom', newval + 'px');
            }
        });
    });
    
    // Global Section Padding (fallback for sections without individual settings)
    wp.customize('section_padding_vertical', function(value) {
        value.bind(function(newval) {
            // Only apply to sections that don't have individual settings
            $('section').each(function() {
                var $section = $(this);
                var sectionId = $section.attr('id');
                // Only apply if section doesn't have individual padding set
                if (!sectionId || (sectionId !== 'top' && sectionId !== 'about' && sectionId !== 'services' && sectionId !== 'case-studies' && sectionId !== 'testimonials' && sectionId !== 'contact')) {
                    $section.css({
                        'padding-top': newval + 'px',
                        'padding-bottom': newval + 'px'
                    });
                }
            });
        });
    });
    
    // Section Padding Horizontal
    wp.customize('section_padding_horizontal', function(value) {
        value.bind(function(newval) {
            $('section > div').css({
                'padding-left': newval + 'px',
                'padding-right': newval + 'px'
            });
        });
    });
    
    // Content Gap
    wp.customize('content_gap', function(value) {
        value.bind(function(newval) {
            $('.space-y-6, .space-y-8').css('gap', newval + 'px');
        });
    });
    
    // Grid Gap
    wp.customize('grid_gap', function(value) {
        value.bind(function(newval) {
            $('.grid').css('gap', newval + 'px');
        });
    });
    
})(jQuery);
