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
<?php wp_body_open(); ?>

<header class="<?php echo get_theme_mod('header_sticky', 1) ? 'fixed' : 'relative'; ?> top-0 left-0 right-0 z-50 bg-carbon-dark text-text-body border-b border-eco-green/30" role="banner">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">
        <!-- Site Title / Logo -->
        <div class="flex items-center">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-eco-green font-semibold text-base md:text-lg tracking-wide hover:text-eco-green/80 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green">
                <?php echo esc_html(get_bloginfo('name') ?: "Clarke's DPF & Engine Specialists"); ?>
            </a>
        </div>
        
        <!-- Desktop Navigation -->
        <nav class="hidden md:flex gap-6 text-sm font-medium text-text-body" role="navigation" aria-label="Primary Navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary_menu',
                'container' => false,
                'menu_class' => 'flex gap-6 items-center',
                'fallback_cb' => 'clarkes_terraclean_default_menu',
                'depth' => 1,
            ));
            ?>
        </nav>
        
        <!-- Right Side: Phone CTA & Mobile Toggle -->
        <div class="flex items-center gap-4">
            <!-- Desktop Phone CTA -->
            <?php if (get_theme_mod('show_phone_in_header', 1)) : 
                $phone = get_theme_mod('business_phone', '07706 230867');
                $phone_clean = preg_replace('/[^0-9]/', '', $phone);
            ?>
                <a href="tel:<?php echo esc_attr($phone_clean); ?>" aria-label="Call Clarke's DPF & Engine Specialists" class="hidden md:inline-block border border-eco-green text-eco-green rounded-full px-4 py-2 text-sm font-semibold hover:bg-eco-green hover:text-carbon-dark transition whitespace-nowrap focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green">
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
    
    <!-- Mobile Navigation -->
    <div id="mobile-nav" class="hidden bg-carbon-dark border-t border-eco-green/20">
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
                <?php 
                $phone = get_theme_mod('business_phone', '07706 230867');
                $phone_clean = preg_replace('/[^0-9]/', '', $phone);
                ?>
                <a href="tel:<?php echo esc_attr($phone_clean); ?>" aria-label="Call Clarke's DPF & Engine Specialists" class="block py-3 px-4 border-b border-eco-green/10 text-eco-green hover:bg-carbon-dark/60 transition-colors font-semibold focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green">Call <?php echo esc_html($phone); ?></a>
                echo '</div>';
            } else {
                // Fallback if menu items retrieval fails
                echo '<div class="flex flex-col">';
                foreach ($pages as $title => $url) {
                    $anchor_class = (is_front_page() && strpos($url, '#') === 0) ? ' js-anchor' : '';
                    echo '<a href="' . esc_url($url) . '" class="block py-3 px-4 border-b border-eco-green/10 text-text-body hover:bg-carbon-dark/60 transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green' . esc_attr($anchor_class) . '">' . esc_html($title) . '</a>';
                }
                <?php 
                $phone = get_theme_mod('business_phone', '07706 230867');
                $phone_clean = preg_replace('/[^0-9]/', '', $phone);
                ?>
                <a href="tel:<?php echo esc_attr($phone_clean); ?>" aria-label="Call Clarke's DPF & Engine Specialists" class="block py-3 px-4 border-b border-eco-green/10 text-eco-green hover:bg-carbon-dark/60 transition-colors font-semibold focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green">Call <?php echo esc_html($phone); ?></a>
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
<div class="h-16"></div>
<?php endif; ?>

<main role="main">
