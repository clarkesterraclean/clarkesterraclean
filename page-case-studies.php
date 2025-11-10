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

            <?php 
            $case_studies_query = clarkes_get_case_studies();
            if ($case_studies_query->have_posts()) :
                while ($case_studies_query->have_posts()) : $case_studies_query->the_post();
                    $vehicle = get_post_meta(get_the_ID(), 'vehicle_make_model', true);
                    $problem = get_post_meta(get_the_ID(), 'problem', true);
                    $dealer_said = get_post_meta(get_the_ID(), 'dealer_said', true);
                    $what_we_did = get_post_meta(get_the_ID(), 'what_we_did', true);
                    $result = get_post_meta(get_the_ID(), 'result', true);
                    if (empty($vehicle)) $vehicle = get_the_title();
            ?>
            <section class="mb-12 bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="h-64 overflow-hidden">
                        <?php the_post_thumbnail('large', array('class' => 'w-full h-full object-cover')); ?>
                    </div>
                <?php endif; ?>
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-6 text-eco-green"><?php echo esc_html($vehicle); ?></h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <?php if (!empty($problem)) : ?>
                            <div>
                                <h3 class="text-lg font-semibold mb-2 text-text-dark">Problem</h3>
                                <p class="leading-relaxed"><?php echo esc_html($problem); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($dealer_said)) : ?>
                            <div>
                                <h3 class="text-lg font-semibold mb-2 text-text-dark">Dealer Said</h3>
                                <p class="leading-relaxed"><?php echo esc_html($dealer_said); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="space-y-4">
                            <?php if (!empty($what_we_did)) : ?>
                            <div>
                                <h3 class="text-lg font-semibold mb-2 text-eco-green">What We Did</h3>
                                <p class="leading-relaxed"><?php echo esc_html($what_we_did); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($result)) : ?>
                            <div>
                                <h3 class="text-lg font-semibold mb-2 text-eco-green">Result</h3>
                                <p class="leading-relaxed"><?php echo esc_html($result); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (get_the_content()) : ?>
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
            <?php 
                endwhile;
                wp_reset_postdata();
            else :
            ?>
            <p class="text-gray-700">No case studies have been added yet. Go to <strong>Case Studies → Add New</strong> to create your first case study.</p>
            <?php endif; ?>

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

