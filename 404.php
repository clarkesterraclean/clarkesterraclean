<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package ClarkesTerraClean
 */

get_header();
?>

<main class="py-16 md:py-24 bg-carbon-dark text-text-body min-h-screen flex items-center">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Page Not Found</h1>
        <p class="text-xl text-text-body mb-8 max-w-2xl mx-auto">
            Sorry, we couldn't find the page you're looking for. It may have been moved or deleted.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="border border-eco-green text-eco-green rounded-full px-6 py-3 text-base font-semibold hover:bg-eco-green hover:text-carbon-dark transition inline-block">
                Go to Home
            </a>
            <a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="inline-block rounded-full border border-text-body/40 px-4 py-2 text-sm font-semibold hover:bg-white hover:text-carbon-dark transition">
                Contact Us
            </a>
        </div>
        
        <p class="text-text-body text-lg">
            Need help? Call us at <?php 
            $phone = get_theme_mod('business_phone', '07706 230867');
            $phone_clean = preg_replace('/[^0-9]/', '', $phone);
            ?><a href="tel:<?php echo esc_attr($phone_clean); ?>" class="text-eco-green hover:text-white transition-colors font-semibold"><?php echo esc_html($phone); ?></a>
        </p>
    </div>
</main>

<?php
get_footer();

