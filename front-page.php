<?php
/**
 * The front page template
 * Complete scrolling homepage for Clarke's DPF & Engine Specialists
 *
 * @package ClarkesTerraClean
 */

get_header();
?>

<!-- Hero Section -->
<?php
$hero_title = get_theme_mod('hero_title', 'Restore Performance. Reduce Emissions. Save Fuel.');
$hero_subtitle = get_theme_mod('hero_subtitle', 'Professional engine decarbonisation in Kent — DPF, EGR and injector specialists.');
$hero_bg_image = get_theme_mod('hero_bg_image', '');
$hero_height = get_theme_mod('hero_height', '70vh');
$hero_bg_style = '';
if (!empty($hero_bg_image)) {
    $hero_bg_url = wp_get_attachment_image_url($hero_bg_image, 'full');
    if ($hero_bg_url) {
        $hero_bg_style = 'background-image:url(' . esc_url($hero_bg_url) . '); background-size:cover; background-position:center;';
    }
}
?>
<section id="top" class="flex items-center bg-carbon-dark text-text-body pt-24 md:pt-32 pb-16 md:pb-24" style="min-height:<?php echo esc_attr($hero_height); ?>; <?php echo esc_attr($hero_bg_style); ?>">
    <div class="max-w-7xl mx-auto px-4 w-full">
        <div class="max-w-4xl">
            <h1 class="hero-title text-4xl md:text-5xl font-bold text-white leading-tight">
                <?php echo esc_html($hero_title); ?>
            </h1>
            <p class="hero-subtitle mt-4 text-lg md:text-xl max-w-2xl text-text-body">
                <?php echo esc_html($hero_subtitle); ?>
            </p>
            
            <!-- Bullet list with green accent card -->
            <div class="mt-8 bg-carbon-dark/40 border-l-4 border-eco-green p-4 space-y-2 text-sm md:text-base max-w-md">
                <div class="flex items-start">
                    <span class="block w-1 h-5 bg-eco-green mr-3 mt-1 flex-shrink-0"></span>
                    <span class="text-text-body">Improved fuel economy (MPG)</span>
                </div>
                <div class="flex items-start">
                    <span class="block w-1 h-5 bg-eco-green mr-3 mt-1 flex-shrink-0"></span>
                    <span class="text-text-body">Smoother, quieter engine</span>
                </div>
                <div class="flex items-start">
                    <span class="block w-1 h-5 bg-eco-green mr-3 mt-1 flex-shrink-0"></span>
                    <span class="text-text-body">Lower emissions</span>
                </div>
                <div class="flex items-start">
                    <span class="block w-1 h-5 bg-eco-green mr-3 mt-1 flex-shrink-0"></span>
                    <span class="text-text-body">Restored throttle response</span>
                </div>
            </div>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mt-8">
                <?php 
                $phone = get_theme_mod('business_phone', '07706 230867');
                $phone_clean = preg_replace('/[^0-9]/', '', $phone);
                ?>
                <a href="tel:<?php echo esc_attr($phone_clean); ?>" aria-label="Call Clarke's DPF & Engine Specialists" class="border border-eco-green text-eco-green rounded-full px-6 py-3 text-base font-semibold hover:bg-eco-green hover:text-carbon-dark transition text-center whitespace-nowrap focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green">
                    Call <?php echo esc_html($phone); ?>
                </a>
                <a href="#contact" class="inline-block rounded-full border border-text-body/40 px-4 py-2 text-sm font-semibold hover:bg-white hover:text-carbon-dark transition text-center whitespace-nowrap focus-visible:outline focus-visible:outline-2 focus-visible:outline-eco-green">
                    Book a Service
                </a>
            </div>
        </div>
    </div>
</section>

