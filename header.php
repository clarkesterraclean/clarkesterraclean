<!DOCTYPE html>
<html lang="en-GB" <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    // Open Graph / Twitter meta tags (only if no SEO plugin is active)
    if (!function_exists('yoast_breadcrumb') && 
        !function_exists('rank_math') && 
        !function_exists('wpseo_auto_load') &&
        !class_exists('All_in_One_SEO_Pack')) {
        $og_description = "Clarke's DPF & Engine Specialists – professional engine decarbonisation, DPF cleaning and EGR cleaning in Kent. Improve MPG, reduce emissions, restore performance.";
        ?>
        <meta property="og:title" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <meta property="og:description" content="<?php echo esc_attr($og_description); ?>">
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>">
        <meta name="twitter:card" content="summary_large_image">
        <?php
    }
    ?>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if (function_exists('wp_body_open')) { wp_body_open(); } ?>

<?php
// Get header settings
$header_layout = get_theme_mod('header_layout', 'default');
$header_sticky = get_theme_mod('header_sticky', 1);
$header_height = get_theme_mod('header_height', 64);
$header_bg_color = get_theme_mod('header_bg_color', '#0f0f0f');
$header_bg_image_id = get_theme_mod('header_bg_image', '');
$header_text_color = get_theme_mod('header_text_color', '#d4d4d4');
$header_link_color = get_theme_mod('header_link_color', '#d4d4d4');
$header_link_hover_color = get_theme_mod('header_link_hover_color', '#4ade80');
$header_border_color = get_theme_mod('header_border_color', '#4ade804d');
$header_padding_vertical = get_theme_mod('header_padding_vertical', 0);
$header_padding_horizontal = get_theme_mod('header_padding_horizontal', 16);
$header_nav_font_size = get_theme_mod('header_nav_font_size', 14);
$header_nav_font_weight = get_theme_mod('header_nav_font_weight', '500');
$header_nav_letter_spacing = get_theme_mod('header_nav_letter_spacing', 0);
$header_logo_size = get_theme_mod('header_logo_size', 'medium');
$mobile_menu_bg_color = get_theme_mod('mobile_menu_bg_color', '#0f0f0f');
$phone_button_style = get_theme_mod('header_phone_button_style', 'outline');

// Build header style
$header_style = 'background-color: ' . esc_attr($header_bg_color) . ';';
$header_style .= ' color: ' . esc_attr($header_text_color) . ';';
$header_style .= ' border-bottom-color: ' . esc_attr($header_border_color) . ';';
$header_style .= ' padding-top: ' . absint($header_padding_vertical) . 'px;';
$header_style .= ' padding-bottom: ' . absint($header_padding_vertical) . 'px;';
$header_style .= ' padding-left: ' . absint($header_padding_horizontal) . 'px;';
$header_style .= ' padding-right: ' . absint($header_padding_horizontal) . 'px;';
$header_style .= ' height: ' . absint($header_height) . 'px;';

if ($header_bg_image_id) {
    $header_bg_image_url = wp_get_attachment_image_url($header_bg_image_id, 'full');
    if ($header_bg_image_url) {
        $header_style .= ' background-image: url(' . esc_url($header_bg_image_url) . ');';
        $header_style .= ' background-size: cover; background-position: center;';
    }
}

// Logo size classes
$logo_size_classes = array(
    'small'  => 'text-sm',
    'medium' => 'text-base md:text-lg',
    'large'  => 'text-lg md:text-xl',
    'xlarge' => 'text-xl md:text-2xl',
);
$logo_class = isset($logo_size_classes[$header_logo_size]) ? $logo_size_classes[$header_logo_size] : $logo_size_classes['medium'];

// Nav style
$nav_style = 'font-size: ' . absint($header_nav_font_size) . 'px;';
$nav_style .= ' font-weight: ' . esc_attr($header_nav_font_weight) . ';';
$nav_style .= ' letter-spacing: ' . floatval($header_nav_letter_spacing) . 'px;';
$nav_style .= ' color: ' . esc_attr($header_link_color) . ';';
?>
<header 
    id="site-header"
    class="header-layout-<?php echo esc_attr($header_layout); ?> <?php echo $header_sticky ? 'fixed' : 'relative'; ?> top-0 left-0 right-0 z-50 border-b" 
    role="banner"
    style="<?php echo $header_style; ?>"
    data-header-layout="<?php echo esc_attr($header_layout); ?>"
    data-header-sticky="<?php echo $header_sticky ? '1' : '0'; ?>"
