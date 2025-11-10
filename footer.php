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
<?php
// Get footer settings
$footer_bg_color = get_theme_mod('footer_bg_color', '#0f0f0f');
$footer_text_color = get_theme_mod('footer_text_color', '#d4d4d4');
$footer_link_color = get_theme_mod('footer_link_color', '#d4d4d4');
$footer_link_hover_color = get_theme_mod('footer_link_hover_color', '#4ade80');
$footer_border_color = get_theme_mod('footer_border_color', '#4ade804d');
$footer_padding_top = get_theme_mod('footer_padding_top', 64);
$footer_padding_bottom = get_theme_mod('footer_padding_bottom', 32);
$footer_padding_horizontal = get_theme_mod('footer_padding_horizontal', 16);
$footer_font_size = get_theme_mod('footer_font_size', 14);
$footer_heading_font_size = get_theme_mod('footer_heading_font_size', 18);
$footer_bg_image_id = get_theme_mod('footer_bg_image', '');

// Build footer style
$footer_style = 'background-color: ' . esc_attr($footer_bg_color) . ';';
$footer_style .= ' color: ' . esc_attr($footer_text_color) . ';';
$footer_style .= ' padding-top: ' . absint($footer_padding_top) . 'px;';
$footer_style .= ' padding-bottom: ' . absint($footer_padding_bottom) . 'px;';
$footer_style .= ' padding-left: ' . absint($footer_padding_horizontal) . 'px;';
$footer_style .= ' padding-right: ' . absint($footer_padding_horizontal) . 'px;';
$footer_style .= ' font-size: ' . absint($footer_font_size) . 'px;';

if ($footer_bg_image_id) {
    $footer_bg_image_url = wp_get_attachment_image_url($footer_bg_image_id, 'full');
    if ($footer_bg_image_url) {
        $footer_style .= ' background-image: url(' . esc_url($footer_bg_image_url) . ');';
        $footer_style .= ' background-size: cover; background-position: center;';
    }
}
?>
<footer id="site-footer" class="mt-16" role="contentinfo" style="<?php echo $footer_style; ?>">
    <div class="max-w-7xl mx-auto grid <?php echo esc_attr($grid_class); ?> gap-8">
        <!-- Column 1: Brand -->
        <div>
            <h3 class="font-semibold mb-2" style="color: <?php echo esc_attr($footer_text_color); ?>; font-size: <?php echo absint($footer_heading_font_size); ?>px;">Clarke's DPF & Engine Specialists</h3>
            <p class="brand-tagline text-sm" style="color: <?php echo esc_attr($footer_text_color); ?>;">
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
                <h3 class="font-semibold mb-4" style="color: <?php echo esc_attr($footer_text_color); ?>; font-size: <?php echo absint($footer_heading_font_size); ?>px;">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>" class="footer-link transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green" style="color: <?php echo esc_attr($footer_link_color); ?>;">Home</a></li>
                    <li><a href="<?php echo esc_url(home_url('/services/')); ?>" class="footer-link transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green" style="color: <?php echo esc_attr($footer_link_color); ?>;">Services</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="footer-link transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green" style="color: <?php echo esc_attr($footer_link_color); ?>;">Contact</a></li>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- Footer Menu (if assigned and enabled) -->
            <?php if ($has_footer_menu && $footer_show_menu) : ?>
            <div>
                <h3 class="font-semibold mb-4" style="color: <?php echo esc_attr($footer_text_color); ?>; font-size: <?php echo absint($footer_heading_font_size); ?>px;">Quick Links</h3>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer_menu',
                    'container'      => false,
                    'menu_class'     => 'space-y-2 text-sm footer-links',
                    'fallback_cb'    => false,
                    'depth'          => 1,
                ));
                ?>
            </div>
            <?php endif; ?>
            
            <!-- Column 3: Contact -->
            <div>
                <h3 class="font-semibold mb-4" style="color: <?php echo esc_attr($footer_text_color); ?>; font-size: <?php echo absint($footer_heading_font_size); ?>px;">Contact</h3>
                <a href="tel:<?php echo esc_attr($phone_clean); ?>" aria-label="Call Clarke's DPF & Engine Specialists" class="inline-block border border-eco-green text-eco-green rounded-full px-4 py-2 text-sm font-semibold hover:bg-eco-green hover:text-carbon-dark transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green">
                    Call <?php echo esc_html($phone); ?>
                </a>
            </div>
            
            <!-- Column 4: Social -->
            <div>
                <h3 class="font-semibold mb-4" style="color: <?php echo esc_attr($footer_text_color); ?>; font-size: <?php echo absint($footer_heading_font_size); ?>px;">Follow Us</h3>
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
    <?php 
    $footer_copyright_text = get_theme_mod('footer_copyright_text', '© ' . date('Y') . ' Clarke\'s DPF & Engine Specialists. All rights reserved.');
    if (get_theme_mod('footer_show_copyright', 1)) :
    ?>
    <div class="border-t mt-8 pt-6 text-xs" style="border-top-color: <?php echo esc_attr($footer_border_color); ?>; color: <?php echo esc_attr($footer_text_color); ?>;">
        <div class="max-w-7xl mx-auto text-center">
            <?php echo esc_html($footer_copyright_text); ?>
        </div>
    </div>
    <?php endif; ?>
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
