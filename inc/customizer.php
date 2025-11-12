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
    
    // Header Layout Settings
    $wp_customize->add_setting('header_layout', array(
        'default'           => 'default',
        'sanitize_callback' => function($input) {
            $choices = array('default', 'centered', 'split', 'minimal', 'simple');
            return in_array($input, $choices) ? $input : 'default';
        },
        'transport'         => 'refresh', // Changed to refresh because layout changes HTML structure
    ));
    
    $wp_customize->add_control('header_layout', array(
        'label'       => esc_html__('Header Layout', 'clarkes-terraclean'),
        'description' => esc_html__('Choose the header layout style', 'clarkes-terraclean'),
        'section'     => 'clarkes_header',
        'type'        => 'select',
        'choices'     => array(
            'default'  => 'Default (Logo Left, Nav Right, CTA Right)',
            'simple'   => 'Simple (Logo Left, Nav Right, No CTA)',
            'centered' => 'Centered (Logo Center, Nav Below)',
            'split'    => 'Split (Logo Left, Nav Center, CTA Right)',
            'minimal'  => 'Minimal (Logo Only)',
        ),
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
    
    // Header Height
    $wp_customize->add_setting('header_height', array(
        'default'           => '64',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_height', array(
        'label'       => esc_html__('Header Height (px)', 'clarkes-terraclean'),
        'description' => esc_html__('Set the header height in pixels (default: 64px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_header',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 40,
            'max'  => 120,
            'step' => 4,
        ),
    ));
    
    // Header Background Color
    $wp_customize->add_setting('header_bg_color', array(
        'default'           => '#0f0f0f',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_bg_color', array(
        'label'   => esc_html__('Header Background Color', 'clarkes-terraclean'),
        'section' => 'clarkes_header',
    )));
    
    // Header Background Image
    $wp_customize->add_setting('header_bg_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'header_bg_image', array(
        'label'       => esc_html__('Header Background Image (Optional)', 'clarkes-terraclean'),
        'description' => esc_html__('Upload a background image for the header', 'clarkes-terraclean'),
        'section'     => 'clarkes_header',
        'mime_type'   => 'image',
    )));
    
    // Header Text Color
    $wp_customize->add_setting('header_text_color', array(
        'default'           => '#d4d4d4',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_text_color', array(
        'label'   => esc_html__('Header Text Color', 'clarkes-terraclean'),
        'section' => 'clarkes_header',
    )));
    
    // Header Link Color
    $wp_customize->add_setting('header_link_color', array(
        'default'           => '#d4d4d4',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_link_color', array(
        'label'   => esc_html__('Header Link Color', 'clarkes-terraclean'),
        'section' => 'clarkes_header',
    )));
    
    // Header Link Hover Color
    $wp_customize->add_setting('header_link_hover_color', array(
        'default'           => '#4ade80',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_link_hover_color', array(
        'label'   => esc_html__('Header Link Hover Color', 'clarkes-terraclean'),
        'section' => 'clarkes_header',
    )));
    
    // Header Border Color
    $wp_customize->add_setting('header_border_color', array(
        'default'           => '#4ade804d',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_border_color', array(
        'label'   => esc_html__('Header Border Color', 'clarkes-terraclean'),
        'section' => 'clarkes_header',
    )));
    
    // Header Padding Top/Bottom
    $wp_customize->add_setting('header_padding_vertical', array(
        'default'           => '0',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_padding_vertical', array(
        'label'       => esc_html__('Header Vertical Padding (px)', 'clarkes-terraclean'),
        'description' => esc_html__('Top and bottom padding for header content', 'clarkes-terraclean'),
        'section'     => 'clarkes_header',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 40,
            'step' => 2,
        ),
    ));
    
    // Header Padding Horizontal
    $wp_customize->add_setting('header_padding_horizontal', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_padding_horizontal', array(
        'label'       => esc_html__('Header Horizontal Padding (px)', 'clarkes-terraclean'),
        'description' => esc_html__('Left and right padding for header content', 'clarkes-terraclean'),
        'section'     => 'clarkes_header',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 80,
            'step' => 4,
        ),
    ));
    
    // Logo Size
    $wp_customize->add_setting('header_logo_size', array(
        'default'           => 'medium',
        'sanitize_callback' => function($input) {
            $choices = array('small', 'medium', 'large', 'xlarge');
            return in_array($input, $choices) ? $input : 'medium';
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_logo_size', array(
        'label'   => esc_html__('Logo Size', 'clarkes-terraclean'),
        'section' => 'clarkes_header',
        'type'    => 'select',
        'choices' => array(
            'small'  => 'Small',
            'medium' => 'Medium',
            'large'  => 'Large',
            'xlarge' => 'Extra Large',
        ),
    ));
    
    // Navigation Font Size
    $wp_customize->add_setting('header_nav_font_size', array(
        'default'           => '14',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_nav_font_size', array(
        'label'       => esc_html__('Navigation Font Size (px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_header',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 20,
            'step' => 1,
        ),
    ));
    
    // Navigation Font Weight
    $wp_customize->add_setting('header_nav_font_weight', array(
        'default'           => '500',
        'sanitize_callback' => function($input) {
            $choices = array('300', '400', '500', '600', '700');
            return in_array($input, $choices) ? $input : '500';
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_nav_font_weight', array(
        'label'   => esc_html__('Navigation Font Weight', 'clarkes-terraclean'),
        'section' => 'clarkes_header',
        'type'    => 'select',
        'choices' => array(
            '300' => 'Light (300)',
            '400' => 'Normal (400)',
            '500' => 'Medium (500)',
            '600' => 'Semi-Bold (600)',
            '700' => 'Bold (700)',
        ),
    ));
    
    // Navigation Letter Spacing
    $wp_customize->add_setting('header_nav_letter_spacing', array(
        'default'           => '0',
        'sanitize_callback' => function($input) {
            return is_numeric($input) ? floatval($input) : 0;
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_nav_letter_spacing', array(
        'label'       => esc_html__('Navigation Letter Spacing (px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_header',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => -2,
            'max'  => 5,
            'step' => 0.1,
        ),
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
    
    // Phone Button Style
    $wp_customize->add_setting('header_phone_button_style', array(
        'default'           => 'outline',
        'sanitize_callback' => function($input) {
            $choices = array('outline', 'solid', 'text');
            return in_array($input, $choices) ? $input : 'outline';
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_phone_button_style', array(
        'label'   => esc_html__('Phone Button Style', 'clarkes-terraclean'),
        'section' => 'clarkes_header',
        'type'    => 'select',
        'choices' => array(
            'outline' => 'Outline',
            'solid'   => 'Solid',
            'text'    => 'Text Only',
        ),
    ));
    
    // Mobile Menu Settings
    $wp_customize->add_setting('mobile_menu_style', array(
        'default'           => 'slide',
        'sanitize_callback' => function($input) {
            $choices = array('slide', 'dropdown', 'fullscreen');
            return in_array($input, $choices) ? $input : 'slide';
        },
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('mobile_menu_style', array(
        'label'       => esc_html__('Mobile Menu Style', 'clarkes-terraclean'),
        'description' => esc_html__('Choose how the mobile menu appears', 'clarkes-terraclean'),
        'section'     => 'clarkes_header',
        'type'        => 'select',
        'choices'     => array(
            'slide'     => 'Slide Down',
            'dropdown'  => 'Dropdown',
            'fullscreen' => 'Full Screen Overlay',
        ),
    ));
    
    // Mobile Menu Background Color
    $wp_customize->add_setting('mobile_menu_bg_color', array(
        'default'           => '#0f0f0f',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mobile_menu_bg_color', array(
        'label'   => esc_html__('Mobile Menu Background Color', 'clarkes-terraclean'),
        'section' => 'clarkes_header',
    )));
    
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
    // SECTION F: Section Images & Videos
    // ========================================
    $wp_customize->add_section('clarkes_images', array(
        'title'       => esc_html__('Section Images & Videos', 'clarkes-terraclean'),
        'description' => esc_html__('Manage images and videos for different sections of your site. Videos will display instead of images if both are uploaded. Note: Case study images are managed through the Case Studies post type (use Featured Image when editing a case study).', 'clarkes-terraclean'),
        'panel'       => 'clarkes_theme_options',
        'priority'    => 55,
    ));
    
    // About Section Image
    $wp_customize->add_setting('about_section_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'about_section_image', array(
        'label'       => esc_html__('About Section Image', 'clarkes-terraclean'),
        'description' => esc_html__('Upload an image for the About section on the homepage. This appears alongside the "Why Choose Clarke\'s?" content.', 'clarkes-terraclean'),
        'section'     => 'clarkes_images',
        'mime_type'   => 'image',
    )));
    
    // About Section Video
    $wp_customize->add_setting('about_section_video', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'about_section_video', array(
        'label'       => esc_html__('About Section Video (Optional)', 'clarkes-terraclean'),
        'description' => esc_html__('Upload a video for the About section. If both image and video are uploaded, the video will be displayed instead of the image.', 'clarkes-terraclean'),
        'section'     => 'clarkes_images',
        'mime_type'   => 'video',
    )));
    
    // Service 1 Image (Engine Carbon Cleaning)
    $wp_customize->add_setting('service_1_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'service_1_image', array(
        'label'       => esc_html__('Engine Carbon Cleaning Image', 'clarkes-terraclean'),
        'description' => esc_html__('Image for the Engine Carbon Cleaning service', 'clarkes-terraclean'),
        'section'     => 'clarkes_images',
        'mime_type'   => 'image',
    )));
    
    // Service 1 Video (Engine Carbon Cleaning)
    $wp_customize->add_setting('service_1_video', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'service_1_video', array(
        'label'       => esc_html__('Engine Carbon Cleaning Video (Optional)', 'clarkes-terraclean'),
        'description' => esc_html__('Upload a video for this service. Video will display instead of image if both are uploaded.', 'clarkes-terraclean'),
        'section'     => 'clarkes_images',
        'mime_type'   => 'video',
    )));
    
    // Service 2 Image (DPF Cleaning)
    $wp_customize->add_setting('service_2_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'service_2_image', array(
        'label'       => esc_html__('DPF Cleaning Image', 'clarkes-terraclean'),
        'description' => esc_html__('Image for the DPF Cleaning service', 'clarkes-terraclean'),
        'section'     => 'clarkes_images',
        'mime_type'   => 'image',
    )));
    
    // Service 2 Video (DPF Cleaning)
    $wp_customize->add_setting('service_2_video', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'service_2_video', array(
        'label'       => esc_html__('DPF Cleaning Video (Optional)', 'clarkes-terraclean'),
        'description' => esc_html__('Upload a video for this service. Video will display instead of image if both are uploaded.', 'clarkes-terraclean'),
        'section'     => 'clarkes_images',
        'mime_type'   => 'video',
    )));
    
    // Service 3 Image (EGR Valve Cleaning)
    $wp_customize->add_setting('service_3_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'service_3_image', array(
        'label'       => esc_html__('EGR Valve Cleaning Image', 'clarkes-terraclean'),
        'description' => esc_html__('Image for the EGR Valve Cleaning service', 'clarkes-terraclean'),
        'section'     => 'clarkes_images',
        'mime_type'   => 'image',
    )));
    
    // Service 3 Video (EGR Valve Cleaning)
    $wp_customize->add_setting('service_3_video', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'service_3_video', array(
        'label'       => esc_html__('EGR Valve Cleaning Video (Optional)', 'clarkes-terraclean'),
        'description' => esc_html__('Upload a video for this service. Video will display instead of image if both are uploaded.', 'clarkes-terraclean'),
        'section'     => 'clarkes_images',
        'mime_type'   => 'video',
    )));
    
    // Service 4 Image (Injector Cleaning)
    $wp_customize->add_setting('service_4_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'service_4_image', array(
        'label'       => esc_html__('Injector Cleaning & Diagnostics Image', 'clarkes-terraclean'),
        'description' => esc_html__('Image for the Injector Cleaning & Diagnostics service', 'clarkes-terraclean'),
        'section'     => 'clarkes_images',
        'mime_type'   => 'image',
    )));
    
    // Service 4 Video (Injector Cleaning)
    $wp_customize->add_setting('service_4_video', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'service_4_video', array(
        'label'       => esc_html__('Injector Cleaning & Diagnostics Video (Optional)', 'clarkes-terraclean'),
        'description' => esc_html__('Upload a video for this service. Video will display instead of image if both are uploaded.', 'clarkes-terraclean'),
        'section'     => 'clarkes_images',
        'mime_type'   => 'video',
    )));
    
    // ========================================
    // SECTION G: Footer
    // ========================================
    $wp_customize->add_section('clarkes_footer', array(
        'title'    => esc_html__('Footer', 'clarkes-terraclean'),
        'panel'    => 'clarkes_theme_options',
        'priority' => 60,
    ));
    
    // Footer Layout
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
    
    // Footer Background Color
    $wp_customize->add_setting('footer_bg_color', array(
        'default'           => '#0f0f0f',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_bg_color', array(
        'label'   => esc_html__('Footer Background Color', 'clarkes-terraclean'),
        'section' => 'clarkes_footer',
    )));
    
    // Footer Background Image
    $wp_customize->add_setting('footer_bg_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'footer_bg_image', array(
        'label'       => esc_html__('Footer Background Image (Optional)', 'clarkes-terraclean'),
        'description' => esc_html__('Upload a background image for the footer', 'clarkes-terraclean'),
        'section'     => 'clarkes_footer',
        'mime_type'   => 'image',
    )));
    
    // Footer Text Color
    $wp_customize->add_setting('footer_text_color', array(
        'default'           => '#d4d4d4',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_text_color', array(
        'label'   => esc_html__('Footer Text Color', 'clarkes-terraclean'),
        'section' => 'clarkes_footer',
    )));
    
    // Footer Link Color
    $wp_customize->add_setting('footer_link_color', array(
        'default'           => '#d4d4d4',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_link_color', array(
        'label'   => esc_html__('Footer Link Color', 'clarkes-terraclean'),
        'section' => 'clarkes_footer',
    )));
    
    // Footer Link Hover Color
    $wp_customize->add_setting('footer_link_hover_color', array(
        'default'           => '#4ade80',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_link_hover_color', array(
        'label'   => esc_html__('Footer Link Hover Color', 'clarkes-terraclean'),
        'section' => 'clarkes_footer',
    )));
    
    // Footer Border Color
    $wp_customize->add_setting('footer_border_color', array(
        'default'           => '#4ade804d',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_border_color', array(
        'label'   => esc_html__('Footer Border Color', 'clarkes-terraclean'),
        'section' => 'clarkes_footer',
    )));
    
    // Footer Padding Top
    $wp_customize->add_setting('footer_padding_top', array(
        'default'           => '64',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('footer_padding_top', array(
        'label'       => esc_html__('Footer Padding Top (px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_footer',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 20,
            'max'  => 120,
            'step' => 4,
        ),
    ));
    
    // Footer Padding Bottom
    $wp_customize->add_setting('footer_padding_bottom', array(
        'default'           => '32',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('footer_padding_bottom', array(
        'label'       => esc_html__('Footer Padding Bottom (px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_footer',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 20,
            'max'  => 120,
            'step' => 4,
        ),
    ));
    
    // Footer Padding Horizontal
    $wp_customize->add_setting('footer_padding_horizontal', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('footer_padding_horizontal', array(
        'label'       => esc_html__('Footer Horizontal Padding (px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_footer',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 80,
            'step' => 4,
        ),
    ));
    
    // Footer Font Size
    $wp_customize->add_setting('footer_font_size', array(
        'default'           => '14',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('footer_font_size', array(
        'label'       => esc_html__('Footer Font Size (px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_footer',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 20,
            'step' => 1,
        ),
    ));
    
    // Footer Heading Font Size
    $wp_customize->add_setting('footer_heading_font_size', array(
        'default'           => '18',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('footer_heading_font_size', array(
        'label'       => esc_html__('Footer Heading Font Size (px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_footer',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 14,
            'max'  => 28,
            'step' => 1,
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
    
    // Footer Copyright Text
    $wp_customize->add_setting('footer_copyright_text', array(
        'default'           => '© ' . date('Y') . ' Clarke\'s DPF & Engine Specialists. All rights reserved.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('footer_copyright_text', array(
        'label'       => esc_html__('Copyright Text', 'clarkes-terraclean'),
        'description' => esc_html__('Text displayed in footer copyright area', 'clarkes-terraclean'),
        'section'     => 'clarkes_footer',
        'type'        => 'text',
    ));
    
    // Footer Show Copyright
    $wp_customize->add_setting('footer_show_copyright', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('footer_show_copyright', array(
        'label'   => esc_html__('Show Copyright Text', 'clarkes-terraclean'),
        'section' => 'clarkes_footer',
        'type'    => 'checkbox',
    ));
    
    // Footer Social Links Section
    $wp_customize->add_setting('footer_show_social', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('footer_show_social', array(
        'label'   => esc_html__('Show Social Links', 'clarkes-terraclean'),
        'section' => 'clarkes_footer',
        'type'    => 'checkbox',
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
    
    // Footer social Twitter
    $wp_customize->add_setting('footer_social_twitter', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('footer_social_twitter', array(
        'label'   => esc_html__('Twitter URL (Optional)', 'clarkes-terraclean'),
        'section' => 'clarkes_footer',
        'type'    => 'url',
    ));
    
    // Footer social Instagram
    $wp_customize->add_setting('footer_social_instagram', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('footer_social_instagram', array(
        'label'   => esc_html__('Instagram URL (Optional)', 'clarkes-terraclean'),
        'section' => 'clarkes_footer',
        'type'    => 'url',
    ));
    
    // Footer social LinkedIn
    $wp_customize->add_setting('footer_social_linkedin', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('footer_social_linkedin', array(
        'label'   => esc_html__('LinkedIn URL (Optional)', 'clarkes-terraclean'),
        'section' => 'clarkes_footer',
        'type'    => 'url',
    ));
    
    // Footer social YouTube
    $wp_customize->add_setting('footer_social_youtube', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('footer_social_youtube', array(
        'label'   => esc_html__('YouTube URL (Optional)', 'clarkes-terraclean'),
        'section' => 'clarkes_footer',
        'type'    => 'url',
    ));
    
    // ========================================
    // SECTION H: Typography
    // ========================================
    $wp_customize->add_section('clarkes_typography', array(
        'title'    => esc_html__('Typography', 'clarkes-terraclean'),
        'panel'    => 'clarkes_theme_options',
        'priority' => 65,
    ));
    
    // Body Font Family
    $wp_customize->add_setting('body_font_family', array(
        'default'           => 'system',
        'sanitize_callback' => function($input) {
            $choices = array('system', 'serif', 'sans-serif', 'monospace');
            return in_array($input, $choices) ? $input : 'system';
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('body_font_family', array(
        'label'   => esc_html__('Body Font Family', 'clarkes-terraclean'),
        'section' => 'clarkes_typography',
        'type'    => 'select',
        'choices' => array(
            'system'     => 'System Default',
            'sans-serif' => 'Sans Serif',
            'serif'      => 'Serif',
            'monospace'  => 'Monospace',
        ),
    ));
    
    // Body Font Size
    $wp_customize->add_setting('body_font_size', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('body_font_size', array(
        'label'       => esc_html__('Body Font Size (px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 20,
            'step' => 1,
        ),
    ));
    
    // Body Line Height
    $wp_customize->add_setting('body_line_height', array(
        'default'           => '1.6',
        'sanitize_callback' => function($input) {
            return is_numeric($input) ? floatval($input) : 1.6;
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('body_line_height', array(
        'label'       => esc_html__('Body Line Height', 'clarkes-terraclean'),
        'section'     => 'clarkes_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1.0,
            'max'  => 2.5,
            'step' => 0.1,
        ),
    ));
    
    // Heading Font Family
    $wp_customize->add_setting('heading_font_family', array(
        'default'           => 'system',
        'sanitize_callback' => function($input) {
            $choices = array('system', 'serif', 'sans-serif', 'monospace');
            return in_array($input, $choices) ? $input : 'system';
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('heading_font_family', array(
        'label'   => esc_html__('Heading Font Family', 'clarkes-terraclean'),
        'section' => 'clarkes_typography',
        'type'    => 'select',
        'choices' => array(
            'system'     => 'System Default',
            'sans-serif' => 'Sans Serif',
            'serif'      => 'Serif',
            'monospace'  => 'Monospace',
        ),
    ));
    
    // Heading Font Weight
    $wp_customize->add_setting('heading_font_weight', array(
        'default'           => '700',
        'sanitize_callback' => function($input) {
            $choices = array('300', '400', '500', '600', '700', '800');
            return in_array($input, $choices) ? $input : '700';
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('heading_font_weight', array(
        'label'   => esc_html__('Heading Font Weight', 'clarkes-terraclean'),
        'section' => 'clarkes_typography',
        'type'    => 'select',
        'choices' => array(
            '300' => 'Light (300)',
            '400' => 'Normal (400)',
            '500' => 'Medium (500)',
            '600' => 'Semi-Bold (600)',
            '700' => 'Bold (700)',
            '800' => 'Extra Bold (800)',
        ),
    ));
    
    // H1 Font Size
    $wp_customize->add_setting('h1_font_size', array(
        'default'           => '36',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('h1_font_size', array(
        'label'       => esc_html__('H1 Font Size (px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 24,
            'max'  => 72,
            'step' => 2,
        ),
    ));
    
    // H2 Font Size
    $wp_customize->add_setting('h2_font_size', array(
        'default'           => '30',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('h2_font_size', array(
        'label'       => esc_html__('H2 Font Size (px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 20,
            'max'  => 60,
            'step' => 2,
        ),
    ));
    
    // H3 Font Size
    $wp_customize->add_setting('h3_font_size', array(
        'default'           => '24',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('h3_font_size', array(
        'label'       => esc_html__('H3 Font Size (px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 18,
            'max'  => 48,
            'step' => 2,
        ),
    ));
    
    // ========================================
    // SECTION I: Buttons & CTAs
    // ========================================
    $wp_customize->add_section('clarkes_buttons', array(
        'title'    => esc_html__('Buttons & CTAs', 'clarkes-terraclean'),
        'panel'    => 'clarkes_theme_options',
        'priority' => 68,
    ));
    
    // Primary Button Background Color
    $wp_customize->add_setting('button_primary_bg', array(
        'default'           => '#4ade80',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_primary_bg', array(
        'label'   => esc_html__('Primary Button Background', 'clarkes-terraclean'),
        'section' => 'clarkes_buttons',
    )));
    
    // Primary Button Text Color
    $wp_customize->add_setting('button_primary_text', array(
        'default'           => '#0f0f0f',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_primary_text', array(
        'label'   => esc_html__('Primary Button Text Color', 'clarkes-terraclean'),
        'section' => 'clarkes_buttons',
    )));
    
    // Primary Button Hover Background
    $wp_customize->add_setting('button_primary_hover_bg', array(
        'default'           => '#22c55e',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_primary_hover_bg', array(
        'label'   => esc_html__('Primary Button Hover Background', 'clarkes-terraclean'),
        'section' => 'clarkes_buttons',
    )));
    
    // Secondary Button Background Color
    $wp_customize->add_setting('button_secondary_bg', array(
        'default'           => 'transparent',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('button_secondary_bg', array(
        'label'   => esc_html__('Secondary Button Background', 'clarkes-terraclean'),
        'section' => 'clarkes_buttons',
        'type'    => 'text',
    ));
    
    // Secondary Button Text Color
    $wp_customize->add_setting('button_secondary_text', array(
        'default'           => '#4ade80',
        'sanitize_callback' => 'clarkes_sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_secondary_text', array(
        'label'   => esc_html__('Secondary Button Text Color', 'clarkes-terraclean'),
        'section' => 'clarkes_buttons',
    )));
    
    // Button Border Radius
    $wp_customize->add_setting('button_border_radius', array(
        'default'           => 'full',
        'sanitize_callback' => function($input) {
            $choices = array('none', 'sm', 'md', 'lg', 'xl', 'full');
            return in_array($input, $choices) ? $input : 'full';
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('button_border_radius', array(
        'label'   => esc_html__('Button Border Radius', 'clarkes-terraclean'),
        'section' => 'clarkes_buttons',
        'type'    => 'select',
        'choices' => array(
            'none' => 'None (Square)',
            'sm'   => 'Small',
            'md'   => 'Medium',
            'lg'   => 'Large',
            'xl'   => 'Extra Large',
            'full' => 'Full (Pill)',
        ),
    ));
    
    // Button Padding Vertical
    $wp_customize->add_setting('button_padding_vertical', array(
        'default'           => '12',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('button_padding_vertical', array(
        'label'       => esc_html__('Button Vertical Padding (px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_buttons',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 6,
            'max'  => 24,
            'step' => 2,
        ),
    ));
    
    // Button Padding Horizontal
    $wp_customize->add_setting('button_padding_horizontal', array(
        'default'           => '24',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('button_padding_horizontal', array(
        'label'       => esc_html__('Button Horizontal Padding (px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_buttons',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 48,
            'step' => 2,
        ),
    ));
    
    // Button Font Size
    $wp_customize->add_setting('button_font_size', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('button_font_size', array(
        'label'       => esc_html__('Button Font Size (px)', 'clarkes-terraclean'),
        'section'     => 'clarkes_buttons',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 20,
            'step' => 1,
        ),
    ));
    
    // Button Font Weight
    $wp_customize->add_setting('button_font_weight', array(
        'default'           => '600',
        'sanitize_callback' => function($input) {
            $choices = array('400', '500', '600', '700');
            return in_array($input, $choices) ? $input : '600';
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('button_font_weight', array(
        'label'   => esc_html__('Button Font Weight', 'clarkes-terraclean'),
        'section' => 'clarkes_buttons',
        'type'    => 'select',
        'choices' => array(
            '400' => 'Normal (400)',
            '500' => 'Medium (500)',
            '600' => 'Semi-Bold (600)',
            '700' => 'Bold (700)',
        ),
    ));
    
    // ========================================
    // SECTION J: Advanced
    // ========================================
    $wp_customize->add_section('clarkes_advanced', array(
        'title'    => esc_html__('Advanced', 'clarkes-terraclean'),
        'panel'    => 'clarkes_theme_options',
        'priority' => 100,
    ));
    
    // Custom CSS
    $wp_customize->add_setting('custom_css', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('custom_css', array(
        'label'       => esc_html__('Custom CSS', 'clarkes-terraclean'),
        'description' => esc_html__('Add custom CSS code. This will be added to the theme styles.', 'clarkes-terraclean'),
        'section'     => 'clarkes_advanced',
        'type'        => 'textarea',
    ));
    
    // Custom JavaScript (Head)
    $wp_customize->add_setting('custom_js_head', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('custom_js_head', array(
        'label'       => esc_html__('Custom JavaScript (Head)', 'clarkes-terraclean'),
        'description' => esc_html__('Add custom JavaScript code to be placed in the &lt;head&gt; section', 'clarkes-terraclean'),
        'section'     => 'clarkes_advanced',
        'type'        => 'textarea',
    ));
    
    // Custom JavaScript (Footer)
    $wp_customize->add_setting('custom_js_footer', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('custom_js_footer', array(
        'label'       => esc_html__('Custom JavaScript (Footer)', 'clarkes-terraclean'),
        'description' => esc_html__('Add custom JavaScript code to be placed before closing &lt;/body&gt; tag', 'clarkes-terraclean'),
        'section'     => 'clarkes_advanced',
        'type'        => 'textarea',
    ));
    
    // Enable Lazy Loading
    $wp_customize->add_setting('enable_lazy_loading', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('enable_lazy_loading', array(
        'label'       => esc_html__('Enable Lazy Loading', 'clarkes-terraclean'),
        'description' => esc_html__('Lazy load images for better performance', 'clarkes-terraclean'),
        'section'     => 'clarkes_advanced',
        'type'        => 'checkbox',
    ));
    
    // Enable Smooth Scroll
    $wp_customize->add_setting('enable_smooth_scroll', array(
        'default'           => 1,
        'sanitize_callback' => 'clarkes_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('enable_smooth_scroll', array(
        'label'       => esc_html__('Enable Smooth Scroll', 'clarkes-terraclean'),
        'description' => esc_html__('Enable smooth scrolling for anchor links', 'clarkes-terraclean'),
        'section'     => 'clarkes_advanced',
        'type'        => 'checkbox',
    ));
    
    // ========================================
    // SECTION K: Layout
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
    
    // Section Padding Vertical
    $wp_customize->add_setting('section_padding_vertical', array(
        'default'           => '96',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('section_padding_vertical', array(
        'label'       => esc_html__('Section Vertical Padding (px)', 'clarkes-terraclean'),
        'description' => esc_html__('Default top and bottom padding for sections', 'clarkes-terraclean'),
        'section'     => 'clarkes_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 40,
            'max'  => 200,
            'step' => 8,
        ),
    ));
    
    // Section Padding Horizontal
    $wp_customize->add_setting('section_padding_horizontal', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('section_padding_horizontal', array(
        'label'       => esc_html__('Section Horizontal Padding (px)', 'clarkes-terraclean'),
        'description' => esc_html__('Default left and right padding for sections', 'clarkes-terraclean'),
        'section'     => 'clarkes_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 80,
            'step' => 4,
        ),
    ));
    
    // Content Gap (between elements)
    $wp_customize->add_setting('content_gap', array(
        'default'           => '24',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('content_gap', array(
        'label'       => esc_html__('Content Gap (px)', 'clarkes-terraclean'),
        'description' => esc_html__('Default spacing between content elements', 'clarkes-terraclean'),
        'section'     => 'clarkes_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 8,
            'max'  => 64,
            'step' => 4,
        ),
    ));
    
    // Grid Gap
    $wp_customize->add_setting('grid_gap', array(
        'default'           => '24',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('grid_gap', array(
        'label'       => esc_html__('Grid Gap (px)', 'clarkes-terraclean'),
        'description' => esc_html__('Default gap between grid items', 'clarkes-terraclean'),
        'section'     => 'clarkes_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 8,
            'max'  => 64,
            'step' => 4,
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
    $preview_file = get_template_directory() . '/inc/customizer-preview.js';
    $preview_ver = file_exists($preview_file) ? filemtime($preview_file) : '1.0.0';
    
    wp_enqueue_script(
        'clarkes-customizer-preview',
        get_template_directory_uri() . '/inc/customizer-preview.js',
        array('customize-preview', 'jquery'),
        $preview_ver,
        true
    );
}
}
add_action('customize_preview_init', 'clarkes_customize_preview_init');