>
    <?php
    // Get phone settings
    $show_phone = get_theme_mod('show_phone_in_header', 1);
    $phone = get_theme_mod('business_phone', '07706 230867');
    $phone_clean = preg_replace('/[^0-9]/', '', $phone);
    
    // Build phone button classes based on style
    $phone_button_classes = 'hidden md:inline-block rounded-full px-4 py-2 text-sm font-semibold transition whitespace-nowrap focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green';
    switch ($phone_button_style) {
        case 'solid':
            $phone_button_classes .= ' bg-eco-green text-carbon-dark hover:bg-eco-green/90';
            break;
        case 'text':
            $phone_button_classes .= ' text-eco-green hover:text-eco-green/80';
            break;
        case 'outline':
        default:
            $phone_button_classes .= ' border border-eco-green text-eco-green hover:bg-eco-green hover:text-carbon-dark';
            break;
    }
    
    // Render different layouts based on header_layout setting
    if ($header_layout === 'centered') : ?>
        <!-- Centered Layout: Logo Center, Nav Below -->
        <div class="max-w-7xl mx-auto" style="height: <?php echo absint($header_height); ?>px;">
            <div class="flex flex-col items-center justify-center h-full">
                <!-- Logo Center -->
                <div class="flex items-center mb-2">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo font-semibold <?php echo esc_attr($logo_class); ?> tracking-wide transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green" style="color: <?php echo esc_attr($header_link_color); ?>;">
                        <?php echo esc_html(get_bloginfo('name') ?: "Clarke's DPF & Engine Specialists"); ?>
                    </a>
                </div>
                
                <!-- Navigation Below -->
                <nav class="header-nav hidden md:flex gap-6 font-medium" role="navigation" aria-label="Primary Navigation" style="<?php echo $nav_style; ?>">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary_menu',
                        'container' => false,
                        'menu_class' => 'flex gap-6 items-center',
                        'fallback_cb' => 'clarkes_terraclean_default_menu',
                        'depth' => 1,
                        'link_before' => '<span class="header-nav-link">',
                        'link_after' => '</span>',
                    ));
                    ?>
                </nav>
                
                <!-- Mobile Toggle -->
                <button id="mobile-menu-toggle" class="md:hidden absolute top-1/2 right-4 transform -translate-y-1/2 text-text-body hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green transition-colors" aria-label="Toggle mobile menu" aria-expanded="false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
    <?php elseif ($header_layout === 'split') : ?>
        <!-- Split Layout: Logo Left, Nav Center, CTA Right -->
        <div class="max-w-7xl mx-auto flex items-center justify-between" style="height: <?php echo absint($header_height); ?>px;">
            <!-- Logo Left -->
            <div class="flex items-center flex-1">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo font-semibold <?php echo esc_attr($logo_class); ?> tracking-wide transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green" style="color: <?php echo esc_attr($header_link_color); ?>;">
                    <?php echo esc_html(get_bloginfo('name') ?: "Clarke's DPF & Engine Specialists"); ?>
                </a>
            </div>
            
            <!-- Navigation Center -->
            <nav class="header-nav hidden md:flex gap-6 font-medium flex-1 justify-center" role="navigation" aria-label="Primary Navigation" style="<?php echo $nav_style; ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary_menu',
                    'container' => false,
                    'menu_class' => 'flex gap-6 items-center',
                    'fallback_cb' => 'clarkes_terraclean_default_menu',
                    'depth' => 1,
                    'link_before' => '<span class="header-nav-link">',
                    'link_after' => '</span>',
                ));
                ?>
            </nav>
            
            <!-- Right Side: Phone CTA & Mobile Toggle -->
            <div class="flex items-center gap-4 flex-1 justify-end">
                <?php if ($show_phone) : ?>
                    <a href="tel:<?php echo esc_attr($phone_clean); ?>" aria-label="Call Clarke's DPF & Engine Specialists" class="header-phone-button <?php echo esc_attr($phone_button_classes); ?>" data-button-style="<?php echo esc_attr($phone_button_style); ?>">
                        Call <?php echo esc_html($phone); ?>
                    </a>
                <?php endif; ?>
                
                <button id="mobile-menu-toggle" class="md:hidden text-text-body hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green transition-colors" aria-label="Toggle mobile menu" aria-expanded="false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
    <?php elseif ($header_layout === 'simple') : ?>
        <!-- Simple Layout: Logo Left, Nav Right, No CTA -->
        <div class="max-w-7xl mx-auto flex items-center justify-between" style="height: <?php echo absint($header_height); ?>px;">
            <!-- Site Title / Logo -->
            <div class="flex items-center">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo font-semibold <?php echo esc_attr($logo_class); ?> tracking-wide transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green" style="color: <?php echo esc_attr($header_link_color); ?>;">
                    <?php echo esc_html(get_bloginfo('name') ?: "Clarke's DPF & Engine Specialists"); ?>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <nav class="header-nav hidden md:flex gap-6 font-medium" role="navigation" aria-label="Primary Navigation" style="<?php echo $nav_style; ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary_menu',
                    'container' => false,
                    'menu_class' => 'flex gap-6 items-center',
                    'fallback_cb' => 'clarkes_terraclean_default_menu',
                    'depth' => 1,
                    'link_before' => '<span class="header-nav-link">',
                    'link_after' => '</span>',
                ));
                ?>
            </nav>
            
            <!-- Mobile Toggle Button Only -->
            <button id="mobile-menu-toggle" class="md:hidden text-text-body hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green transition-colors" aria-label="Toggle mobile menu" aria-expanded="false">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
    <?php elseif ($header_layout === 'minimal') : ?>
        <!-- Minimal Layout: Logo Only -->
        <div class="max-w-7xl mx-auto flex items-center justify-between" style="height: <?php echo absint($header_height); ?>px;">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo font-semibold <?php echo esc_attr($logo_class); ?> tracking-wide transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green" style="color: <?php echo esc_attr($header_link_color); ?>;">
                    <?php echo esc_html(get_bloginfo('name') ?: "Clarke's DPF & Engine Specialists"); ?>
                </a>
            </div>
            
            <!-- Mobile Toggle Only -->
            <button id="mobile-menu-toggle" class="md:hidden text-text-body hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green transition-colors" aria-label="Toggle mobile menu" aria-expanded="false">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
    <?php else : ?>
        <!-- Default Layout: Logo Left, Nav Right -->
        <div class="max-w-7xl mx-auto flex items-center justify-between" style="height: <?php echo absint($header_height); ?>px;">
            <!-- Site Title / Logo -->
            <div class="flex items-center">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo font-semibold <?php echo esc_attr($logo_class); ?> tracking-wide transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green" style="color: <?php echo esc_attr($header_link_color); ?>;">
                    <?php echo esc_html(get_bloginfo('name') ?: "Clarke's DPF & Engine Specialists"); ?>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <nav class="header-nav hidden md:flex gap-6 font-medium" role="navigation" aria-label="Primary Navigation" style="<?php echo $nav_style; ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary_menu',
                    'container' => false,
                    'menu_class' => 'flex gap-6 items-center',
                    'fallback_cb' => 'clarkes_terraclean_default_menu',
                    'depth' => 1,
                    'link_before' => '<span class="header-nav-link">',
                    'link_after' => '</span>',
                ));
                ?>
            </nav>
            
            <!-- Right Side: Phone CTA & Mobile Toggle -->
            <div class="flex items-center gap-4">
                <!-- Desktop Phone CTA -->
                <?php if ($show_phone) : ?>
                    <a href="tel:<?php echo esc_attr($phone_clean); ?>" aria-label="Call Clarke's DPF & Engine Specialists" class="header-phone-button <?php echo esc_attr($phone_button_classes); ?>" data-button-style="<?php echo esc_attr($phone_button_style); ?>">
                        Call <?php echo esc_html($phone); ?>
                    </a>
                <?php endif; ?>
                
                <!-- Mobile Toggle Button -->
                <button id="mobile-menu-toggle" class="md:hidden text-text-body hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green transition-colors" aria-label="Toggle mobile menu" aria-expanded="false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Mobile Navigation -->
    <div id="mobile-nav" class="hidden border-t" style="background-color: <?php echo esc_attr($mobile_menu_bg_color); ?>; border-top-color: <?php echo esc_attr($header_border_color); ?>;">
        <?php
        // Mobile menu - use same fallback logic with anchors on front page
        $menu_locations = get_nav_menu_locations();
        
        // Build pages array based on whether we're on front page
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
        
        if (has_nav_menu('primary_menu') && isset($menu_locations['primary_menu'])) {
            $menu_items = wp_get_nav_menu_items($menu_locations['primary_menu']);
            if ($menu_items && !is_wp_error($menu_items)) {
                echo '<div class="flex flex-col">';
                foreach ($menu_items as $item) {
                    // Apply filter to get rewritten URL if on front page
                    $item_url = $item->url;
                    if (is_front_page()) {
                        $home = trailingslashit(home_url());
                        $map = array(
                            $home                       => '#top',
                            $home . 'about-us/'         => '#about',
                            $home . 'services/'         => '#services',
                            $home . 'case-studies/'     => '#case-studies',
                            $home . 'testimonials/'     => '#testimonials',
                            $home . 'contact-us/'       => '#contact',
                        );
                        $item_url_normalised = trailingslashit(strtolower($item_url));
                        foreach ($map as $real_url => $anchor) {
                            $normalised_real = trailingslashit(strtolower($real_url));
                            if ($item_url_normalised === $normalised_real) {
                                $item_url = $anchor;
                                break;
                            }
                        }
                    }
                    $anchor_class = (is_front_page() && strpos($item_url, '#') === 0) ? ' js-anchor' : '';
                    echo '<a href="' . esc_url($item_url) . '" class="block py-3 px-4 border-b border-eco-green/10 text-text-body hover:bg-carbon-dark/60 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green' . esc_attr($anchor_class) . '">' . esc_html($item->title) . '</a>';
                }
                $phone = get_theme_mod('business_phone', '07706 230867');
                $phone_clean = preg_replace('/[^0-9]/', '', $phone);
                echo '<a href="tel:' . esc_attr($phone_clean) . '" aria-label="Call Clarke\'s DPF & Engine Specialists" class="block py-3 px-4 border-b border-eco-green/10 text-eco-green hover:bg-carbon-dark/60 transition-colors font-semibold focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green">Call ' . esc_html($phone) . '</a>';
                echo '</div>';
            } else {
                // Fallback if menu items retrieval fails
                echo '<div class="flex flex-col">';
                foreach ($pages as $title => $url) {
                    $anchor_class = (is_front_page() && strpos($url, '#') === 0) ? ' js-anchor' : '';
                    echo '<a href="' . esc_url($url) . '" class="block py-3 px-4 border-b border-eco-green/10 text-text-body hover:bg-carbon-dark/60 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green' . esc_attr($anchor_class) . '">' . esc_html($title) . '</a>';
                }
                $phone = get_theme_mod('business_phone', '07706 230867');
                $phone_clean = preg_replace('/[^0-9]/', '', $phone);
                echo '<a href="tel:' . esc_attr($phone_clean) . '" aria-label="Call Clarke\'s DPF & Engine Specialists" class="block py-3 px-4 border-b border-eco-green/10 text-eco-green hover:bg-carbon-dark/60 transition-colors font-semibold focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green">Call ' . esc_html($phone) . '</a>';
                echo '</div>';
            }
        } else {
            // Fallback menu for mobile
            echo '<div class="flex flex-col">';
            foreach ($pages as $title => $url) {
                $anchor_class = (is_front_page() && strpos($url, '#') === 0) ? ' js-anchor' : '';
                echo '<a href="' . esc_url($url) . '" class="block py-3 px-4 border-b border-eco-green/10 text-text-body hover:bg-carbon-dark/60 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green' . esc_attr($anchor_class) . '">' . esc_html($title) . '</a>';
            }
            $phone = get_theme_mod('business_phone', '07706 230867');
            $phone_clean = preg_replace('/[^0-9]/', '', $phone);
                echo '<a href="tel:' . esc_attr($phone_clean) . '" aria-label="Call Clarke\'s DPF & Engine Specialists" class="block py-3 px-4 border-b border-eco-green/10 text-eco-green hover:bg-carbon-dark/60 transition-colors font-semibold focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green">Call ' . esc_html($phone) . '</a>';
            echo '</div>';
        }
        ?>
    </div>
</header>

<!-- Spacer to prevent content from going under fixed header -->
<?php if (get_theme_mod('header_sticky', 1)) : ?>
<div id="header-spacer" style="height: <?php echo absint($header_height); ?>px;"></div>
<?php endif; ?>

<main role="main">
