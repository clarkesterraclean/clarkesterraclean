/**
 * Enhanced Section Spacing Control JavaScript
 */

(function($) {
    'use strict';
    
    wp.customize.bind('ready', function() {
        
        // Handle input changes
        $(document).on('input change', '.spacing-input', function() {
            const $input = $(this);
            const setting = $input.data('setting');
            const value = $input.val();
            
            if (setting && wp.customize(setting)) {
                if (value === '' || value === null) {
                    wp.customize(setting).set('');
                } else {
                    const numValue = parseInt(value, 10);
                    if (!isNaN(numValue) && numValue >= 0) {
                        wp.customize(setting).set(numValue);
                    }
                }
            }
        });
        
        // Reset to global
        $(document).on('click', '.reset-global', function(e) {
            e.preventDefault();
            const globalValue = 64;
            wp.customize('section_padding_vertical_global').set(globalValue);
            $('.spacing-input[data-setting="section_padding_vertical_global"]').val(globalValue);
        });
        
        // Reset section to global
        $(document).on('click', '.reset-section', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const section = $btn.data('section');
            const $card = $btn.closest('.spacing-section-card');
            
            // Clear both top and bottom
            const topSetting = section + '_section_padding_top';
            const bottomSetting = section + '_section_padding_bottom';
            
            wp.customize(topSetting).set('');
            wp.customize(bottomSetting).set('');
            
            $card.find('.spacing-input[data-setting="' + topSetting + '"]').val('').trigger('change');
            $card.find('.spacing-input[data-setting="' + bottomSetting + '"]').val('').trigger('change');
            
            // Update display
            updateSectionDisplay($card, section);
        });
        
        // Link top and bottom paddings
        $(document).on('click', '.link-paddings', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const section = $btn.data('section');
            const $card = $btn.closest('.spacing-section-card');
            const topInput = $card.find('.spacing-input[data-setting="' + section + '_section_padding_top"]');
            const bottomInput = $card.find('.spacing-input[data-setting="' + section + '_section_padding_bottom"]');
            
            // Get current top value or global
            let topValue = topInput.val();
            if (!topValue || topValue === '') {
                topValue = wp.customize('section_padding_vertical_global').get() || 64;
            }
            
            // Set bottom to match top
            bottomInput.val(topValue).trigger('change');
            
            $btn.text('Linked ✓').addClass('linked');
            setTimeout(function() {
                $btn.text('Link Top & Bottom').removeClass('linked');
            }, 2000);
        });
        
        // Quick presets
        $(document).on('click', '.preset-btn', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const section = $btn.data('section');
            const value = parseInt($btn.data('value'), 10);
            const $card = $btn.closest('.spacing-section-card');
            
            const topSetting = section + '_section_padding_top';
            const bottomSetting = section + '_section_padding_bottom';
            
            // Set both to preset value
            wp.customize(topSetting).set(value);
            wp.customize(bottomSetting).set(value);
            
            $card.find('.spacing-input[data-setting="' + topSetting + '"]').val(value).trigger('change');
            $card.find('.spacing-input[data-setting="' + bottomSetting + '"]').val(value).trigger('change');
            
            // Visual feedback
            $btn.addClass('active');
            setTimeout(function() {
                $btn.removeClass('active');
            }, 500);
        });
        
        // Update section display when global changes
        wp.customize('section_padding_vertical_global', function(setting) {
            setting.bind(function(value) {
                $('.spacing-input[data-setting="section_padding_vertical_global"]').val(value);
                updateAllSections();
            });
        });
        
        // Update section displays when individual settings change
        const sections = ['hero', 'about', 'services', 'case_studies', 'testimonials', 'contact'];
        sections.forEach(function(section) {
            ['top', 'bottom'].forEach(function(side) {
                const settingName = section + '_section_padding_' + side;
                if (wp.customize(settingName)) {
                    wp.customize(settingName, function(setting) {
                        setting.bind(function(value) {
                            const $card = $('.spacing-section-card[data-section="' + section + '"]');
                            updateSectionDisplay($card, section);
                        });
                    });
                }
            });
        });
        
        // Update section display
        function updateSectionDisplay($card, section) {
            if (!$card.length) return;
            
            const globalValue = wp.customize('section_padding_vertical_global').get() || 64;
            
            ['top', 'bottom'].forEach(function(side) {
                const settingName = section + '_section_padding_' + side;
                const value = wp.customize(settingName).get();
                const $input = $card.find('.spacing-input[data-setting="' + settingName + '"]');
                const $status = $input.closest('label').find('.using-global, .custom-value');
                
                if (value === '' || value === null) {
                    $input.attr('placeholder', globalValue);
                    $status.removeClass('custom-value').addClass('using-global').text('Using global (' + globalValue + 'px)');
                } else {
                    $input.attr('placeholder', globalValue);
                    $status.removeClass('using-global').addClass('custom-value').text('Custom value');
                }
            });
        }
        
        // Update all sections
        function updateAllSections() {
            const sections = ['hero', 'about', 'services', 'case_studies', 'testimonials', 'contact'];
            sections.forEach(function(section) {
                const $card = $('.spacing-section-card[data-section="' + section + '"]');
                updateSectionDisplay($card, section);
            });
        }
        
        // Initialize displays
        setTimeout(updateAllSections, 100);
    });
    
})(jQuery);

