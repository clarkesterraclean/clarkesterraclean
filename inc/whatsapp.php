<?php
/**
 * WhatsApp Floating Button System
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sanitize Integer (must be defined before use in settings)
 */
if (!function_exists('clarkes_sanitize_int')) {
    function clarkes_sanitize_int($input) {
        return absint($input);
    }
}

/**
 * Register WhatsApp Customizer Settings
 */
if (!function_exists('clarkes_register_whatsapp_settings')) {
function clarkes_register_whatsapp_settings($wp_customize) {
    // Check if panel exists first
    if (!$wp_customize->get_panel('clarkes_theme_options')) {
        return; // Panel not registered yet, skip
    }
    
    // Add section to existing panel
    $wp_customize->add_section('clarkes_whatsapp', array(
        'title'    => esc_html__('Contact & WhatsApp', 'clarkes-terraclean'),
        'panel'    => 'clarkes_theme_options',
        'priority' => 80,
    ));

    // Enable WhatsApp FAB
    $wp_customize->add_setting('enable_whatsapp_fab', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('enable_whatsapp_fab', array(
        'label'       => esc_html__('Enable WhatsApp Floating Button', 'clarkes-terraclean'),
        'description' => esc_html__('Show a floating WhatsApp contact button on the site', 'clarkes-terraclean'),
        'section'     => 'clarkes_whatsapp',
        'type'        => 'checkbox',
    ));

    // WhatsApp Number
    $wp_customize->add_setting('whatsapp_number', array(
        'default'           => '07706 230867',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('whatsapp_number', array(
        'label'       => esc_html__('WhatsApp Number', 'clarkes-terraclean'),
        'description' => esc_html__('Phone number for WhatsApp and call (displayed as entered)', 'clarkes-terraclean'),
        'section'     => 'clarkes_whatsapp',
        'type'        => 'text',
    ));

    // WhatsApp Pre-filled Text
    $wp_customize->add_setting('whatsapp_pretext', array(
        'default'           => 'Hi, I\'m interested in a DPF/engine service. Vehicle: [make/model], Location: [area].',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('whatsapp_pretext', array(
        'label'       => esc_html__('Pre-filled Message', 'clarkes-terraclean'),
        'description' => esc_html__('Message pre-filled when opening WhatsApp chat', 'clarkes-terraclean'),
        'section'     => 'clarkes_whatsapp',
        'type'        => 'textarea',
    ));

    // Position
    $wp_customize->add_setting('whatsapp_position', array(
        'default'           => 'bottom-right',
        'sanitize_callback' => function($input) {
            $choices = array('bottom-right', 'bottom-left');
            return in_array($input, $choices) ? $input : 'bottom-right';
        },
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('whatsapp_position', array(
        'label'   => esc_html__('Button Position', 'clarkes-terraclean'),
        'section' => 'clarkes_whatsapp',
        'type'    => 'select',
        'choices' => array(
            'bottom-right' => 'Bottom Right',
            'bottom-left'  => 'Bottom Left',
        ),
    ));

    // Offset X
    $wp_customize->add_setting('whatsapp_offset_x', array(
        'default'           => 20,
        'sanitize_callback' => 'clarkes_sanitize_int',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('whatsapp_offset_x', array(
        'label'       => esc_html__('Horizontal Offset (px)', 'clarkes-terraclean'),
        'description' => esc_html__('Distance from left/right edge', 'clarkes-terraclean'),
        'section'     => 'clarkes_whatsapp',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 200,
            'step' => 1,
        ),
    ));

    // Offset Y
    $wp_customize->add_setting('whatsapp_offset_y', array(
        'default'           => 20,
        'sanitize_callback' => 'clarkes_sanitize_int',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('whatsapp_offset_y', array(
        'label'       => esc_html__('Vertical Offset (px)', 'clarkes-terraclean'),
        'description' => esc_html__('Distance from bottom edge', 'clarkes-terraclean'),
        'section'     => 'clarkes_whatsapp',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 200,
            'step' => 1,
        ),
    ));

    // Show on Desktop
    $wp_customize->add_setting('whatsapp_show_desktop', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('whatsapp_show_desktop', array(
        'label'   => esc_html__('Show on Desktop', 'clarkes-terraclean'),
        'section' => 'clarkes_whatsapp',
        'type'    => 'checkbox',
    ));

    // Show on Mobile
    $wp_customize->add_setting('whatsapp_show_mobile', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('whatsapp_show_mobile', array(
        'label'   => esc_html__('Show on Mobile', 'clarkes-terraclean'),
        'section' => 'clarkes_whatsapp',
        'type'    => 'checkbox',
    ));

    // Show Scope
    $wp_customize->add_setting('whatsapp_show_scope', array(
        'default'           => 'all',
        'sanitize_callback' => function($input) {
            $choices = array('all', 'front_only', 'pages_only');
            return in_array($input, $choices) ? $input : 'all';
        },
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('whatsapp_show_scope', array(
        'label'   => esc_html__('Show On', 'clarkes-terraclean'),
        'section' => 'clarkes_whatsapp',
        'type'    => 'radio',
        'choices' => array(
            'all'        => 'All Pages',
            'front_only' => 'Front Page Only',
            'pages_only' => 'Static Pages Only',
        ),
    ));
}
}
add_action('customize_register', 'clarkes_register_whatsapp_settings');

/**
 * Render WhatsApp FAB
 */
if (!function_exists('clarkes_render_whatsapp_fab')) {
function clarkes_render_whatsapp_fab() {
    // Check if enabled (check options first, then theme mods)
    $enable_fab = get_option('enable_whatsapp_fab', get_theme_mod('enable_whatsapp_fab', 1));
    if (!$enable_fab) {
        return;
    }

    // Check device visibility
    $is_mobile = wp_is_mobile();
    $show_desktop = get_option('whatsapp_show_desktop', get_theme_mod('whatsapp_show_desktop', 1));
    $show_mobile = get_option('whatsapp_show_mobile', get_theme_mod('whatsapp_show_mobile', 1));

    if ($is_mobile && !$show_mobile) {
        return;
    }
    if (!$is_mobile && !$show_desktop) {
        return;
    }

    // Check scope - Default to all pages for better visibility
    $scope = get_option('whatsapp_show_scope', get_theme_mod('whatsapp_show_scope', 'all'));
    if ($scope === 'front_only' && !is_front_page()) {
        return;
    }
    if ($scope === 'pages_only' && !is_page() && !is_front_page()) {
        return;
    }

    // Get settings from options (fallback to theme mods)
    $raw_phone = get_option('whatsapp_number', get_theme_mod('whatsapp_number', '07706 230867'));
    $clean_phone = preg_replace('/\D+/', '', $raw_phone);
    
    // Convert UK numbers starting with 0 to international format (+44)
    // UK numbers: if starts with 0 and is 11 digits, convert to +44 format
    if (preg_match('/^0\d{10}$/', $clean_phone)) {
        $clean_phone = '44' . substr($clean_phone, 1);
    }
    // If already has country code, use as-is
    // Otherwise assume it's already in correct format
    
    $prefill = urlencode(get_option('whatsapp_pretext', get_theme_mod('whatsapp_pretext', 'Hi, I\'m interested in a DPF/engine service. Vehicle: [make/model], Location: [area].')));
    // WhatsApp API - use + prefix for better mobile compatibility
    $chat_href = 'https://wa.me/' . $clean_phone . '?text=' . $prefill;
    // Alternative format for better compatibility: https://api.whatsapp.com/send?phone=' . $clean_phone . '&text=' . $prefill;
    $tel_href = 'tel:' . $clean_phone;

    // Position
    $position = get_option('whatsapp_position', get_theme_mod('whatsapp_position', 'bottom-right'));
    $offset_x = absint(get_option('whatsapp_offset_x', get_theme_mod('whatsapp_offset_x', 20)));
    $offset_y = absint(get_option('whatsapp_offset_y', get_theme_mod('whatsapp_offset_y', 20)));
    
    // Get settings from options (fallback to theme mods)
    $chat_title = get_option('whatsapp_chat_title', 'Mark Clarke');
    $chat_subtitle = get_option('whatsapp_chat_subtitle', 'Usually replies within minutes');
    $button_size = get_option('whatsapp_button_size', 'medium');
    $button_color = get_option('whatsapp_button_color', '#25D366');
    
    // Calculate button size
    $size_map = array('small' => 50, 'medium' => 60, 'large' => 70);
    $button_size_px = isset($size_map[$button_size]) ? $size_map[$button_size] : 60;

    $position_style = '';
    if ($position === 'bottom-right') {
        $position_style = 'right: ' . $offset_x . 'px; bottom: ' . $offset_y . 'px;';
    } else {
        $position_style = 'left: ' . $offset_x . 'px; bottom: ' . $offset_y . 'px;';
    }

    // Enqueue and localize script
    wp_enqueue_script('jquery');
    
    // Enqueue CTA buttons script
    wp_enqueue_script(
        'clarkes-cta-buttons',
        get_template_directory_uri() . '/inc/cta-buttons.js',
        array(),
        filemtime(get_template_directory() . '/inc/cta-buttons.js'),
        true
    );
    
    wp_add_inline_script('jquery', 'var clarkesWhatsApp = ' . wp_json_encode(array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('clarkes_whatsapp_chat'),
        'phone' => $clean_phone,
        'raw_phone' => $raw_phone,
        'chat_url' => $chat_href,
        'tel_url' => $tel_href,
    )) . ';', 'before');
    
    // Get settings from options (fallback to theme mods)
    $chat_title = get_option('whatsapp_chat_title', 'Mark Clarke');
    $chat_subtitle = get_option('whatsapp_chat_subtitle', 'Usually replies within minutes');
    $button_size = get_option('whatsapp_button_size', 'medium');
    $button_color = get_option('whatsapp_button_color', '#25D366');
    
    // Calculate button size
    $size_map = array('small' => 50, 'medium' => 60, 'large' => 70);
    $button_size_px = isset($size_map[$button_size]) ? $size_map[$button_size] : 60;
    
    ?>
    <!-- WhatsApp Floating Button -->
    <div id="clarkes-wa-fab" style="position: fixed; z-index: 99999; <?php echo esc_attr($position_style); ?>">
        <button id="clarkes-wa-toggle" class="clarkes-wa-button" type="button" style="width: <?php echo $button_size_px; ?>px; height: <?php echo $button_size_px; ?>px; border-radius: 50%; background: linear-gradient(135deg, <?php echo esc_attr($button_color); ?> 0%, #128C7E 100%); border: none; color: white; cursor: pointer; box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4); display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; pointer-events: auto;" aria-expanded="false" aria-controls="clarkes-wa-sheet" aria-label="Open WhatsApp contact options">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
        </button>
        <div id="clarkes-wa-sheet" class="clarkes-wa-sheet" style="display: none; margin-top: 10px; width: 280px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); background: white; border: 1px solid #e5e7eb; overflow: hidden;">
            <button id="clarkes-wa-chat-btn" class="clarkes-wa-action-btn" style="width: 100%; padding: 14px 16px; background: white; border: none; border-bottom: 1px solid #e5e7eb; text-align: left; cursor: pointer; display: flex; align-items: center; gap: 12px; transition: background 0.2s;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="#25D366">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                <span style="font-weight: 600; color: #1f2937;">Chat on WhatsApp</span>
            </button>
            <button id="clarkes-wa-call-btn" class="clarkes-wa-action-btn" style="width: 100%; padding: 14px 16px; background: white; border: none; text-align: left; cursor: pointer; display: flex; align-items: center; gap: 12px; transition: background 0.2s;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="#3b82f6">
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                </svg>
                <span style="font-weight: 600; color: #1f2937;">Call <?php echo esc_html($raw_phone); ?></span>
            </button>
        </div>
    </div>
    
    <!-- Live Chat Window -->
    <div id="clarkes-wa-chat-window" style="display: none; position: fixed; bottom: 90px; right: 20px; width: 380px; max-width: calc(100vw - 40px); height: 600px; max-height: calc(100vh - 120px); background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); z-index: 10000; flex-direction: column; overflow: hidden;">
        <div style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); padding: 20px; color: white; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="margin: 0; font-size: 18px; font-weight: 600;"><?php echo esc_html($chat_title); ?></h3>
                <p style="margin: 4px 0 0 0; font-size: 12px; opacity: 0.9;"><?php echo esc_html($chat_subtitle); ?></p>
            </div>
            <button id="clarkes-wa-close-chat" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.2s;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </button>
        </div>
        <div id="clarkes-wa-messages" style="flex: 1; overflow-y: auto; padding: 20px; background: #f0f2f5;">
            <div style="text-align: center; color: #6b7280; font-size: 14px; margin-top: 20px;">
                <p>Start a conversation with Mark</p>
            </div>
        </div>
        <div id="clarkes-wa-attachments" style="display: none; padding: 10px 20px; background: white; border-top: 1px solid #e5e7eb; max-height: 150px; overflow-y: auto;">
            <div id="clarkes-wa-attachment-list" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>
        </div>
        <div style="padding: 16px; background: white; border-top: 1px solid #e5e7eb;">
            <div style="display: flex; gap: 8px; align-items: flex-end;">
                <button id="clarkes-wa-attach-btn" style="background: #f3f4f6; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#6b7280">
                        <path d="M9 16h6v-6h4l-7-7-7 7h4zm-4 2h14v2H5z"/>
                    </svg>
                </button>
                <input type="file" id="clarkes-wa-file-input" multiple accept="image/*,video/*" style="display: none;">
                <textarea id="clarkes-wa-message-input" placeholder="Type a message..." style="flex: 1; border: 1px solid #e5e7eb; border-radius: 20px; padding: 10px 16px; resize: none; font-family: inherit; font-size: 14px; min-height: 40px; max-height: 120px;" rows="1"></textarea>
                <button id="clarkes-wa-send-btn" style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); border: none; width: 40px; height: 40px; border-radius: 50%; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Customer Info Form Modal -->
    <div id="clarkes-customer-info-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 10003; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 16px; padding: 24px; max-width: 450px; width: 90%; margin: 20px; max-height: 90vh; overflow-y: auto;">
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
    </div>
    
    <!-- Call/Message Options Modal -->
    <div id="clarkes-wa-call-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 10001; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 16px; padding: 24px; max-width: 400px; width: 90%; margin: 20px;">
            <h3 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 600; color: #1f2937;">Contact Mark</h3>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <a id="clarkes-wa-call-direct" href="<?php echo esc_url($tel_href); ?>" style="display: flex; align-items: center; gap: 12px; padding: 14px; background: #f3f4f6; border-radius: 8px; text-decoration: none; color: #1f2937; transition: background 0.2s;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#3b82f6">
                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                    </svg>
                    <div>
                        <div style="font-weight: 600;">Call Now</div>
                        <div style="font-size: 12px; color: #6b7280;"><?php echo esc_html($raw_phone); ?></div>
                    </div>
                </a>
                <button id="clarkes-wa-message-direct" style="display: flex; align-items: center; gap: 12px; padding: 14px; background: #f3f4f6; border: none; border-radius: 8px; text-align: left; cursor: pointer; color: #1f2937; transition: background 0.2s;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#25D366">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    <div>
                        <div style="font-weight: 600;">Message on WhatsApp</div>
                        <div style="font-size: 12px; color: #6b7280;">Open WhatsApp chat</div>
                    </div>
                </button>
            </div>
            <button id="clarkes-wa-close-modal" style="margin-top: 16px; width: 100%; padding: 12px; background: #f3f4f6; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; color: #6b7280;">Cancel</button>
        </div>
    </div>
    
    <style>
    .clarkes-wa-button:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.5) !important;
    }
    .clarkes-wa-action-btn:hover {
        background: #f9fafb !important;
    }
    #clarkes-wa-messages::-webkit-scrollbar {
        width: 6px;
    }
    #clarkes-wa-messages::-webkit-scrollbar-track {
        background: transparent;
    }
    #clarkes-wa-messages::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    </style>
    
    <script>
    (function() {
        'use strict';
        
        // Customer info storage
        let customerInfo = null;
        let pendingAction = null; // 'whatsapp' or 'chat'
        
        // Wait for DOM to be ready
        function initWhatsApp() {
            const toggle = document.getElementById('clarkes-wa-toggle');
            const sheet = document.getElementById('clarkes-wa-sheet');
            const chatBtn = document.getElementById('clarkes-wa-chat-btn');
            const callBtn = document.getElementById('clarkes-wa-call-btn');
            const chatWindow = document.getElementById('clarkes-wa-chat-window');
            const callModal = document.getElementById('clarkes-wa-call-modal');
            const closeChat = document.getElementById('clarkes-wa-close-chat');
            const closeModal = document.getElementById('clarkes-wa-close-modal');
            const messageInput = document.getElementById('clarkes-wa-message-input');
            const sendBtn = document.getElementById('clarkes-wa-send-btn');
            const attachBtn = document.getElementById('clarkes-wa-attach-btn');
            const fileInput = document.getElementById('clarkes-wa-file-input');
            const attachmentsDiv = document.getElementById('clarkes-wa-attachments');
            const attachmentList = document.getElementById('clarkes-wa-attachment-list');
            const messagesDiv = document.getElementById('clarkes-wa-messages');
            const messageDirectBtn = document.getElementById('clarkes-wa-message-direct');
            const customerInfoModal = document.getElementById('clarkes-customer-info-modal');
            const customerInfoForm = document.getElementById('clarkes-customer-info-form');
            const customerInfoCancel = document.getElementById('clarkes-customer-info-cancel');
            
            // Check if elements exist
            if (!toggle || !sheet) {
                console.warn('WhatsApp button elements not found', {
                    toggle: !!toggle,
                    sheet: !!sheet,
                    toggleId: toggle ? toggle.id : 'N/A',
                    sheetId: sheet ? sheet.id : 'N/A'
                });
                return;
            }
            
            console.log('WhatsApp button initialized successfully', {
                toggle: toggle.id,
                sheet: sheet.id,
                chatUrl: typeof clarkesWhatsApp !== 'undefined' ? clarkesWhatsApp.chat_url : 'N/A'
            });
            
            let attachments = [];
        
        // Detect WhatsApp
        function hasWhatsApp() {
            const ua = navigator.userAgent || navigator.vendor || window.opera;
            return /android|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(ua.toLowerCase());
        }
        
        function closeSheet() {
            if (sheet) {
                sheet.style.display = 'none';
                console.log('Sheet closed');
            }
            if (toggle) toggle.setAttribute('aria-expanded', 'false');
        }
        
        function openSheet() {
            if (sheet) {
                sheet.style.display = 'block';
                console.log('Sheet opened');
            }
            if (toggle) toggle.setAttribute('aria-expanded', 'true');
        }
        
        function closeChatWindow() {
            if (chatWindow) chatWindow.style.display = 'none';
        }
        
        function openChatWindow() {
            if (chatWindow) {
                chatWindow.style.display = 'flex';
                if (messageInput) {
                    messageInput.focus();
                }
                // Pre-fill message with customer info if available
                if (customerInfo && messageInput) {
                    const prefillMessage = formatCustomerMessage();
                    messageInput.value = prefillMessage;
                }
            }
        }
        
        function formatCustomerMessage() {
            if (!customerInfo) return '';
            return `Hi Mark,\n\nThis enquiry has come from your website.\n\nName: ${customerInfo.name}\nLocation: ${customerInfo.location}\nVehicle Registration: ${customerInfo.registration}\n\nI'm interested in a DPF/engine service.`;
        }
        
        function formatWhatsAppURL(baseUrl, customerInfo) {
            if (!customerInfo) return baseUrl;
            const message = formatCustomerMessage();
            const encodedMessage = encodeURIComponent(message);
            // Remove existing text parameter if any
            const cleanUrl = baseUrl.split('?')[0];
            return cleanUrl + '?text=' + encodedMessage;
        }
        
        function showCustomerInfoForm(action) {
            pendingAction = action;
            if (customerInfoModal) {
                customerInfoModal.style.display = 'flex';
                const nameInput = document.getElementById('customer-name');
                if (nameInput) nameInput.focus();
            }
        }
        
        function closeCustomerInfoForm() {
            if (customerInfoModal) {
                customerInfoModal.style.display = 'none';
                if (customerInfoForm) customerInfoForm.reset();
            }
            pendingAction = null;
        }
        
        function closeCallModal() {
            if (callModal) callModal.style.display = 'none';
        }
        
        function openCallModal() {
            if (callModal) callModal.style.display = 'flex';
        }
        
        // Toggle sheet
        if (toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('WhatsApp toggle clicked, sheet element:', sheet);
                if (sheet) {
                    const currentDisplay = window.getComputedStyle(sheet).display;
                    const isVisible = currentDisplay !== 'none';
                    console.log('Sheet current display:', currentDisplay, 'isVisible:', isVisible);
                    if (isVisible) {
                        closeSheet();
                    } else {
                        openSheet();
                    }
                } else {
                    console.warn('Sheet element not found');
                }
            });
        } else {
            console.warn('WhatsApp toggle button not found');
        }
        
        // Chat button - detect WhatsApp
        if (chatBtn) {
            chatBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Chat button clicked');
                closeSheet();
                
                // Show customer info form first
                showCustomerInfoForm('whatsapp');
            });
        } else {
            console.warn('Chat button not found');
        }
        
        // Handle customer info form submission
        if (customerInfoForm) {
            customerInfoForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const name = document.getElementById('customer-name').value.trim();
                const location = document.getElementById('customer-location').value.trim();
                const registration = document.getElementById('vehicle-registration').value.trim().toUpperCase();
                
                if (!name || !location || !registration) {
                    alert('Please fill in all fields.');
                    return;
                }
                
                // Store customer info
                customerInfo = {
                    name: name,
                    location: location,
                    registration: registration
                };
                
                closeCustomerInfoForm();
                
                // Proceed with pending action
                if (pendingAction === 'whatsapp') {
                    if (typeof clarkesWhatsApp !== 'undefined' && clarkesWhatsApp.chat_url) {
                        const chatUrl = formatWhatsAppURL(clarkesWhatsApp.chat_url, customerInfo);
                        console.log('Opening WhatsApp with customer info:', chatUrl);
                        
                        if (hasWhatsApp()) {
                            window.location.href = chatUrl;
                            setTimeout(function() {
                                if (document.hasFocus && !document.hasFocus()) {
                                    console.log('WhatsApp not opened, showing chat window');
                                    openChatWindow();
                                }
                            }, 2000);
                        } else {
                            openChatWindow();
                        }
                    } else {
                        openChatWindow();
                    }
                } else if (pendingAction === 'chat') {
                    openChatWindow();
                }
            });
        }
        
        if (customerInfoCancel) {
            customerInfoCancel.addEventListener('click', closeCustomerInfoForm);
        }
        
        // Close customer info modal on outside click
        if (customerInfoModal) {
            customerInfoModal.addEventListener('click', function(e) {
                if (e.target === customerInfoModal) {
                    closeCustomerInfoForm();
                }
            });
        }
        
        // Call button - show options
        if (callBtn) {
            callBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Call button clicked');
                closeSheet();
                openCallModal();
            });
        } else {
            console.warn('Call button not found');
        }
        
        // Message direct from modal
        if (messageDirectBtn) {
            messageDirectBtn.addEventListener('click', function(e) {
                e.preventDefault();
                closeCallModal();
                showCustomerInfoForm('whatsapp');
            });
        }
        
        // Close buttons
        if (closeChat) {
            closeChat.addEventListener('click', closeChatWindow);
        }
        if (closeModal) {
            closeModal.addEventListener('click', closeCallModal);
        }
        
        // Close on outside click
        document.addEventListener('click', function(e) {
            if (sheet && toggle && !toggle.contains(e.target) && !sheet.contains(e.target)) {
                closeSheet();
            }
            if (callModal && callModal === e.target) {
                closeCallModal();
            }
        });
        
        // File attachment
        if (attachBtn && fileInput) {
            attachBtn.addEventListener('click', function() {
                fileInput.click();
            });
            
            fileInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                files.forEach(function(file) {
                    if (file.size > 25 * 1024 * 1024) {
                        alert('File too large. Maximum size is 25MB.');
                        return;
                    }
                    
                    attachments.push(file);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.style.cssText = 'position: relative; width: 80px; height: 80px; border-radius: 8px; overflow: hidden; background: #f3f4f6;';
                        
                        if (file.type.startsWith('image/')) {
                            div.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;"><button class="remove-attach" style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,0.6);color:white;border:none;width:20px;height:20px;border-radius:50%;cursor:pointer;font-size:12px;">×</button>';
                        } else if (file.type.startsWith('video/')) {
                            div.innerHTML = '<video src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;"></video><button class="remove-attach" style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,0.6);color:white;border:none;width:20px;height:20px;border-radius:50%;cursor:pointer;font-size:12px;">×</button>';
                        }
                        
                        const removeBtn = div.querySelector('.remove-attach');
                        removeBtn.addEventListener('click', function() {
                            attachments = attachments.filter(f => f !== file);
                            div.remove();
                            if (attachments.length === 0) {
                                attachmentsDiv.style.display = 'none';
                            }
                        });
                        
                        attachmentList.appendChild(div);
                        attachmentsDiv.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                });
                });
            });
        }
        
        // Send message
        if (sendBtn && messageInput) {
            function sendMessage() {
                const message = messageInput.value.trim();
                if (!message && attachments.length === 0) return;
                
                // Add user message to chat
                if (message) {
                    addMessage(message, 'user');
                }
                
                // Send via AJAX
                const formData = new FormData();
                formData.append('action', 'clarkes_send_whatsapp_message');
                formData.append('nonce', clarkesWhatsApp.nonce);
                
                // Include customer info in message if available
                let fullMessage = message;
                if (customerInfo) {
                    const customerInfoText = `\n\n---\nEnquiry from website:\nName: ${customerInfo.name}\nLocation: ${customerInfo.location}\nVehicle Registration: ${customerInfo.registration}`;
                    fullMessage = message + customerInfoText;
                }
                
                formData.append('message', fullMessage);
                if (customerInfo) {
                    formData.append('customer_name', customerInfo.name);
                    formData.append('customer_location', customerInfo.location);
                    formData.append('vehicle_registration', customerInfo.registration);
                }
                attachments.forEach(function(file, index) {
                    formData.append('attachments[]', file);
                });
                
                // Show sending indicator
                addMessage('Sending...', 'system');
                
                fetch(clarkesWhatsApp.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Remove sending indicator
                    const systemMsgs = messagesDiv.querySelectorAll('.system-message');
                    if (systemMsgs.length > 0) {
                        systemMsgs[systemMsgs.length - 1].remove();
                    }
                    
                    if (data.success) {
                        addMessage('Message sent! Mark will reply soon.', 'system');
                        messageInput.value = '';
                        attachments = [];
                        attachmentList.innerHTML = '';
                        attachmentsDiv.style.display = 'none';
                        fileInput.value = '';
                    } else {
                        addMessage('Failed to send. Please try again.', 'error');
                    }
                })
                .catch(error => {
                    const systemMsgs = messagesDiv.querySelectorAll('.system-message');
                    if (systemMsgs.length > 0) {
                        systemMsgs[systemMsgs.length - 1].remove();
                    }
                    addMessage('Error sending message. Please try again.', 'error');
                });
            }
            
            sendBtn.addEventListener('click', sendMessage);
            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
            
            // Auto-resize textarea
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            });
        }
        
        function addMessage(text, type) {
            const div = document.createElement('div');
            div.style.cssText = 'margin-bottom: 12px; display: flex; ' + (type === 'user' ? 'justify-content: flex-end;' : 'justify-content: flex-start;');
            
            const msgDiv = document.createElement('div');
            msgDiv.style.cssText = 'max-width: 75%; padding: 10px 14px; border-radius: 12px; ' + 
                (type === 'user' ? 'background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); color: white; border-bottom-right-radius: 4px;' : 
                 type === 'error' ? 'background: #fee2e2; color: #991b1b;' :
                 'background: white; color: #1f2937; border-bottom-left-radius: 4px;');
            msgDiv.textContent = text;
            
            div.appendChild(msgDiv);
            messagesDiv.appendChild(div);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
            
            if (type === 'system' || type === 'error') {
                div.classList.add('system-message');
            }
        }
        
        // Escape key to close
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (sheet && sheet.style.display !== 'none') closeSheet();
                if (chatWindow && chatWindow.style.display !== 'none') closeChatWindow();
                if (callModal && callModal.style.display !== 'none') closeCallModal();
            }
        });
        
        // Auto-open chat if enabled
        <?php 
        $auto_open = get_option('whatsapp_auto_open', 0);
        $auto_open_delay = get_option('whatsapp_auto_open_delay', 5);
        if ($auto_open) : 
        ?>
        setTimeout(function() {
            // Check if user hasn't interacted with the page
            if (!document.querySelector('.clarkes-wa-button:active') && !sessionStorage.getItem('clarkes_wa_opened')) {
                if (hasWhatsApp()) {
                    // Try WhatsApp first
                    window.open(clarkesWhatsApp.chat_url, '_blank');
                } else {
                    // Show chat window
                    openChatWindow();
                }
                sessionStorage.setItem('clarkes_wa_opened', 'true');
            }
        }, <?php echo absint($auto_open_delay) * 1000; ?>);
        <?php endif; ?>
        } // End initWhatsApp function
        
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initWhatsApp();
            });
        } else {
            // DOM is already ready - use setTimeout to ensure all scripts are loaded
            setTimeout(function() {
                console.log('Initializing WhatsApp button...');
                initWhatsApp();
            }, 100);
        }
    })();
    </script>
    <script>
    // Additional check - ensure button is clickable and works
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const toggle = document.getElementById('clarkes-wa-toggle');
            const sheet = document.getElementById('clarkes-wa-sheet');
            
            if (toggle && sheet) {
                console.log('WhatsApp toggle button and sheet found');
                
                // Add a backup click handler that definitely works
                toggle.addEventListener('click', function(e) {
                    console.log('Backup click handler fired');
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const currentDisplay = window.getComputedStyle(sheet).display;
                    console.log('Current sheet display:', currentDisplay);
                    
                    if (currentDisplay === 'none' || currentDisplay === '') {
                        sheet.style.display = 'block';
                        toggle.setAttribute('aria-expanded', 'true');
                        console.log('Sheet opened via backup handler');
                    } else {
                        sheet.style.display = 'none';
                        toggle.setAttribute('aria-expanded', 'false');
                        console.log('Sheet closed via backup handler');
                    }
                }, true); // Use capture phase to catch early
            } else {
                console.error('WhatsApp elements NOT FOUND:', {
                    toggle: !!toggle,
                    sheet: !!sheet
                });
            }
        }, 500);
    });
    </script>
    <?php
}
}
add_action('wp_footer', 'clarkes_render_whatsapp_fab', 99);

