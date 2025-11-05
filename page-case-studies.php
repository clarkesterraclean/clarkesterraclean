<?php
/**
 * Template Name: Case Studies
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
            <p class="mb-8 italic text-gray-700">
                Below are real-world examples of how our engine decarbonisation services have helped customers. Results vary depending on vehicle condition, age, and driving patterns.
            </p>

            <!-- Case Study 1: Audi A4 TDI -->
            <section class="mb-8 bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                <h2 class="text-2xl font-semibold mb-4 text-eco-green">Audi A4 TDI</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Problem</h3>
                        <p>Warning light and limp mode activation. Vehicle losing power and entering restricted performance mode.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Dealer Said</h3>
                        <p>"New DPF needed" – replacement cost estimated at over £1,500.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">What We Did</h3>
                        <p>DPF cleaning service to remove carbon deposits and restore exhaust flow through the blocked filter.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Result</h3>
                        <p>Warning light cleared, full performance restored. Vehicle no longer entering limp mode. Customer avoided costly DPF replacement.</p>
                    </div>
                </div>
            </section>

            <!-- Case Study 2: Ford Transit -->
            <section class="mb-8 bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
                <h2 class="text-2xl font-semibold mb-4 text-eco-green">Ford Transit</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Problem</h3>
                        <p>Low MPG and sluggish throttle response. Vehicle felt underpowered, especially when loaded.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">What We Did</h3>
                        <p>Full engine decarbonisation service covering engine carbon cleaning, EGR valve cleaning, and injector cleaning.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Result</h3>
                        <p>MPG improved by approximately 10–15% (typical improvement range). Throttle response restored to normal operation. Vehicle performing as expected.</p>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="mt-10 p-6 bg-white rounded-lg border border-eco-green/20 text-center">
                <p class="mb-4">
                    Want to see similar results for your vehicle?
                </p>
                <a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="border border-eco-green text-eco-green rounded-full px-6 py-2 text-sm font-semibold hover:bg-eco-green hover:text-carbon-dark transition inline-block">
                    Contact Us
                </a>
            </section>
        </article>
    </div>
</main>

<?php
get_footer();

