<?php
/**
 * Template Name: Recent Work Gallery
 * 
 * @package ClarkesTerraClean
 */

get_header();
?>

<main id="main" class="site-main">
    <section class="recent-work-hero" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 80px 20px; text-align: center; color: white;">
        <div class="max-w-7xl mx-auto">
            <h1 style="font-size: 48px; font-weight: 700; margin-bottom: 20px;">Recent Work Gallery</h1>
            <p style="font-size: 20px; opacity: 0.9; max-width: 600px; margin: 0 auto;">See the quality of our work through before and after transformations</p>
        </div>
    </section>
    
    <section class="recent-work-content" style="padding: 60px 20px;">
        <div class="max-w-7xl mx-auto">
            <?php
            $recent_work_query = clarkes_get_recent_work(-1);
            
            if ($recent_work_query->have_posts()) :
            ?>
            <div class="recent-work-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 40px; margin-top: 40px;">
                <?php while ($recent_work_query->have_posts()) : $recent_work_query->the_post();
                    $before_image = get_post_meta(get_the_ID(), '_before_image', true);
                    $after_image = get_post_meta(get_the_ID(), '_after_image', true);
                    $vehicle = get_post_meta(get_the_ID(), '_vehicle_make_model', true);
                    $service_type = get_post_meta(get_the_ID(), '_service_type', true);
                    $work_date = get_post_meta(get_the_ID(), '_work_date', true);
                ?>
                <article class="recent-work-card" style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s;">
                    <?php if ($before_image && $after_image) : ?>
                        <div class="before-after-slider-container" style="position: relative; height: 400px; overflow: hidden; background: #000;">
                            <div class="before-after-wrapper" style="position: relative; width: 100%; height: 100%;">
                                <div class="before-image-wrapper" style="position: absolute; width: 100%; height: 100%;">
                                    <?php echo wp_get_attachment_image($before_image, 'large', false, array('style' => 'width:100%;height:100%;object-fit:cover;', 'alt' => 'Before')); ?>
                                    <div class="before-label" style="position: absolute; bottom: 15px; left: 15px; background: rgba(0,0,0,0.8); color: white; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 700; letter-spacing: 1px;">BEFORE</div>
                                </div>
                                <div class="after-image-wrapper" style="position: absolute; width: 50%; height: 100%; right: 0; overflow: hidden; border-left: 4px solid #10b981;">
                                    <?php echo wp_get_attachment_image($after_image, 'large', false, array('style' => 'width:200%;height:100%;object-fit:cover;margin-left:-100%;', 'alt' => 'After')); ?>
                                    <div class="after-label" style="position: absolute; bottom: 15px; right: 15px; background: rgba(16,185,129,0.95); color: white; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 700; letter-spacing: 1px;">AFTER</div>
                                </div>
                                <div class="slider-handle" style="position: absolute; top: 0; left: 50%; width: 4px; height: 100%; background: #10b981; cursor: ew-resize; z-index: 10; transition: background 0.2s;">
                                    <div class="handle-circle" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 50px; height: 50px; background: #10b981; border-radius: 50%; border: 4px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
                                            <path d="M8.5 12l5-5 5 5M8.5 12l5 5 5-5"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php elseif (has_post_thumbnail()) : ?>
                        <div style="height: 400px; overflow: hidden;">
                            <?php the_post_thumbnail('large', array('style' => 'width:100%;height:100%;object-fit:cover;')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div style="padding: 30px;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                            <div>
                                <h2 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 700; color: #1f2937;">
                                    <?php echo esc_html($vehicle ?: get_the_title()); ?>
                                </h2>
                                <?php if ($work_date) : ?>
                                    <p style="margin: 0; color: #6b7280; font-size: 14px;">
                                        <?php echo esc_html(date_i18n('F j, Y', strtotime($work_date))); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <?php if ($service_type) : ?>
                                <span style="display: inline-block; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <?php echo esc_html($service_type); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div style="color: #4b5563; font-size: 15px; line-height: 1.8; margin-top: 15px;">
                            <?php 
                            if (has_excerpt()) {
                                the_excerpt();
                            } else {
                                echo wp_trim_words(get_the_content(), 30);
                            }
                            ?>
                        </div>
                        
                        <?php if (get_the_content()) : ?>
                            <button class="read-more-btn" style="margin-top: 20px; background: transparent; border: 2px solid #10b981; color: #10b981; padding: 10px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s;" onclick="this.nextElementSibling.style.display='block'; this.style.display='none';">
                                <?php _e('Read More', 'clarkes-terraclean'); ?>
                            </button>
                            <div class="full-content" style="display: none; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #4b5563; line-height: 1.8;">
                                <?php the_content(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <?php else : ?>
                <div style="text-align: center; padding: 60px 20px;">
                    <p style="font-size: 18px; color: #6b7280;"><?php _e('No recent work items found.', 'clarkes-terraclean'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<style>
.recent-work-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.slider-handle:hover {
    background: #059669;
}

.slider-handle:hover .handle-circle {
    background: #059669;
    transform: translate(-50%, -50%) scale(1.1);
}

.read-more-btn:hover {
    background: #10b981;
    color: white;
}
</style>

<script>
(function() {
    document.querySelectorAll('.before-after-wrapper').forEach(function(wrapper) {
        var handle = wrapper.querySelector('.slider-handle');
        var afterImage = wrapper.querySelector('.after-image-wrapper');
        var isDragging = false;
        var startX = 0;
        var startPercent = 50;
        
        function updateSlider(x) {
            var rect = wrapper.getBoundingClientRect();
            var percent = Math.max(0, Math.min(100, ((x - rect.left) / rect.width) * 100));
            
            handle.style.left = percent + '%';
            afterImage.style.width = percent + '%';
        }
        
        function handleStart(e) {
            isDragging = true;
            startX = e.clientX || e.touches[0].clientX;
            var rect = wrapper.getBoundingClientRect();
            startPercent = ((startX - rect.left) / rect.width) * 100;
            wrapper.style.userSelect = 'none';
            e.preventDefault();
        }
        
        function handleMove(e) {
            if (!isDragging) return;
            var currentX = e.clientX || e.touches[0].clientX;
            var diff = currentX - startX;
            var rect = wrapper.getBoundingClientRect();
            var newPercent = startPercent + (diff / rect.width) * 100;
            updateSlider((newPercent / 100) * rect.width + rect.left);
            e.preventDefault();
        }
        
        function handleEnd() {
            isDragging = false;
            wrapper.style.userSelect = '';
        }
        
        handle.addEventListener('mousedown', handleStart);
        document.addEventListener('mousemove', handleMove);
        document.addEventListener('mouseup', handleEnd);
        
        handle.addEventListener('touchstart', handleStart);
        document.addEventListener('touchmove', handleMove);
        document.addEventListener('touchend', handleEnd);
        
        wrapper.addEventListener('click', function(e) {
            if (!isDragging) {
                updateSlider(e.clientX || e.touches[0].clientX);
            }
        });
    });
})();
</script>

<?php
get_footer();

