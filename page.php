<?php
/**
 * The template for displaying all pages
 *
 * @package ClarkesTerraClean
 */

get_header();
?>

<div class="container mx-auto px-4 py-12">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('max-w-4xl mx-auto'); ?>>
            <header class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    <?php the_title(); ?>
                </h1>
            </header>
            
            <div class="bg-white rounded-lg shadow-md p-8 prose prose-lg max-w-none">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<?php
get_footer();

