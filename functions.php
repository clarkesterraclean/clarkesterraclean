<?php
/**
 * Clarke's DPF & Engine Specialists Theme Functions
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Theme Setup
 */
if (!function_exists('clarkes_terraclean_setup')) {
function clarkes_terraclean_setup() {
    // Add theme support for title tag
    add_theme_support('title-tag');
    
    // Add theme support for post thumbnails
    add_theme_support('post-thumbnails');
    
    // Add theme support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 120,
        'width'       => 600,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    
    // Add theme support for custom background
    add_theme_support('custom-background');
    
    // Add theme support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary_menu' => esc_html__('Primary Menu', 'clarkes-terraclean'),
        'footer_menu'  => esc_html__('Footer Menu', 'clarkes-terraclean'),
        'social_menu'  => esc_html__('Social Links', 'clarkes-terraclean'),
    ));
    
    // Register widget areas
    register_sidebar(array(
        'name'          => esc_html__('Footer Column 1', 'clarkes-terraclean'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Widgets for the first footer column', 'clarkes-terraclean'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Footer Column 2', 'clarkes-terraclean'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Widgets for the second footer column', 'clarkes-terraclean'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Footer Column 3', 'clarkes-terraclean'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Widgets for the third footer column', 'clarkes-terraclean'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Footer Column 4', 'clarkes-terraclean'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Widgets for the fourth footer column', 'clarkes-terraclean'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Set content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
}
add_action('after_setup_theme', 'clarkes_terraclean_setup');

/**
 * Enqueue Styles and Scripts
 */
if (!function_exists('clarkes_terraclean_scripts')) {
function clarkes_terraclean_scripts() {
    // Cache-busting versioning using filemtime
    $css_file = get_stylesheet_directory() . '/dist/style.css';
    $css_ver  = file_exists($css_file) ? filemtime($css_file) : null;
    
    wp_enqueue_style(
        'clarkes-terraclean-style',
        get_stylesheet_directory_uri() . '/dist/style.css',
        array(),
        $css_ver
    );
    
    // Cache-busting versioning using filemtime
    $js_file = get_stylesheet_directory() . '/js/theme.js';
    $js_ver  = file_exists($js_file) ? filemtime($js_file) : null;
    
    wp_enqueue_script(
        'clarkes-terraclean-script',
        get_stylesheet_directory_uri() . '/js/theme.js',
        array(),
        $js_ver,
        true
    );
    
    // Localize script with AJAX URL and nonce for contact form
    wp_localize_script('clarkes-terraclean-script', 'CLARKES_CONTACT', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('clarkes_contact_nonce'),
    ));
}
}
add_action('wp_enqueue_scripts', 'clarkes_terraclean_scripts');

/**
 * Add defer attribute to theme script
 */
add_filter('script_loader_tag', function($tag, $handle) {
    if ('clarkes-terraclean-script' === $handle) {
        $tag = str_replace(' src', ' defer src', $tag);
    }
    return $tag;
}, 10, 2);

/**
 * Error handler removed - was causing issues
 * WordPress handles errors natively
 */

/**
 * Create Default Pages on Theme Activation
 */
if (!function_exists('clarkes_terraclean_create_pages')) {
function clarkes_terraclean_create_pages() {
    $pages = array(
        'Home' => array(
            'content' => '<!-- This page uses the front-page.php template -->',
            'template' => '',
        ),
        'About Us' => array(
            'content' => '<!-- About Us page content will be added via WordPress editor -->',
            'template' => 'page-about.php',
        ),
        'Services' => array(
            'content' => '<!-- Services page content will be added via WordPress editor -->',
            'template' => 'page-services.php',
        ),
        'Case Studies' => array(
            'content' => '<!-- Case Studies page content will be added via WordPress editor -->',
            'template' => 'page-case-studies.php',
        ),
        'Testimonials' => array(
            'content' => '<!-- Testimonials page content will be added via WordPress editor -->',
            'template' => 'page-testimonials.php',
        ),
        'Contact Us' => array(
            'content' => '<!-- Contact Us page content will be added via WordPress editor -->',
            'template' => 'page-contact.php',
        ),
    );
    
    foreach ($pages as $title => $page_data) {
        // Check if page already exists
        $page = get_page_by_title($title);
        
        if (!$page) {
            $page_id = wp_insert_post(array(
                'post_title' => $title,
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'page_template' => $page_data['template'],
            ));
            
            // Set Home as front page
            if ($title === 'Home') {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $page_id);
            }
        }
    }
}
}
add_action('after_switch_theme', 'clarkes_terraclean_create_pages');

/**
 * Add Custom Body Classes
 */
function clarkes_terraclean_body_classes($classes) {
    if (is_front_page()) {
        $classes[] = 'is-front-page';
    }
    return $classes;
}
add_filter('body_class', 'clarkes_terraclean_body_classes');

