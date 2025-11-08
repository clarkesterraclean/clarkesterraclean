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
function clarkes_render_whatsapp_fab() {
    // Check if enabled
    if (!get_theme_mod('enable_whatsapp_fab', 1)) {
        return;
    }

    // Check device visibility
    $is_mobile = wp_is_mobile();
    $show_desktop = get_theme_mod('whatsapp_show_desktop', 1);
    $show_mobile = get_theme_mod('whatsapp_show_mobile', 1);

    if ($is_mobile && !$show_mobile) {
        return;
    }
    if (!$is_mobile && !$show_desktop) {
        return;
    }

    // Check scope
    $scope = get_theme_mod('whatsapp_show_scope', 'all');
    if ($scope === 'front_only' && !is_front_page()) {
        return;
    }
    if ($scope === 'pages_only' && !is_page()) {
        return;
    }

    // Get settings
    $raw_phone = get_theme_mod('whatsapp_number', '07706 230867');
    $clean_phone = preg_replace('/\D+/', '', $raw_phone);
    
    // Optional: if starts with 0 and UK assumed, could normalize to 44
    // For now, just use clean digits as-is
    // $clean_phone = preg_replace('/^0/', '44', $clean_phone);
    
    $prefill = urlencode(get_theme_mod('whatsapp_pretext', 'Hi, I\'m interested in a DPF/engine service. Vehicle: [make/model], Location: [area].'));
    $chat_href = 'https://wa.me/' . $clean_phone . '?text=' . $prefill;
    $tel_href = 'tel:' . $clean_phone;

    // Position
    $position = get_theme_mod('whatsapp_position', 'bottom-right');
    $offset_x = absint(get_theme_mod('whatsapp_offset_x', 20));
    $offset_y = absint(get_theme_mod('whatsapp_offset_y', 20));

    $position_style = '';
    if ($position === 'bottom-right') {
        $position_style = 'right: ' . $offset_x . 'px; bottom: ' . $offset_y . 'px;';
    } else {
        $position_style = 'left: ' . $offset_x . 'px; bottom: ' . $offset_y . 'px;';
    }

    ?>
    <div id="clarkes-wa-fab" class="fixed z-50" style="<?php echo esc_attr($position_style); ?>">
        <button id="clarkes-wa-toggle" class="rounded-full p-3 bg-eco-green/90 hover:bg-eco-green text-white shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green transition-colors" aria-expanded="false" aria-controls="clarkes-wa-sheet" aria-label="Open WhatsApp contact options">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
        </button>
        <div id="clarkes-wa-sheet" class="hidden mt-2 w-56 rounded-lg shadow-lg bg-carbon-dark text-text-body border border-eco-green/40 overflow-hidden">
            <a <?php echo clarkes_link_attrs($chat_href, 'block px-4 py-3 hover:bg-carbon-dark/60 transition-colors'); ?> aria-label="Chat on WhatsApp (opens in new tab)">
                Chat on WhatsApp
            </a>
            <a href="<?php echo esc_url($tel_href); ?>" class="block px-4 py-3 hover:bg-carbon-dark/60 transition-colors" aria-label="Call Clarke's DPF & Engine Specialists">
                Call <?php echo esc_html($raw_phone); ?>
            </a>
        </div>
        <noscript>
            <a href="<?php echo esc_url($chat_href); ?>" class="block mt-2 rounded-lg px-4 py-2 bg-eco-green text-white text-center hover:bg-eco-green/80 transition-colors">
                Chat on WhatsApp
            </a>
        </noscript>
    </div>
    <script>
    (function() {
        'use strict';
        const toggle = document.getElementById('clarkes-wa-toggle');
        const sheet = document.getElementById('clarkes-wa-sheet');
        if (!toggle || !sheet) return;

        function closeSheet() {
            sheet.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');
        }

        function openSheet() {
            sheet.classList.remove('hidden');
            toggle.setAttribute('aria-expanded', 'true');
        }

        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            if (sheet.classList.contains('hidden')) {
                openSheet();
            } else {
                closeSheet();
            }
        });

        // Close on outside click
        document.addEventListener('click', function(e) {
            if (!toggle.contains(e.target) && !sheet.contains(e.target)) {
                closeSheet();
            }
        });

        // Close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !sheet.classList.contains('hidden')) {
                closeSheet();
            }
        });
    })();
    </script>
    <?php
}
add_action('wp_footer', 'clarkes_render_whatsapp_fab', 99);

/**
 * Enqueue Customizer Preview Script
 */
function clarkes_whatsapp_preview_init() {
    wp_enqueue_script(
        'clarkes-whatsapp-preview',
        get_template_directory_uri() . '/inc/whatsapp-preview.js',
        array('customize-preview', 'jquery'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('customize_preview_init', 'clarkes_whatsapp_preview_init');

