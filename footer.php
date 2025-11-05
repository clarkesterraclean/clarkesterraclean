</main>

<?php
$footer_layout = get_theme_mod('footer_layout', '4-col');
$footer_show_menu = get_theme_mod('footer_show_menu', 1);
$footer_show_widgets = get_theme_mod('footer_show_widgets', 1);
$phone = get_theme_mod('business_phone', '07706 230867');
$phone_clean = preg_replace('/[^0-9]/', '', $phone);
$tagline = get_theme_mod('brand_tagline', 'Lower emissions. Restore performance. Improve MPG.');
$facebook_url = get_theme_mod('footer_social_facebook', 'https://www.facebook.com/Clarkesterraclean');

// Grid column classes based on layout
$grid_classes = array(
    '1-col' => 'grid-cols-1',
    '2-col' => 'grid-cols-1 sm:grid-cols-2',
    '3-col' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    '4-col' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
);
$grid_class = isset($grid_classes[$footer_layout]) ? $grid_classes[$footer_layout] : $grid_classes['4-col'];

// Count active widgets
$active_widgets = 0;
if ($footer_show_widgets) {
    for ($i = 1; $i <= 4; $i++) {
        if (is_active_sidebar('footer-' . $i)) {
            $active_widgets++;
        }
    }
}

// Check if footer menu is assigned and should be shown
$has_footer_menu = false;
if ($footer_show_menu) {
    $menu_locations = get_nav_menu_locations();
    $has_footer_menu = isset($menu_locations['footer_menu']) && $menu_locations['footer_menu'] > 0;
}

// Check if social menu is assigned
$has_social_menu = false;
$menu_locations = get_nav_menu_locations();
if (isset($menu_locations['social_menu']) && $menu_locations['social_menu'] > 0) {
    $has_social_menu = true;
}
?>
<footer class="bg-carbon-dark text-text-body mt-16" role="contentinfo">
    <div class="max-w-7xl mx-auto px-4 py-12 grid <?php echo esc_attr($grid_class); ?> gap-8">
        <!-- Column 1: Brand -->
        <div>
            <h3 class="text-white font-semibold mb-2">Clarke's DPF & Engine Specialists</h3>
            <p class="brand-tagline text-text-body text-sm">
                <?php echo esc_html($tagline); ?>
            </p>
        </div>
        
        <?php if ($footer_show_widgets && $active_widgets > 0) : ?>
            <!-- Widget Areas -->
            <?php for ($i = 1; $i <= 4; $i++) : ?>
                <?php if (is_active_sidebar('footer-' . $i)) : ?>
                    <div>
                        <?php dynamic_sidebar('footer-' . $i); ?>
                    </div>
                <?php endif; ?>
            <?php endfor; ?>
        <?php else : ?>
            <!-- Fallback: Quick Links -->
            <?php if (!$has_footer_menu || !$footer_show_menu) : ?>
            <div>
                <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-white transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green">Home</a></li>
                    <li><a href="<?php echo esc_url(home_url('/services/')); ?>" class="hover:text-white transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green">Services</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="hover:text-white transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green">Contact</a></li>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- Footer Menu (if assigned and enabled) -->
            <?php if ($has_footer_menu && $footer_show_menu) : ?>
            <div>
                <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer_menu',
                    'container'      => false,
                    'menu_class'     => 'space-y-2 text-sm',
                    'fallback_cb'    => false,
                    'depth'          => 1,
                ));
                ?>
            </div>
            <?php endif; ?>
            
            <!-- Column 3: Contact -->
            <div>
                <h3 class="text-white font-semibold mb-4">Contact</h3>
                <a href="tel:<?php echo esc_attr($phone_clean); ?>" aria-label="Call Clarke's DPF & Engine Specialists" class="inline-block border border-eco-green text-eco-green rounded-full px-4 py-2 text-sm font-semibold hover:bg-eco-green hover:text-carbon-dark transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green">
                    Call <?php echo esc_html($phone); ?>
                </a>
            </div>
            
            <!-- Column 4: Social -->
            <div>
                <h3 class="text-white font-semibold mb-4">Follow Us</h3>
                <?php if ($has_social_menu) : ?>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'social_menu',
                        'container'      => false,
                        'menu_class'     => 'space-y-2 text-sm',
                        'link_before'    => '<span class="sr-only">',
                        'link_after'     => '</span>',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    ));
                    ?>
                <?php else : ?>
                    <?php if (!empty($facebook_url)) : ?>
                    <a <?php echo clarkes_link_attrs($facebook_url, 'text-text-body hover:text-white transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green inline-block'); ?> aria-label="Visit our Facebook page">
                        Facebook
                    </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Bottom Bar -->
    <div class="border-t border-eco-green/20 mt-8 pt-6 text-xs text-text-body/80">
        <div class="max-w-7xl mx-auto px-4 text-center">
            © <?php echo date('Y'); ?> Clarke's DPF & Engine Specialists. All rights reserved.
        </div>
    </div>
</footer>

<?php
// LocalBusiness JSON-LD Schema
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "AutomotiveRepair",
  "name": "Clarke's DPF & Engine Specialists",
  "url": "https://clarkesterraclean.co.uk/",
  "telephone": "<?php echo esc_js($phone); ?>",
  "areaServed": {
    "@type": "City",
    "name": "Kent, UK"
  },
  "description": "DPF, EGR & engine decarbonisation specialists for petrol and diesel vehicles in Kent.",
  "sameAs": [
    <?php if (!empty($facebook_url)) : ?>"<?php echo esc_js($facebook_url); ?>"<?php endif; ?>
  ]
}
</script>

<?php wp_footer(); ?>
</body>
</html>
