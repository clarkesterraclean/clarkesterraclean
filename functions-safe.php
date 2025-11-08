<?php
/**
 * SAFE functions.php - Minimal version to test
 * Temporarily rename functions.php to functions.php.bak and this to functions.php
 * to test if the basic theme loads
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
if (!function_exists('clarkes_terraclean_setup')) {
function clarkes_terraclean_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    
    register_nav_menus(array(
        'primary_menu' => esc_html__('Primary Menu', 'clarkes-terraclean'),
    ));
}
}
add_action('after_setup_theme', 'clarkes_terraclean_setup');

/**
 * Enqueue Styles and Scripts
 */
if (!function_exists('clarkes_terraclean_scripts')) {
function clarkes_terraclean_scripts() {
    $css_file = get_stylesheet_directory() . '/dist/style.css';
    $css_ver  = file_exists($css_file) ? filemtime($css_file) : '1.0.0';
    
    wp_enqueue_style(
        'clarkes-terraclean-style',
        get_stylesheet_directory_uri() . '/dist/style.css',
        array(),
        $css_ver
    );
    
    $js_file = get_stylesheet_directory() . '/js/theme.js';
    $js_ver  = file_exists($js_file) ? filemtime($js_file) : '1.0.0';
    
    wp_enqueue_script(
        'clarkes-terraclean-script',
        get_stylesheet_directory_uri() . '/js/theme.js',
        array(),
        $js_ver,
        true
    );
}
}
add_action('wp_enqueue_scripts', 'clarkes_terraclean_scripts');

