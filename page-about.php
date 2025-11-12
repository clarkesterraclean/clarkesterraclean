<?php
/**
 * Template Name: About
 *
 * @package ClarkesTerraClean
 */

get_header();
?>

<main class="py-16 md:py-24 bg-carbon-light text-text-dark">
    <div class="max-w-4xl mx-auto px-4 space-y-8">
        <header>
            <h1 class="text-3xl md:text-4xl font-semibold mb-4"><?php the_title(); ?></h1>
            <?php if (has_excerpt()) : ?>
                <p class="text-gray-700"><?php the_excerpt(); ?></p>
            <?php endif; ?>
        </header>

        <article class="prose prose-neutral max-w-none">
            <!-- Who We Are -->
            <section class="mb-12">
                <div class="grid md:grid-cols-2 gap-8 items-center mb-8">
                    <div>
                        <h2 class="text-2xl font-semibold mb-4">Who We Are</h2>
                        <p class="text-lg leading-relaxed">
                            <strong class="text-eco-green">Clarke's DPF & Engine Specialists</strong> are Kent-based, independent specialists with years of experience in engine decarbonisation. We provide professional services using specialist equipment and detergents to restore your vehicle's performance.
                        </p>
                        <p class="leading-relaxed mt-4">
                            Our team is trained to deliver the highest standard of service, treating each vehicle with the care and attention it deserves. We understand that your vehicle is essential to your daily life, and we're committed to getting you back on the road with improved performance and reliability.
                        </p>
                    </div>
                    <?php 
                    $about_page_video_id = get_theme_mod('about_section_video', '');
                    $about_page_image_id = get_theme_mod('about_section_image', '');
                    
                    if ($about_page_video_id) {
                        $about_page_video_url = wp_get_attachment_url($about_page_video_id);
                        if ($about_page_video_url) {
                            echo '<div class="order-first md:order-last">';
                            echo '<div class="rounded-lg shadow-lg overflow-hidden">';
                            echo '<video class="w-full h-auto" controls playsinline>';
                            echo '<source src="' . esc_url($about_page_video_url) . '" type="' . esc_attr(get_post_mime_type($about_page_video_id)) . '">';
                            echo 'Your browser does not support the video tag.';
                            echo '</video>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } elseif ($about_page_image_id) {
                        echo '<div class="order-first md:order-last">';
                        echo wp_get_attachment_image($about_page_image_id, 'large', false, array('class' => 'rounded-lg shadow-lg w-full h-auto'));
                        echo '</div>';
                    }
                    ?>
                </div>
            </section>

            <!-- What We Do -->
            <section class="mb-12 bg-white rounded-lg p-8 border-l-4 border-eco-green shadow-sm">
                <h2 class="text-2xl font-semibold mb-6">What We Do</h2>
                <div class="space-y-4">
                    <p class="leading-relaxed">
                        We use <strong>specialist decarbonisation equipment and detergents</strong> to remove carbon build-up from your engine, fuel system, and emissions components. Unlike quick fixes that simply clear warning codes, we treat the root cause by thoroughly cleaning carbon deposits, soot, and restrictions that cause poor performance, warning lights, and reduced fuel economy.
                    </p>
                    <p class="leading-relaxed">
                        The process works by introducing a specialised cleaning fluid through your fuel system while the engine runs. This removes carbon deposits from critical areas including:
                    </p>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Intake manifold and throttle body</li>
                        <li>Combustion chambers and piston crowns</li>
                        <li>EGR valves and EGR cooler</li>
                        <li>Diesel Particulate Filters (DPF)</li>
                        <li>Exhaust system components</li>
                        <li>Fuel injectors and fuel system</li>
                    </ul>
                </div>
            </section>

            <!-- Why Carbon Builds Up -->
            <section class="mb-12">
                <h2 class="text-2xl font-semibold mb-6">Why Carbon Builds Up</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-carbon-light rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-3 text-eco-green">Modern Driving Patterns</h3>
                        <p class="leading-relaxed">
                            Short journeys and city driving don't allow engines to reach optimal operating temperatures. This leads to incomplete combustion and the build-up of carbon deposits and soot in critical engine components.
                        </p>
                    </div>
                    <div class="bg-carbon-light rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-3 text-eco-green">DPF & EGR Issues</h3>
                        <p class="leading-relaxed">
                            Diesel Particulate Filters (DPFs) and Exhaust Gas Recirculation (EGR) valves are especially susceptible. Short journeys prevent DPFs from completing their regeneration cycle, causing soot to accumulate and eventually block the filter.
                        </p>
                    </div>
                </div>
                <p class="mt-6 leading-relaxed">
                    EGR valves recirculate exhaust gases to reduce emissions, but this also introduces carbon deposits that cause the valves to stick or become restricted. Over time, this build-up becomes severe enough to trigger warning lights and affect performance.
                </p>
            </section>

            <!-- Our Approach -->
            <section class="mb-12">
                <h2 class="text-2xl font-semibold mb-6">Our Approach</h2>
                <div class="bg-white rounded-lg p-8 border border-eco-green/20 shadow-sm">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3 text-text-dark">The Problem with Quick Fixes</h3>
                        <p class="leading-relaxed">
                            Many garages simply clear warning codes or reset error messages. This doesn't fix the underlying problem – it just turns off the warning light temporarily. The carbon deposits, soot restrictions, and blocked components remain, meaning the symptoms will return, often within days or weeks.
                        </p>
                    </div>
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold mb-3 text-eco-green">Our Solution</h3>
                        <p class="leading-relaxed">
                            We don't just clear codes. <strong>We treat the root cause</strong> by thoroughly cleaning carbon and soot restrictions from your engine and emissions systems. Our engine decarbonisation service removes built-up deposits, restores proper airflow, and allows your engine to perform as it was designed to – giving you improved MPG, restored power, and peace of mind.
                        </p>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="mt-10 p-6 bg-white rounded-lg border border-eco-green/20">
                <p class="mb-4">
                    Ready to restore your engine's performance? Get in touch with us today to book your service.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="border border-eco-green text-eco-green rounded-full px-6 py-2 text-sm font-semibold hover:bg-eco-green hover:text-carbon-dark transition text-center">
                        Contact Us
                    </a>
                    <?php 
                    $phone = get_theme_mod('business_phone', '07706 230867');
                    $phone_clean = preg_replace('/[^0-9]/', '', $phone);
                    ?>
                    <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="border border-eco-green text-eco-green rounded-full px-6 py-2 text-sm font-semibold hover:bg-eco-green hover:text-carbon-dark transition text-center">
                        Call <?php echo esc_html($phone); ?>
                    </a>
                </div>
            </section>
        </article>
    </div>
</main>

<?php
get_footer();