/**
 * Enqueue Customizer Preview Script
 */
if (!function_exists('clarkes_whatsapp_preview_init')) {
function clarkes_whatsapp_preview_init() {
    wp_enqueue_script(
        'clarkes-whatsapp-preview',
        get_template_directory_uri() . '/inc/whatsapp-preview.js',
        array('customize-preview', 'jquery'),
        wp_get_theme()->get('Version'),
        true
    );
}
}
add_action('customize_preview_init', 'clarkes_whatsapp_preview_init');

/**
 * AJAX: Send WhatsApp Message
 */
if (!function_exists('clarkes_send_whatsapp_message')) {
function clarkes_send_whatsapp_message() {
    check_ajax_referer('clarkes_whatsapp_chat', 'nonce');
    
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
    $attachments = isset($_FILES['attachments']) ? $_FILES['attachments'] : array();
    
    if (empty($message) && empty($attachments)) {
        wp_send_json_error(array('message' => 'Message or attachment required'));
        return;
    }
    
    // Get customer info if provided
    $customer_name = isset($_POST['customer_name']) ? sanitize_text_field($_POST['customer_name']) : '';
    $customer_location = isset($_POST['customer_location']) ? sanitize_text_field($_POST['customer_location']) : '';
    $vehicle_registration = isset($_POST['vehicle_registration']) ? sanitize_text_field($_POST['vehicle_registration']) : '';
    
    $whatsapp_number = get_theme_mod('whatsapp_number', '07706 230867');
    $admin_email = get_option('admin_email');
    
    // Prepare email
    $subject = 'New WhatsApp Message from Website - ' . get_bloginfo('name');
    $body = "You have received a new message from your website:\n\n";
    $body .= "Message: " . $message . "\n\n";
    
    // Add customer info if available
    if ($customer_name || $customer_location || $vehicle_registration) {
        $body .= "---\n";
        $body .= "Customer Information:\n";
        if ($customer_name) {
            $body .= "Name: " . $customer_name . "\n";
        }
        if ($customer_location) {
            $body .= "Location: " . $customer_location . "\n";
        }
        if ($vehicle_registration) {
            $body .= "Vehicle Registration: " . $vehicle_registration . "\n";
        }
        $body .= "---\n\n";
    }
    
    $body .= "From: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Website') . "\n";
    $body .= "Time: " . current_time('mysql') . "\n\n";
    $body .= "Reply to this number on WhatsApp: " . $whatsapp_number . "\n";
    
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    
    // Handle attachments
    $attachment_files = array();
    if (!empty($attachments['name'][0])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        
        $upload_overrides = array('test_form' => false);
        
        foreach ($attachments['name'] as $key => $filename) {
            if ($attachments['error'][$key] === UPLOAD_ERR_OK) {
                $file = array(
                    'name' => $attachments['name'][$key],
                    'type' => $attachments['type'][$key],
                    'tmp_name' => $attachments['tmp_name'][$key],
                    'error' => $attachments['error'][$key],
                    'size' => $attachments['size'][$key]
                );
                
                $movefile = wp_handle_upload($file, $upload_overrides);
                
                if ($movefile && !isset($movefile['error'])) {
                    $attachment_files[] = $movefile['file'];
                    $body .= "\nAttachment: " . $filename . " (" . size_format($file['size']) . ")\n";
                }
            }
        }
    }
    
    // Send email
    $sent = wp_mail($admin_email, $subject, $body, $headers, $attachment_files);
    
    // Clean up attachment files
    foreach ($attachment_files as $file) {
        if (file_exists($file)) {
            @unlink($file);
        }
    }
    
    if ($sent) {
        wp_send_json_success(array('message' => 'Message sent successfully'));
    } else {
        wp_send_json_error(array('message' => 'Failed to send message'));
    }
}
}
add_action('wp_ajax_clarkes_send_whatsapp_message', 'clarkes_send_whatsapp_message');
add_action('wp_ajax_nopriv_clarkes_send_whatsapp_message', 'clarkes_send_whatsapp_message');