/**
 * Add meta description tag
 */
function clarkes_terraclean_meta_description() {
    // Only output if no SEO plugin is active
    if (function_exists('yoast_breadcrumb') || 
        function_exists('rank_math') || 
        function_exists('wpseo_auto_load') ||
        class_exists('All_in_One_SEO_Pack')) {
        return; // Let SEO plugin handle meta description
    }
    
    // Output on front page and static pages
    if (is_front_page() || is_page()) {
        $description = "Clarke's DPF & Engine Specialists – professional engine decarbonisation, DPF cleaning and EGR cleaning in Kent. Improve MPG, reduce emissions, restore performance.";
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    }
}
}
add_action('wp_head', 'clarkes_terraclean_meta_description', 1);

/**
 * Rewrite menu links to anchors on front page
 */
if (!function_exists('clarkes_terraclean_menu_link_anchors')) {
function clarkes_terraclean_menu_link_anchors($atts, $item, $args) {
    // Only process on front page
    if (!is_front_page()) {
        return $atts;
    }
    
    // Build URL mapping
    $home = trailingslashit(home_url());
    $map = array(
        $home                       => '#top',
        $home . 'about-us/'         => '#about',
        $home . 'services/'         => '#services',
        $home . 'case-studies/'     => '#case-studies',
        $home . 'testimonials/'     => '#testimonials',
        $home . 'contact-us/'       => '#contact',
    );
    
    // Normalise the menu item URL
    $item_url = trailingslashit(strtolower($item->url));
    
    // Check if this URL matches our mapping
    foreach ($map as $real_url => $anchor) {
        $normalised_real = trailingslashit(strtolower($real_url));
        if ($item_url === $normalised_real) {
            $atts['href'] = $anchor;
            $atts['class'] = isset($atts['class']) ? $atts['class'] . ' js-anchor' : 'js-anchor';
            break;
        }
    }
    
    return $atts;
}
}
add_filter('nav_menu_link_attributes', 'clarkes_terraclean_menu_link_anchors', 10, 3);

/**
 * Default menu fallback if no menu is assigned
 */
if (!function_exists('clarkes_terraclean_default_menu')) {
function clarkes_terraclean_default_menu() {
    // Build menu items - use anchors on front page, URLs on other pages
    if (is_front_page()) {
        $pages = array(
            'Home' => '#top',
            'About Us' => '#about',
            'Services' => '#services',
            'Case Studies' => '#case-studies',
            'Testimonials' => '#testimonials',
            'Contact Us' => '#contact',
        );
    } else {
        $pages = array(
            'Home' => home_url('/'),
            'About Us' => home_url('/about-us/'),
            'Services' => home_url('/services/'),
            'Case Studies' => home_url('/case-studies/'),
            'Testimonials' => home_url('/testimonials/'),
            'Contact Us' => home_url('/contact-us/'),
        );
    }
    
    echo '<ul class="flex gap-6 items-center">';
    foreach ($pages as $title => $url) {
        $classes = 'text-text-body hover:text-white transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green';
        
        // Add current class for active page
        if (!is_front_page()) {
            $current_class = (is_page($title) || ($title === 'Home' && is_front_page())) ? 'text-white border-b-2 border-eco-green' : '';
            $classes .= ' ' . $current_class;
        }
        
        // Add js-anchor class for front page anchors
        if (is_front_page() && strpos($url, '#') === 0) {
            $classes .= ' js-anchor';
        }
        
        echo '<li><a href="' . esc_url($url) . '" class="' . esc_attr(trim($classes)) . '">' . esc_html($title) . '</a></li>';
    }
    echo '</ul>';
}
}

/**
 * Contact Form AJAX Handler
 */
