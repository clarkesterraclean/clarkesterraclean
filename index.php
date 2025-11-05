<?php
/**
 * The main template file
 *
 * @package ClarkesTerraClean
 */

get_header();
?>

<div class="container mx-auto px-4 py-12">
    <?php if (have_posts()) : ?>
        <div class="grid gap-8">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-md p-6'); ?>>
                    <header class="mb-4">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-green-500 transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        <div class="text-sm text-gray-600">
                            <time datetime="<?php echo get_the_date('c'); ?>">
                                <?php echo get_the_date(); ?>
                            </time>
                        </div>
                    </header>
                    
                    <div class="prose max-w-none">
                        <?php the_excerpt(); ?>
                    </div>
                    
                    <footer class="mt-4">
                        <a href="<?php the_permalink(); ?>" class="inline-block bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 transition-colors">
                            Read More
                        </a>
                    </footer>
                </article>
            <?php endwhile; ?>
        </div>
        
        <?php
        the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => '&laquo; Previous',
            'next_text' => 'Next &raquo;',
        ));
        ?>
        
    <?php else : ?>
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Nothing Found</h2>
            <p class="text-gray-600">It looks like nothing was found at this location.</p>
        </div>
    <?php endif; ?>
</div>

<?php
get_footer();

