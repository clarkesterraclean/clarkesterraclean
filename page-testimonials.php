<?php
/**
 * Template Name: Testimonials
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
            <?php
            // Query published reviews
            $reviews_query = new WP_Query(array(
                'post_type'      => 'review',
                'post_status'    => 'publish',
                'posts_per_page' => 12,
                'meta_key'       => 'review_date',
                'orderby'        => 'meta_value',
                'order'          => 'DESC',
            ));

            if ($reviews_query->have_posts()) :
                ?>
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <?php while ($reviews_query->have_posts()) : $reviews_query->the_post(); ?>
                        <?php
                        $reviewer_name = get_post_meta(get_the_ID(), 'reviewer_name', true);
                        $reviewer_location = get_post_meta(get_the_ID(), 'reviewer_location', true);
                        $review_rating = absint(get_post_meta(get_the_ID(), 'review_rating', true));
                        $review_date = get_post_meta(get_the_ID(), 'review_date', true);
                        $display_date = $review_date ? date_i18n(get_option('date_format'), strtotime($review_date)) : get_the_date();
                        ?>
                        <div class="bg-white rounded-lg p-6 border-l-4 border-eco-green shadow-sm">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <p class="font-semibold text-text-dark mb-1"><?php echo esc_html($reviewer_name ?: 'Customer'); ?></p>
                                    <?php if (!empty($reviewer_location)) : ?>
                                        <p class="text-gray-600 text-sm"><?php echo esc_html($reviewer_location); ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php if ($review_rating > 0) : ?>
                                    <div class="text-eco-green text-lg" aria-label="<?php echo esc_attr($review_rating); ?> star rating">
                                        <?php echo str_repeat('★', $review_rating) . str_repeat('☆', 5 - $review_rating); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p class="text-gray-600 text-xs mb-3"><?php echo esc_html($display_date); ?></p>
                            <div class="text-text-dark italic">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php
                wp_reset_postdata();
            else :
                // Fallback to manual testimonials
                ?>
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <!-- Testimonial 1 -->
                    <div class="bg-white rounded-lg p-6 border-l-4 border-eco-green shadow-sm">
                        <p class="text-text-dark italic mb-2">
                            "Night and day difference. Van feels like it's had a new engine."
                        </p>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="bg-white rounded-lg p-6 border-l-4 border-eco-green shadow-sm">
                        <p class="text-text-dark italic mb-2">
                            "Warning light's gone and it pulls properly again – saved me a fortune."
                        </p>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="bg-white rounded-lg p-6 border-l-4 border-eco-green shadow-sm">
                        <p class="text-text-dark italic mb-2">
                            "MPG is up and it's quieter on the motorway. Worth every penny."
                        </p>
                    </div>

                    <!-- Testimonial 4 -->
                    <div class="bg-white rounded-lg p-6 border-l-4 border-eco-green shadow-sm">
                        <p class="text-text-dark italic mb-2">
                            "Booked in quickly, really professional – highly recommend."
                        </p>
                    </div>
                </div>

                <!-- Additional testimonial (full width) -->
                <div class="bg-white rounded-lg p-6 border-l-4 border-eco-green shadow-sm mb-8">
                    <p class="text-text-dark italic mb-2">
                        "Had been getting warnings for months. Thought I'd need a new DPF but they sorted it. No warnings since and it's running better than ever."
                    </p>
                </div>
            <?php endif; ?>

            <!-- Leave a Review Button -->
            <div class="text-center mb-8">
                <a href="<?php echo esc_url(home_url('/leave-a-review/')); ?>" class="inline-block border border-eco-green text-eco-green rounded-full px-6 py-3 text-base font-semibold hover:bg-eco-green hover:text-carbon-dark transition">
                    Leave a Review
                </a>
            </div>

            <!-- Facebook link -->
            <p class="text-center text-text-dark">
                <?php 
                $facebook_url = get_theme_mod('footer_social_facebook', 'https://www.facebook.com/Clarkesterraclean');
                ?>
                See more reviews on our <a <?php echo clarkes_link_attrs($facebook_url, 'text-eco-green hover:underline font-semibold'); ?>>Facebook page</a>.
            </p>
        </article>
    </div>
</main>

<?php
get_footer();