<!-- About Us Section -->
<?php if (get_theme_mod('show_about', 1)) : ?>
<section id="about" class="py-16 md:py-24 bg-carbon-light text-text-dark">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <!-- Image Column -->
            <div class="order-2 md:order-1">
                <?php 
                $about_image_id = get_theme_mod('about_section_image', '');
                if ($about_image_id) {
                    $about_image = wp_get_attachment_image($about_image_id, 'large', false, array('class' => 'rounded-lg shadow-lg w-full h-auto object-cover'));
                    echo $about_image;
                } else {
                    // Placeholder for when image is uploaded
                    echo '<div class="bg-carbon-dark/10 rounded-lg shadow-lg aspect-[4/3] flex items-center justify-center text-text-dark/40">';
                    echo '<p class="text-center">Image placeholder<br><span class="text-sm">Upload image via Customizer</span></p>';
                    echo '</div>';
                }
                ?>
            </div>
            
            <!-- Content Column -->
            <div class="order-1 md:order-2">
                <h2 class="text-3xl md:text-4xl font-semibold mb-6">Why Choose Clarke's?</h2>
                <div class="prose prose-lg max-w-none space-y-6">
                    <p class="text-lg leading-relaxed">
                        <strong class="text-eco-green">Kent's trusted engine decarbonisation specialists.</strong> We use professional-grade equipment and specialist detergents to remove carbon build-up from engines, fuel systems, and emissions components – restoring performance without costly replacements.
                    </p>
                    <p class="leading-relaxed">
                        Modern driving patterns, especially short journeys and city driving, cause soot and carbon deposits to accumulate in critical components like DPFs, EGR valves, and fuel injectors. Many garages simply clear warning codes, but that's just a temporary fix. <strong>We treat the root cause</strong> by thoroughly cleaning your engine's critical components using professional decarbonisation equipment.
                    </p>
                    <div class="bg-white rounded-lg p-6 border-l-4 border-eco-green shadow-sm">
                        <p class="font-semibold text-text-dark mb-2">The Result?</p>
                        <p class="text-text-dark">
                            Whether you're experiencing reduced MPG, warning lights, limp mode, or sluggish performance, our engine decarbonisation service restores your engine to optimal condition – saving you thousands compared to replacement parts.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Services Section -->
<?php if (get_theme_mod('show_services', 1)) : ?>
<section id="services" class="py-16 md:py-24 bg-white text-text-dark">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-semibold mb-8 text-center">Our Services</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Service 1: Engine Carbon Cleaning -->
            <div class="bg-carbon-light rounded-lg overflow-hidden border border-gray-200 hover:border-eco-green hover:shadow-lg transition-all duration-300">
                <?php 
                $service1_image_id = get_theme_mod('service_1_image', '');
                if ($service1_image_id) {
                    echo '<div class="h-48 overflow-hidden">';
                    echo wp_get_attachment_image($service1_image_id, 'medium', false, array('class' => 'w-full h-full object-cover'));
                    echo '</div>';
                }
                ?>
                <div class="p-6">
                    <div class="mb-4">
                        <div class="w-12 h-12 bg-eco-green/20 rounded-lg flex items-center justify-center mb-4">
                            <span class="text-2xl">⚙️</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Engine Carbon Cleaning</h3>
                    </div>
                    <p class="text-text-dark mb-4 leading-relaxed">
                        Professional decarbonisation of intake and combustion areas using specialist equipment. Removes carbon deposits that restrict airflow and reduce power.
                    </p>
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="text-eco-green font-medium hover:underline inline-flex items-center group">
                        Learn more <span class="ml-1 group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                </div>
            </div>
            
            <!-- Service 2: DPF Cleaning -->
            <div class="bg-carbon-light rounded-lg overflow-hidden border border-gray-200 hover:border-eco-green hover:shadow-lg transition-all duration-300">
                <?php 
                $service2_image_id = get_theme_mod('service_2_image', '');
                if ($service2_image_id) {
                    echo '<div class="h-48 overflow-hidden">';
                    echo wp_get_attachment_image($service2_image_id, 'medium', false, array('class' => 'w-full h-full object-cover'));
                    echo '</div>';
                }
                ?>
                <div class="p-6">
                    <div class="mb-4">
                        <div class="w-12 h-12 bg-eco-green/20 rounded-lg flex items-center justify-center mb-4">
                            <span class="text-2xl">🔧</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">DPF Cleaning</h3>
                    </div>
                    <p class="text-text-dark mb-4 leading-relaxed">
                        Restore blocked Diesel Particulate Filters and prevent costly replacements. Our cleaning service removes soot build-up that triggers warning lights and limp mode.
                    </p>
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="text-eco-green font-medium hover:underline inline-flex items-center group">
                        Learn more <span class="ml-1 group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                </div>
            </div>
            
            <!-- Service 3: EGR Valve Cleaning -->
            <div class="bg-carbon-light rounded-lg overflow-hidden border border-gray-200 hover:border-eco-green hover:shadow-lg transition-all duration-300">
                <?php 
                $service3_image_id = get_theme_mod('service_3_image', '');
                if ($service3_image_id) {
                    echo '<div class="h-48 overflow-hidden">';
                    echo wp_get_attachment_image($service3_image_id, 'medium', false, array('class' => 'w-full h-full object-cover'));
                    echo '</div>';
                }
                ?>
                <div class="p-6">
                    <div class="mb-4">
                        <div class="w-12 h-12 bg-eco-green/20 rounded-lg flex items-center justify-center mb-4">
                            <span class="text-2xl">🌬️</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">EGR Valve Cleaning</h3>
                    </div>
                    <p class="text-text-dark mb-4 leading-relaxed">
                        Clean sticking and sooted EGR valves to improve airflow, reduce emissions, and restore proper engine operation. Prevents rough idle and stalling.
                    </p>
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="text-eco-green font-medium hover:underline inline-flex items-center group">
                        Learn more <span class="ml-1 group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                </div>
            </div>
            
            <!-- Service 4: Injector Cleaning & Diagnostics -->
            <div class="bg-carbon-light rounded-lg overflow-hidden border border-gray-200 hover:border-eco-green hover:shadow-lg transition-all duration-300">
                <?php 
                $service4_image_id = get_theme_mod('service_4_image', '');
                if ($service4_image_id) {
                    echo '<div class="h-48 overflow-hidden">';
                    echo wp_get_attachment_image($service4_image_id, 'medium', false, array('class' => 'w-full h-full object-cover'));
                    echo '</div>';
                }
                ?>
                <div class="p-6">
                    <div class="mb-4">
                        <div class="w-12 h-12 bg-eco-green/20 rounded-lg flex items-center justify-center mb-4">
                            <span class="text-2xl">💉</span>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Injector Cleaning & Diagnostics</h3>
                    </div>
                    <p class="text-text-dark mb-4 leading-relaxed">
                        Diagnose and correct rough idle, poor spray patterns, and performance loss. Our cleaning service restores proper fuel delivery and combustion efficiency.
                    </p>
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="text-eco-green font-medium hover:underline inline-flex items-center group">
                        Learn more <span class="ml-1 group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Case Studies Section -->
