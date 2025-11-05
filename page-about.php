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
            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">Who We Are</h2>
                <p>
                    Clarke's DPF & Engine Specialists are Kent-based, independent specialists. We provide professional engine decarbonisation services using specialist equipment and detergents. Our team is trained to deliver the highest standard of service to restore your vehicle's performance.
                </p>
            </section>

            <!-- What We Do -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">What We Do</h2>
                <p>
                    We use specialist decarbonisation equipment and detergents to remove carbon build-up from your engine, fuel system, and emissions components. Unlike quick fixes that simply clear warning codes, we treat the root cause by thoroughly cleaning carbon deposits, soot, and restrictions that cause poor performance, warning lights, and reduced fuel economy.
                </p>
                <p>
                    The process works by introducing a specialised cleaning fluid through your fuel system while the engine runs. This removes carbon deposits from critical areas including the intake manifold, combustion chambers, EGR valves, and exhaust system components.
                </p>
            </section>

            <!-- Why Carbon Builds Up -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">Why Carbon Builds Up</h2>
                <p>
                    Modern driving patterns, particularly short journeys and city driving, don't allow engines to reach optimal operating temperatures. This leads to incomplete combustion and the build-up of carbon deposits and soot in critical engine components.
                </p>
                <p>
                    Diesel Particulate Filters (DPFs) and Exhaust Gas Recirculation (EGR) valves are especially susceptible. Short journeys prevent DPFs from completing their regeneration cycle, causing soot to accumulate and eventually block the filter. EGR valves recirculate exhaust gases to reduce emissions, but this also introduces carbon deposits that cause the valves to stick or become restricted.
                </p>
            </section>

            <!-- Our Approach -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold mb-4">Our Approach</h2>
                <p>
                    Many garages simply clear warning codes or reset error messages. This doesn't fix the underlying problem – it just turns off the warning light temporarily. The carbon deposits, soot restrictions, and blocked components remain, meaning the symptoms will return.
                </p>
                <p>
                    We don't just clear codes. We treat the root cause by thoroughly cleaning carbon and soot restrictions from your engine and emissions systems. Our engine decarbonisation service removes built-up deposits, restores proper airflow, and allows your engine to perform as it was designed to.
                </p>
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

