<?php
/**
 * Theme Customizer Settings
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Customizer settings and controls
 */
if (!function_exists('clarkes_customize_register')) {
function clarkes_customize_register($wp_customize) {
    
    // Create main panel
    $wp_customize->add_panel('clarkes_theme_options', array(
        'title'       => esc_html__("Clarke's Theme Options", 'clarkes-terraclean'),
        'description' => esc_html__('Customize the appearance and settings of your theme', 'clarkes-terraclean'),
        'priority'    => 30,
    ));
    
    // ========================================
    // SECTION A: Branding
    // ========================================
    $wp_customize->add_section('clarkes_branding', array(
        'title'    => esc_html__('Branding', 'clarkes-terraclean'),
        'panel'    => 'clarkes_theme_options',
        'priority' => 10,
    ));
    
    // Site logo alt (optional alternate logo)
    $wp_customize->add_setting('site_logo_alt', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'site_logo_alt', array(
        'label'       => esc_html__('Alternate Logo (Optional)', 'clarkes-terraclean'),
        'description' => esc_html__('Upload an alternate logo (e.g., light version for dark backgrounds)', 'clarkes-terraclean'),
        'section'     => 'clarkes_branding',
        'mime_type'   => 'image',
    )));
    
    // Brand tagline
    $wp_customize->add_setting('brand_tagline', array(
        'default'           => 'Lower emissions. Restore performance. Improve MPG.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('brand_tagline', array(
        'label'       => esc_html__('Brand Tagline', 'clarkes-terraclean'),
        'description' => esc_html__('Tagline displayed in footer and branding areas', 'clarkes-terraclean'),
        'section'     => 'clarkes_branding',
        'type'        => 'text',
    ));
    
    // Business phone
    $wp_customize->add_setting('business_phone', array(
        'default'           => '07706 230867',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('business_phone', array(
        'label'       => esc_html__('Business Phone', 'clarkes-terraclean'),
        'description' => esc_html__('Phone number displayed throughout the site', 'clarkes-terraclean'),
        'section'     => 'clarkes_branding',
        'type'        => 'text',
    ));
    
    // Contact email
    $wp_customize->add_setting('contact_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('contact_email', array(
        'label'       => esc_html__('Contact Email (Optional)', 'clarkes-terraclean'),
        'description' => esc_html__('Override admin email for contact form submissions. Leave empty to use admin email.', 'clarkes-terraclean'),
        'section'     => 'clarkes_branding',
        'type'        => 'email',
    ));
    
    // Business address
    $wp_customize->add_setting('business_address', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('business_address', array(
        'label'       => esc_html__('Business Address (Optional)', 'clarkes-terraclean'),
        'description' => esc_html__('Business address for display in footer or contact page', 'clarkes-terraclean'),
        'section'     => 'clarkes_branding',
        'type'        => 'textarea',
    ));
    
    // ========================================
    // SECTION B: Colours
    // ========================================
    $wp_customize->add_section('clarkes_colors', array(
        'title'    => esc_html__('Colours', 'clarkes-terraclean'),
        'panel'    => 'clarkes_theme_options',
        'priority' => 20,
    ));
    
    // Accent color
    $wp_customize->add_setting('color_accent', array(
        'default'           => '#4ade80',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_accent', array(
        'label'   => esc_html__('Accent Color (Eco Green)', 'clarkes-terraclean'),
        'section' => 'clarkes_colors',
    )));
    
    // Dark color
    $wp_customize->add_setting('color_dark', array(
        'default'           => '#0f0f0f',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_dark', array(
        'label'   => esc_html__('Carbon Dark', 'clarkes-terraclean'),
        'section' => 'clarkes_colors',
    )));
    
    // Light color
    $wp_customize->add_setting('color_light', array(
        'default'           => '#f5f5f5',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_light', array(
        'label'   => esc_html__('Carbon Light', 'clarkes-terraclean'),
        'section' => 'clarkes_colors',
    )));
    
    // Text body color
    $wp_customize->add_setting('color_text_body', array(
        'default'           => '#d4d4d4',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_text_body', array(
        'label'   => esc_html__('Text Body Color', 'clarkes-terraclean'),
        'section' => 'clarkes_colors',
    )));
    
    // Text dark color
    $wp_customize->add_setting('color_text_dark', array(
        'default'           => '#1a1a1a',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'color_text_dark', array(
        'label'   => esc_html__('Text Dark Color', 'clarkes-terraclean'),
        'section' => 'clarkes_colors',
    )));
    
    // ========================================
    // SECTION C: Header & Navigation
    // ========================================
    $wp_customize->add_section('clarkes_header', array(
        'title'    => esc_html__('Header & Navigation', 'clarkes-terraclean'),
        'panel'    => 'clarkes_theme_options',
        'priority' => 30,
    ));
    
    // Header sticky
    $wp_customize->add_setting('header_sticky', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('header_sticky', array(
        'label'       => esc_html__('Sticky Header', 'clarkes-terraclean'),
        'description' => esc_html__('Keep header fixed at top when scrolling', 'clarkes-terraclean'),
        'section'     => 'clarkes_header',
        'type'        => 'checkbox',
    ));
    
    // Show phone in header
    $wp_customize->add_setting('show_phone_in_header', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('show_phone_in_header', array(
        'label'       => esc_html__('Show Phone Number in Header', 'clarkes-terraclean'),
        'description' => esc_html__('Display phone CTA button in header', 'clarkes-terraclean'),
        'section'     => 'clarkes_header',
        'type'        => 'checkbox',
    ));
    
    // ========================================
    // SECTION D: Hero (Home)
    // ========================================
    $wp_customize->add_section('clarkes_hero', array(
        'title'    => esc_html__('Hero Section (Home)', 'clarkes-terraclean'),
        'panel'    => 'clarkes_theme_options',
        'priority' => 40,
    ));
    
    // Hero title
    $wp_customize->add_setting('hero_title', array(
        'default'           => 'Restore Performance. Reduce Emissions. Save Fuel.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('hero_title', array(
        'label'   => esc_html__('Hero Title', 'clarkes-terraclean'),
        'section' => 'clarkes_hero',
        'type'    => 'text',
    ));
    
    // Hero subtitle
    $wp_customize->add_setting('hero_subtitle', array(
        'default'           => 'Professional engine decarbonisation in Kent — DPF, EGR and injector specialists.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('hero_subtitle', array(
        'label'   => esc_html__('Hero Subtitle', 'clarkes-terraclean'),
        'section' => 'clarkes_hero',
        'type'    => 'textarea',
    ));
    
    // Hero background image
    $wp_customize->add_setting('hero_bg_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'hero_bg_image', array(
        'label'       => esc_html__('Hero Background Image (Optional)', 'clarkes-terraclean'),
        'description' => esc_html__('Upload a background image for the hero section', 'clarkes-terraclean'),
        'section'     => 'clarkes_hero',
        'mime_type'   => 'image',
    )));
    
    // Hero height
    $wp_customize->add_setting('hero_height', array(
        'default'           => '70vh',
        'sanitize_callback' => function($input) {
            $choices = array('60vh', '70vh', '80vh');
            return in_array($input, $choices) ? $input : '70vh';
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('hero_height', array(
        'label'   => esc_html__('Hero Height', 'clarkes-terraclean'),
        'section' => 'clarkes_hero',
        'type'    => 'select',
        'choices' => array(
            '60vh' => '60vh',
            '70vh' => '70vh',
            '80vh' => '80vh',
        ),
    ));
    
    // ========================================
    // SECTION E: Sections Visibility
    // ========================================
    $wp_customize->add_section('clarkes_sections', array(
        'title'    => esc_html__('Sections Visibility', 'clarkes-terraclean'),
        'panel'    => 'clarkes_theme_options',
        'priority' => 50,
    ));
    
    // Show About
    $wp_customize->add_setting('show_about', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('show_about', array(
        'label'   => esc_html__('Show About Section', 'clarkes-terraclean'),
        'section' => 'clarkes_sections',
        'type'    => 'checkbox',
    ));
    
    // Show Services
    $wp_customize->add_setting('show_services', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('show_services', array(
        'label'   => esc_html__('Show Services Section', 'clarkes-terraclean'),
        'section' => 'clarkes_sections',
        'type'    => 'checkbox',
    ));
    
    // Show Case Studies
    $wp_customize->add_setting('show_case_studies', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('show_case_studies', array(
        'label'   => esc_html__('Show Case Studies Section', 'clarkes-terraclean'),
        'section' => 'clarkes_sections',
        'type'    => 'checkbox',
    ));
    
    // Show Testimonials
    $wp_customize->add_setting('show_testimonials', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('show_testimonials', array(
        'label'   => esc_html__('Show Testimonials Section', 'clarkes-terraclean'),
        'section' => 'clarkes_sections',
        'type'    => 'checkbox',
    ));
    
    // Show Contact
    $wp_customize->add_setting('show_contact', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('show_contact', array(
        'label'   => esc_html__('Show Contact Section', 'clarkes-terraclean'),
        'section' => 'clarkes_sections',
        'type'    => 'checkbox',
    ));
    
    // ========================================
    // SECTION F: Footer
    // ========================================
    $wp_customize->add_section('clarkes_footer', array(
        'title'    => esc_html__('Footer', 'clarkes-terraclean'),
        'panel'    => 'clarkes_theme_options',
        'priority' => 60,
    ));
    
    // Footer layout
    $wp_customize->add_setting('footer_layout', array(
        'default'           => '4-col',
        'sanitize_callback' => function($input) {
            $choices = array('1-col', '2-col', '3-col', '4-col');
            return in_array($input, $choices) ? $input : '4-col';
        },
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('footer_layout', array(
        'label'   => esc_html__('Footer Layout', 'clarkes-terraclean'),
        'section' => 'clarkes_footer',
        'type'    => 'select',
        'choices' => array(
            '1-col' => '1 Column',
            '2-col' => '2 Columns',
            '3-col' => '3 Columns',
            '4-col' => '4 Columns',
        ),
    ));
    
    // Footer show menu
    $wp_customize->add_setting('footer_show_menu', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('footer_show_menu', array(
        'label'       => esc_html__('Show Footer Menu', 'clarkes-terraclean'),
        'description' => esc_html__('Display footer menu if assigned', 'clarkes-terraclean'),
        'section'     => 'clarkes_footer',
        'type'        => 'checkbox',
    ));
    
    // Footer show widgets
    $wp_customize->add_setting('footer_show_widgets', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('footer_show_widgets', array(
        'label'       => esc_html__('Show Footer Widgets', 'clarkes-terraclean'),
        'description' => esc_html__('Display footer widget areas if active', 'clarkes-terraclean'),
        'section'     => 'clarkes_footer',
        'type'        => 'checkbox',
    ));
    
    // Footer social Facebook
    $wp_customize->add_setting('footer_social_facebook', array(
        'default'           => 'https://www.facebook.com/Clarkesterraclean',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('footer_social_facebook', array(
        'label'   => esc_html__('Facebook URL', 'clarkes-terraclean'),
        'section' => 'clarkes_footer',
        'type'    => 'url',
    ));
    
    // ========================================
    // SECTION G: Layout
    // ========================================
    $wp_customize->add_section('clarkes_layout', array(
        'title'    => esc_html__('Layout', 'clarkes-terraclean'),
        'panel'    => 'clarkes_theme_options',
        'priority' => 70,
    ));
    
    // Container max width
    $wp_customize->add_setting('container_max', array(
        'default'           => '7xl',
        'sanitize_callback' => function($input) {
            $choices = array('6xl', '7xl', 'full');
            return in_array($input, $choices) ? $input : '7xl';
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('container_max', array(
        'label'   => esc_html__('Container Max Width', 'clarkes-terraclean'),
        'section' => 'clarkes_layout',
        'type'    => 'select',
        'choices' => array(
            '6xl'  => '6xl (72rem)',
            '7xl'  => '7xl (80rem)',
            'full' => 'Full Width (100%)',
        ),
    ));
    
    // Card roundness
    $wp_customize->add_setting('card_roundness', array(
        'default'           => 'lg',
        'sanitize_callback' => function($input) {
            $choices = array('md', 'lg', 'xl', '2xl');
            return in_array($input, $choices) ? $input : 'lg';
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('card_roundness', array(
        'label'   => esc_html__('Card Border Radius', 'clarkes-terraclean'),
        'section' => 'clarkes_layout',
        'type'    => 'select',
        'choices' => array(
            'md'  => 'Medium',
            'lg'  => 'Large',
            'xl'  => 'Extra Large',
            '2xl' => '2X Large',
        ),
    ));
}
}
add_action('customize_register', 'clarkes_customize_register');

/**
 * Enqueue Customizer preview script
 */
if (!function_exists('clarkes_customize_preview_init')) {
function clarkes_customize_preview_init() {
    wp_enqueue_script(
        'clarkes-customizer-preview',
        get_template_directory_uri() . '/inc/customizer-preview.js',
        array('customize-preview', 'jquery'),
        wp_get_theme()->get('Version'),
        true
    );
}
}
add_action('customize_preview_init', 'clarkes_customize_preview_init');