if (!function_exists('clarkes_handle_contact_form')) {
function clarkes_handle_contact_form() {
    // Verify nonce
    check_ajax_referer('clarkes_contact_nonce', 'nonce');
    
    // Honeypot check
    if (!empty($_POST['company'])) {
        wp_send_json_error(array('errors' => array('form' => 'Invalid submission.')));
    }
    
    // Collect and sanitize fields
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $vehicle = isset($_POST['vehicle']) ? sanitize_text_field($_POST['vehicle']) : '';
    $issue = isset($_POST['issue']) ? sanitize_textarea_field($_POST['issue']) : '';
    $contact_method = isset($_POST['contact_method']) ? sanitize_text_field($_POST['contact_method']) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
    
    // Validate required fields
    $errors = array();
    
    if (empty($name)) {
        $errors['name'] = 'Name is required.';
    }
    
    if (empty($phone)) {
        $errors['phone'] = 'Phone is required.';
    }
    
    if (empty($vehicle)) {
        $errors['vehicle'] = 'Vehicle information is required.';
    }
    
    if (empty($issue)) {
        $errors['issue'] = 'Issue/symptom is required.';
    }
    
    if (empty($contact_method)) {
        $errors['contact_method'] = 'Please select a contact method.';
    }
    
    // Validate email if provided
    if (!empty($email) && !is_email($email)) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    
    // If validation errors, return them
    if (!empty($errors)) {
        wp_send_json_error(array('errors' => $errors));
    }
    
    // Build email
    // To override contact recipient, use one of these methods:
    // define('CLARKES_CONTACT_TO', 'bookings@clarkesterraclean.co.uk'); // in wp-config.php
    // or add_filter('clarkes_contact_to', fn() => 'bookings@clarkesterraclean.co.uk');
    // or set contact_email in Customizer → Branding
    $customizer_email = get_theme_mod('contact_email', '');
    if (!empty($customizer_email) && is_email($customizer_email)) {
        $to = $customizer_email;
    } else {
        $to = defined('CLARKES_CONTACT_TO') ? CLARKES_CONTACT_TO : apply_filters('clarkes_contact_to', get_option('admin_email'));
    }
    
    // Safety check: ensure we have a valid email
    if (empty($to) || !is_email($to)) {
        $to = get_option('admin_email');
    }
    
    $subject = 'New enquiry from ' . $name;
    
    $body = "New Contact Form Submission\n\n";
    $body .= "Name: " . $name . "\n";
    $body .= "Phone: " . $phone . "\n";
    if (!empty($email)) {
        $body .= "Email: " . $email . "\n";
    }
    $body .= "Vehicle: " . $vehicle . "\n";
    $body .= "Issue/Symptom: " . $issue . "\n";
    $body .= "Preferred Contact Method: " . $contact_method . "\n";
    if (!empty($message)) {
        $body .= "Additional Message: " . $message . "\n";
    }
    $body .= "\nSubmitted: " . current_time('mysql') . "\n";
    $body .= "Site URL: " . home_url() . "\n";
    
    // Set headers
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    
    // Add Reply-To header if email is valid
    if (!empty($email) && is_email($email)) {
        $headers[] = 'Reply-To: ' . $email;
    }
    
    // Send email
    $sent = wp_mail($to, $subject, $body, $headers);
    
    if ($sent) {
        wp_send_json_success(array('message' => 'Thanks — we\'ll get back to you shortly.'));
    } else {
        wp_send_json_error(array('errors' => array('form' => 'Unable to send message. Please try calling us directly.')));
    }
}
add_action('wp_ajax_clarkes_contact', 'clarkes_handle_contact_form');
add_action('wp_ajax_nopriv_clarkes_contact', 'clarkes_handle_contact_form');

/**
 * Include Customizer settings
 */
$customizer_file = get_template_directory() . '/inc/customizer.php';
if (file_exists($customizer_file)) {
    require_once $customizer_file;
}

/**
 * Include Reviews system
 */
$reviews_file = get_template_directory() . '/inc/reviews.php';
if (file_exists($reviews_file)) {
    require_once $reviews_file;
}

/**
 * Include WhatsApp FAB system
 */
$whatsapp_file = get_template_directory() . '/inc/whatsapp.php';
if (file_exists($whatsapp_file)) {
    require_once $whatsapp_file;
}

/**
 * Helper function to get color from theme mod with fallback
 */
if (!function_exists('clarkes_color')) {
function clarkes_color($key, $default = '') {
    $color = get_theme_mod($key, $default);
    return clarkes_sanitize_hex_color($color) ?: $default;
}

}
/**
 * Sanitize hex color
 */
if (!function_exists('clarkes_sanitize_hex_color')) {
function clarkes_sanitize_hex_color($color) {
    if (empty($color)) {
        return '';
    }
    
    // Remove # if present
    $color = ltrim($color, '#');
    
    // Check if valid hex
    if (preg_match('/^([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
        return '#' . $color;
    }
    
    return '';
}

/**
 * Sanitize checkbox
 */
function clarkes_sanitize_checkbox($input) {
    return (isset($input) && true === $input) ? 1 : 0;
}

/**
 * Sanitize select
 */
function clarkes_sanitize_select($input, $setting) {
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Output dynamic CSS variables
 */
function clarkes_output_dynamic_css() {
    $accent = clarkes_color('color_accent', '#4ade80');
    $carbon_dark = clarkes_color('color_dark', '#0f0f0f');
    $carbon_light = clarkes_color('color_light', '#f5f5f5');
    $text_body = clarkes_color('color_text_body', '#d4d4d4');
    $text_dark = clarkes_color('color_text_dark', '#1a1a1a');
    
    // Container max width
    $container_max = get_theme_mod('container_max', '7xl');
    $container_values = array(
        '6xl'  => '72rem',
        '7xl'  => '80rem',
        'full' => '100%',
    );
    $container_max_css = isset($container_values[$container_max]) ? $container_values[$container_max] : '80rem';
    
    // Card roundness
    $card_roundness = get_theme_mod('card_roundness', 'lg');
    $radius_values = array(
        'md'  => '.375rem',
        'lg'  => '.5rem',
        'xl'  => '.75rem',
        '2xl' => '1rem',
    );
    $card_radius_css = isset($radius_values[$card_roundness]) ? $radius_values[$card_roundness] : '.5rem';
    
    ?>
    <style id="clarkes-dynamic-vars">
    :root {
        --accent:       <?php echo esc_html($accent); ?>;
        --carbon-dark:  <?php echo esc_html($carbon_dark); ?>;
        --carbon-light: <?php echo esc_html($carbon_light); ?>;
        --text-body:    <?php echo esc_html($text_body); ?>;
        --text-dark:    <?php echo esc_html($text_dark); ?>;
        --container-max: <?php echo esc_html($container_max_css); ?>;
        --card-radius: <?php echo esc_html($card_radius_css); ?>;
    }
    
    /* Override Tailwind-generated utility colours via higher specificity */
    .text-eco-green { color: var(--accent) !important; }
    .border-eco-green { border-color: var(--accent) !important; }
    .bg-eco-green { background-color: var(--accent) !important; }
    header[role="banner"] { border-color: color-mix(in oklab, var(--accent) 30%, transparent) !important; }
    
    .max-w-7xl { max-width: var(--container-max) !important; }
    .rounded-lg { border-radius: var(--card-radius) !important; }
    </style>
    <?php
}
add_action('wp_head', 'clarkes_output_dynamic_css', 15);

}
/**
 * Check if URL is external
 */
if (!function_exists('clarkes_is_external_url')) {
function clarkes_is_external_url($url) {
    // Treat empty, mailto, tel, and anchor-only as internal
    if (empty($url) || strpos($url, 'mailto:') === 0 || strpos($url, 'tel:') === 0 || strpos($url, '#') === 0) {
        return false;
    }
    
    // Parse the URL
    $parsed = parse_url($url);
    
    // If no host, it's relative (internal)
    if (empty($parsed['host'])) {
        return false;
    }
    
    // Get home URL host
    $home_host = parse_url(home_url(), PHP_URL_HOST);
    
    // Compare hosts (case-insensitive)
    return strtolower($parsed['host']) !== strtolower($home_host);
}

/**
 * Generate link attributes for safe external/internal links
 */
function clarkes_link_attrs($url, $extra_classes = '') {
    $attrs = array();
    
    // Always include href (escaped)
    $attrs[] = 'href="' . esc_url($url) . '"';
    
    // Check if external
    if (clarkes_is_external_url($url)) {
        $attrs[] = 'target="_blank"';
        $attrs[] = 'rel="noopener"';
    }
    
    // Handle classes
    if (!empty($extra_classes)) {
        $attrs[] = 'class="' . esc_attr($extra_classes) . '"';
    }
    
    return implode(' ', $attrs);
}

/**
 * Filter post content to add external link attributes
 */
function clarkes_filter_content_links($content) {
    if (empty($content)) {
        return $content;
    }
    
    // Use DOMDocument for safe parsing
    if (!class_exists('DOMDocument')) {
        return $content;
    }
    
    // Suppress warnings for malformed HTML
    libxml_use_internal_errors(true);
    
    $dom = new DOMDocument();
    $dom->encoding = 'UTF-8';
    
    // Load HTML with proper encoding
    $html = '<?xml encoding="UTF-8">' . $content;
    @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    
    $links = $dom->getElementsByTagName('a');
    
    foreach ($links as $link) {
        $href = $link->getAttribute('href');
        
        if (empty($href)) {
            continue;
        }
        
        // Skip if already has target or is not external
        if ($link->hasAttribute('target') || !clarkes_is_external_url($href)) {
            continue;
        }
        
        // Add target and rel
        $link->setAttribute('target', '_blank');
        $link->setAttribute('rel', 'noopener');
    }
    
    // Get the modified HTML
    $modified = $dom->saveHTML();
    
    // Clean up XML declaration if added
    $modified = preg_replace('/^<\?xml encoding="UTF-8"\?>/i', '', $modified);
    
    libxml_clear_errors();
    
    return $modified;
}
add_filter('the_content', 'clarkes_filter_content_links', 12);

/**
 * Filter nav menu link attributes to add external link attributes
 */
function clarkes_nav_menu_link_attributes($atts, $item, $args) {
    // Only apply to social menu
    if (isset($args->theme_location) && $args->theme_location === 'social_menu') {
        if (isset($atts['href']) && clarkes_is_external_url($atts['href'])) {
            $atts['target'] = '_blank';
            $atts['rel'] = 'noopener';
        }
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'clarkes_nav_menu_link_attributes', 10, 3);