<?php if (get_theme_mod('show_case_studies', 1)) : ?>
<section id="case-studies" class="py-16 md:py-24 bg-carbon-light text-text-dark">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-semibold mb-8 text-center">Recent Results</h2>
        
        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto mb-8">
            <!-- Case Study 1: Audi A4 TDI -->
            <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <?php 
                $case1_image_id = get_theme_mod('case_study_1_image', '');
                if ($case1_image_id) {
                    echo '<div class="h-48 overflow-hidden">';
                    echo wp_get_attachment_image($case1_image_id, 'medium', false, array('class' => 'w-full h-full object-cover'));
                    echo '</div>';
                }
                ?>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-3 text-eco-green">Audi A4 TDI</h3>
                    <p class="text-sm text-gray-600 mb-4 font-medium">Blocked DPF / Limp Mode</p>
                    <p class="text-text-dark leading-relaxed">
                        Dealer recommended a new DPF at over £1,500. We performed our professional DPF cleaning service and restored exhaust flow – warning light cleared, full performance restored, no replacement needed.
                    </p>
                </div>
            </div>
            
            <!-- Case Study 2: Ford Transit -->
            <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                <?php 
                $case2_image_id = get_theme_mod('case_study_2_image', '');
                if ($case2_image_id) {
                    echo '<div class="h-48 overflow-hidden">';
                    echo wp_get_attachment_image($case2_image_id, 'medium', false, array('class' => 'w-full h-full object-cover'));
                    echo '</div>';
                }
                ?>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-3 text-eco-green">Ford Transit</h3>
                    <p class="text-sm text-gray-600 mb-4 font-medium">Poor MPG / Sluggish Performance</p>
                    <p class="text-text-dark leading-relaxed">
                        Full engine decarbonisation service including carbon cleaning, EGR valve cleaning, and injector cleaning. Result: MPG improved by 10-15%, throttle response fully restored, vehicle performing as expected.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="text-center">
            <a href="<?php echo esc_url(home_url('/case-studies/')); ?>" class="text-eco-green font-semibold hover:underline inline-flex items-center">
                View more →
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Testimonials Section -->
<?php if (get_theme_mod('show_testimonials', 1)) : ?>
<section id="testimonials" class="py-16 md:py-24 bg-carbon-dark text-text-body">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-semibold mb-8 text-center text-white">What Drivers Say</h2>
        
        <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto mb-8">
            <!-- Testimonial 1 -->
            <div class="bg-carbon-dark border-l-4 border-eco-green p-6 rounded-r-lg">
                <p class="text-text-body italic mb-4">
                    "Night and day difference. Van feels like it's had a new engine."
                </p>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="bg-carbon-dark border-l-4 border-eco-green p-6 rounded-r-lg">
                <p class="text-text-body italic mb-4">
                    "Warning light's gone and it pulls properly again – saved me a fortune."
                </p>
            </div>
            
            <!-- Testimonial 3 -->
            <div class="bg-carbon-dark border-l-4 border-eco-green p-6 rounded-r-lg">
                <p class="text-text-body italic mb-4">
                    "MPG is up and it's quieter on the motorway. Worth every penny."
                </p>
            </div>
        </div>
        
        <p class="text-center text-text-body text-sm">
            <?php 
            $facebook_url = get_theme_mod('footer_social_facebook', 'https://www.facebook.com/Clarkesterraclean');
            ?>
            More reviews available on our <a <?php echo clarkes_link_attrs($facebook_url, 'text-eco-green hover:underline'); ?>>Facebook page</a>.
        </p>
    </div>
</section>
<?php endif; ?>

<!-- Contact Section -->
<?php if (get_theme_mod('show_contact', 1)) : ?>
<section id="contact" class="py-16 md:py-24 bg-carbon-dark text-text-body">
    <div class="max-w-7xl mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-semibold mb-4 text-center text-white">Book Your Service</h2>
            <p class="text-center text-text-body mb-8 text-lg">
                Based in Kent. Mobile options available. Get your engine breathing properly again.
            </p>
            
            <!-- Phone CTA -->
            <div class="text-center mb-10">
                <?php 
                $phone = get_theme_mod('business_phone', '07706 230867');
                $phone_clean = preg_replace('/[^0-9]/', '', $phone);
                ?>
                <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="inline-block border border-eco-green text-eco-green rounded-full px-8 py-4 text-lg font-semibold hover:bg-eco-green hover:text-carbon-dark transition">
                    📞 Call <?php echo esc_html($phone); ?>
                </a>
            </div>
            
            <!-- Contact Form -->
            <form data-contact-form method="post" class="bg-carbon-dark border border-eco-green/30 rounded-lg p-8 space-y-6">
                <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('clarkes_contact_nonce')); ?>">
                <input type="text" name="company" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
                
                <div>
                    <label for="contact-name" class="block text-text-body font-medium mb-2">Name</label>
                    <input type="text" id="contact-name" name="name" required class="w-full px-4 py-2 bg-carbon-dark border border-eco-green/30 rounded text-text-body focus:outline-none focus:border-eco-green" placeholder="Your name">
                    <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
                </div>
                
                <div>
                    <label for="contact-phone" class="block text-text-body font-medium mb-2">Phone</label>
                    <input type="tel" id="contact-phone" name="phone" required class="w-full px-4 py-2 bg-carbon-dark border border-eco-green/30 rounded text-text-body focus:outline-none focus:border-eco-green" placeholder="07700 000000">
                    <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
                </div>
                
                <div>
                    <label for="contact-email" class="block text-text-body font-medium mb-2">Email</label>
                    <input type="email" id="contact-email" name="email" class="w-full px-4 py-2 bg-carbon-dark border border-eco-green/30 rounded text-text-body focus:outline-none focus:border-eco-green" placeholder="your.email@example.com">
                    <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
                </div>
                
                <div>
                    <label for="contact-vehicle" class="block text-text-body font-medium mb-2">Vehicle (make/model/engine)</label>
                    <input type="text" id="contact-vehicle" name="vehicle" required class="w-full px-4 py-2 bg-carbon-dark border border-eco-green/30 rounded text-text-body focus:outline-none focus:border-eco-green" placeholder="e.g. Ford Focus 1.6 TDCi">
                    <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
                </div>
                
                <div>
                    <label for="contact-issue" class="block text-text-body font-medium mb-2">Issue/Symptom</label>
                    <textarea id="contact-issue" name="issue" rows="4" required class="w-full px-4 py-2 bg-carbon-dark border border-eco-green/30 rounded text-text-body focus:outline-none focus:border-eco-green" placeholder="e.g. Warning light, poor MPG, limp mode..."></textarea>
                    <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
                </div>
                
                <div>
                    <label for="contact-method" class="block text-text-body font-medium mb-2">Preferred Contact Method</label>
                    <select id="contact-method" name="contact_method" required class="w-full px-4 py-2 bg-carbon-dark border border-eco-green/30 rounded text-text-body focus:outline-none focus:border-eco-green">
                        <option value="">Please select...</option>
                        <option value="phone">Phone</option>
                        <option value="email">Email</option>
                        <option value="text">Text Message</option>
                    </select>
                    <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
                </div>
                
                <div>
                    <label for="contact-message" class="block text-text-body font-medium mb-2">Message</label>
                    <textarea id="contact-message" name="message" rows="4" class="w-full px-4 py-2 bg-carbon-dark border border-eco-green/30 rounded text-text-body focus:outline-none focus:border-eco-green" placeholder="Any additional information..."></textarea>
                    <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="border border-eco-green text-eco-green rounded-full px-8 py-3 text-base font-semibold hover:bg-eco-green hover:text-carbon-dark transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="submit-text">Send Enquiry</span>
                    </button>
                    <p class="error-message text-red-600 text-sm mt-2 hidden"></p>
                </div>
                
                <p class="text-center text-text-body text-sm mt-6">
                    We'll get back to you as soon as possible to confirm availability and pricing for your vehicle.
                </p>
            </form>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
get_footer();
