<?php
/**
 * Recent Work Gallery System
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Recent Work Custom Post Type
 */
if (!function_exists('clarkes_register_recent_work_post_type')) {
function clarkes_register_recent_work_post_type() {
    $labels = array(
        'name'                  => _x('Recent Work', 'Post type general name', 'clarkes-terraclean'),
        'singular_name'         => _x('Work Item', 'Post type singular name', 'clarkes-terraclean'),
        'menu_name'             => _x('Recent Work', 'Admin Menu text', 'clarkes-terraclean'),
        'name_admin_bar'        => _x('Work Item', 'Add New on Toolbar', 'clarkes-terraclean'),
        'add_new'               => __('Add New', 'clarkes-terraclean'),
        'add_new_item'          => __('Add New Work Item', 'clarkes-terraclean'),
        'new_item'              => __('New Work Item', 'clarkes-terraclean'),
        'edit_item'             => __('Edit Work Item', 'clarkes-terraclean'),
        'view_item'             => __('View Work Item', 'clarkes-terraclean'),
        'all_items'             => __('All Work Items', 'clarkes-terraclean'),
        'search_items'          => __('Search Work Items', 'clarkes-terraclean'),
        'not_found'             => __('No work items found.', 'clarkes-terraclean'),
        'not_found_in_trash'    => __('No work items found in Trash.', 'clarkes-terraclean'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-camera',
        'menu_position'      => 23,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
        'show_in_rest'       => true,
    );

    register_post_type('recent_work', $args);
}
}
add_action('init', 'clarkes_register_recent_work_post_type');

/**
 * Add Before/After Image Meta Boxes
 */
if (!function_exists('clarkes_add_recent_work_meta_boxes')) {
function clarkes_add_recent_work_meta_boxes() {
    add_meta_box(
        'recent_work_images',
        __('Before & After Images', 'clarkes-terraclean'),
        'clarkes_recent_work_images_meta_box',
        'recent_work',
        'normal',
        'high'
    );
    
    add_meta_box(
        'recent_work_details',
        __('Work Details', 'clarkes-terraclean'),
        'clarkes_recent_work_details_meta_box',
        'recent_work',
        'side',
        'default'
    );
}
}
add_action('add_meta_boxes', 'clarkes_add_recent_work_meta_boxes');

/**
 * Before/After Images Meta Box
 */
if (!function_exists('clarkes_recent_work_images_meta_box')) {
function clarkes_recent_work_images_meta_box($post) {
    wp_nonce_field('clarkes_save_recent_work', 'clarkes_recent_work_nonce');
    
    $before_image = get_post_meta($post->ID, '_before_image', true);
    $after_image = get_post_meta($post->ID, '_after_image', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="before_image"><?php _e('Before Image', 'clarkes-terraclean'); ?></label></th>
            <td>
                <input type="hidden" id="before_image" name="before_image" value="<?php echo esc_attr($before_image); ?>" />
                <div id="before_image_preview" style="margin-bottom: 10px;">
                    <?php if ($before_image) : ?>
                        <?php echo wp_get_attachment_image($before_image, 'medium'); ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="button" id="upload_before_image"><?php _e('Upload Before Image', 'clarkes-terraclean'); ?></button>
                <button type="button" class="button" id="remove_before_image" style="<?php echo $before_image ? '' : 'display:none;'; ?>"><?php _e('Remove', 'clarkes-terraclean'); ?></button>
            </td>
        </tr>
        <tr>
            <th><label for="after_image"><?php _e('After Image', 'clarkes-terraclean'); ?></label></th>
            <td>
                <input type="hidden" id="after_image" name="after_image" value="<?php echo esc_attr($after_image); ?>" />
                <div id="after_image_preview" style="margin-bottom: 10px;">
                    <?php if ($after_image) : ?>
                        <?php echo wp_get_attachment_image($after_image, 'medium'); ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="button" id="upload_after_image"><?php _e('Upload After Image', 'clarkes-terraclean'); ?></button>
                <button type="button" class="button" id="remove_after_image" style="<?php echo $after_image ? '' : 'display:none;'; ?>"><?php _e('Remove', 'clarkes-terraclean'); ?></button>
            </td>
        </tr>
    </table>
    
    <script>
    jQuery(document).ready(function($) {
        var mediaUploader;
        
        $('#upload_before_image, #upload_after_image').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var isBefore = button.attr('id') === 'upload_before_image';
            
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            mediaUploader = wp.media({
                title: isBefore ? 'Choose Before Image' : 'Choose After Image',
                button: { text: 'Use this image' },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                var inputId = isBefore ? '#before_image' : '#after_image';
                var previewId = isBefore ? '#before_image_preview' : '#after_image_preview';
                var removeId = isBefore ? '#remove_before_image' : '#remove_after_image';
                
                $(inputId).val(attachment.id);
                $(previewId).html('<img src="' + attachment.url + '" style="max-width:300px;" />');
                $(removeId).show();
            });
            
            mediaUploader.open();
        });
        
        $('#remove_before_image, #remove_after_image').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var isBefore = button.attr('id') === 'remove_before_image';
            
            var inputId = isBefore ? '#before_image' : '#after_image';
            var previewId = isBefore ? '#before_image_preview' : '#after_image_preview';
            
            $(inputId).val('');
            $(previewId).html('');
            button.hide();
        });
    });
    </script>
    <?php
}
}

/**
 * Work Details Meta Box
 */
if (!function_exists('clarkes_recent_work_details_meta_box')) {
function clarkes_recent_work_details_meta_box($post) {
    $vehicle = get_post_meta($post->ID, '_vehicle_make_model', true);
    $service_type = get_post_meta($post->ID, '_service_type', true);
    $work_date = get_post_meta($post->ID, '_work_date', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="vehicle_make_model"><?php _e('Vehicle', 'clarkes-terraclean'); ?></label></th>
            <td><input type="text" id="vehicle_make_model" name="vehicle_make_model" value="<?php echo esc_attr($vehicle); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="service_type"><?php _e('Service Type', 'clarkes-terraclean'); ?></label></th>
            <td>
                <select id="service_type" name="service_type" class="regular-text">
                    <option value=""><?php _e('Select Service', 'clarkes-terraclean'); ?></option>
                    <option value="DPF Cleaning" <?php selected($service_type, 'DPF Cleaning'); ?>><?php _e('DPF Cleaning', 'clarkes-terraclean'); ?></option>
                    <option value="Engine Carbon Cleaning" <?php selected($service_type, 'Engine Carbon Cleaning'); ?>><?php _e('Engine Carbon Cleaning', 'clarkes-terraclean'); ?></option>
                    <option value="EGR Valve Cleaning" <?php selected($service_type, 'EGR Valve Cleaning'); ?>><?php _e('EGR Valve Cleaning', 'clarkes-terraclean'); ?></option>
                    <option value="Injector Cleaning" <?php selected($service_type, 'Injector Cleaning'); ?>><?php _e('Injector Cleaning', 'clarkes-terraclean'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="work_date"><?php _e('Work Date', 'clarkes-terraclean'); ?></label></th>
            <td><input type="date" id="work_date" name="work_date" value="<?php echo esc_attr($work_date); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}
}

/**
 * Save Recent Work Meta
 */
if (!function_exists('clarkes_save_recent_work_meta')) {
function clarkes_save_recent_work_meta($post_id) {
    if (!isset($_POST['clarkes_recent_work_nonce']) || !wp_verify_nonce($_POST['clarkes_recent_work_nonce'], 'clarkes_save_recent_work')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['before_image'])) {
        update_post_meta($post_id, '_before_image', absint($_POST['before_image']));
    }
    
    if (isset($_POST['after_image'])) {
        update_post_meta($post_id, '_after_image', absint($_POST['after_image']));
    }
    
    if (isset($_POST['vehicle_make_model'])) {
        update_post_meta($post_id, '_vehicle_make_model', sanitize_text_field($_POST['vehicle_make_model']));
    }
    
    if (isset($_POST['service_type'])) {
        update_post_meta($post_id, '_service_type', sanitize_text_field($_POST['service_type']));
    }
    
    if (isset($_POST['work_date'])) {
        update_post_meta($post_id, '_work_date', sanitize_text_field($_POST['work_date']));
    }
}
}
add_action('save_post_recent_work', 'clarkes_save_recent_work_meta');

/**
 * Get Recent Work Items
 */
if (!function_exists('clarkes_get_recent_work')) {
function clarkes_get_recent_work($limit = -1) {
    $args = array(
        'post_type'      => 'recent_work',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    return new WP_Query($args);
}
}

/**
 * Recent Work Gallery Shortcode
 */
if (!function_exists('clarkes_recent_work_gallery_shortcode')) {
function clarkes_recent_work_gallery_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => -1,
        'columns' => 3,
    ), $atts);
    
    $query = clarkes_get_recent_work($atts['limit']);
    
    if (!$query->have_posts()) {
        return '<p>' . __('No recent work items found.', 'clarkes-terraclean') . '</p>';
    }
    
    ob_start();
    ?>
    <div class="recent-work-gallery" style="display: grid; grid-template-columns: repeat(<?php echo esc_attr($atts['columns']); ?>, 1fr); gap: 30px; margin: 40px 0;">
        <?php while ($query->have_posts()) : $query->the_post();
            $before_image = get_post_meta(get_the_ID(), '_before_image', true);
            $after_image = get_post_meta(get_the_ID(), '_after_image', true);
            $vehicle = get_post_meta(get_the_ID(), '_vehicle_make_model', true);
            $service_type = get_post_meta(get_the_ID(), '_service_type', true);
        ?>
        <div class="recent-work-item" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: transform 0.3s, box-shadow 0.3s;">
            <?php if ($before_image && $after_image) : ?>
                <div class="before-after-container" style="position: relative; height: 300px; overflow: hidden;">
                    <div class="before-after-slider" style="position: relative; width: 100%; height: 100%;">
                        <div class="before-image" style="position: absolute; width: 100%; height: 100%;">
                            <?php echo wp_get_attachment_image($before_image, 'large', false, array('style' => 'width:100%;height:100%;object-fit:cover;')); ?>
                            <div style="position: absolute; bottom: 10px; left: 10px; background: rgba(0,0,0,0.7); color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold;">BEFORE</div>
                        </div>
                        <div class="after-image" style="position: absolute; width: 50%; height: 100%; right: 0; overflow: hidden; border-left: 3px solid #10b981;">
                            <?php echo wp_get_attachment_image($after_image, 'large', false, array('style' => 'width:200%;height:100%;object-fit:cover;margin-left:-100%;')); ?>
                            <div style="position: absolute; bottom: 10px; right: 10px; background: rgba(16,185,129,0.9); color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold;">AFTER</div>
                        </div>
                        <div class="slider-handle" style="position: absolute; top: 0; left: 50%; width: 4px; height: 100%; background: #10b981; cursor: ew-resize; z-index: 10;">
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 40px; height: 40px; background: #10b981; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"></div>
                        </div>
                    </div>
                </div>
            <?php elseif (has_post_thumbnail()) : ?>
                <div style="height: 300px; overflow: hidden;">
                    <?php the_post_thumbnail('large', array('style' => 'width:100%;height:100%;object-fit:cover;')); ?>
                </div>
            <?php endif; ?>
            
            <div style="padding: 20px;">
                <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 600; color: #1f2937;">
                    <?php echo esc_html($vehicle ?: get_the_title()); ?>
                </h3>
                <?php if ($service_type) : ?>
                    <span style="display: inline-block; background: #10b981; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 500; margin-bottom: 10px;">
                        <?php echo esc_html($service_type); ?>
                    </span>
                <?php endif; ?>
                <div style="color: #6b7280; font-size: 14px; line-height: 1.6; margin-top: 10px;">
                    <?php the_excerpt(); ?>
                </div>
            </div>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
    
    <script>
    (function() {
        document.querySelectorAll('.before-after-slider').forEach(function(slider) {
            var handle = slider.querySelector('.slider-handle');
            var afterImage = slider.querySelector('.after-image');
            var isDragging = false;
            
            function updateSlider(e) {
                var rect = slider.getBoundingClientRect();
                var x = (e.clientX || e.touches[0].clientX) - rect.left;
                var percent = Math.max(0, Math.min(100, (x / rect.width) * 100));
                
                handle.style.left = percent + '%';
                afterImage.style.width = percent + '%';
            }
            
            handle.addEventListener('mousedown', function() { isDragging = true; });
            slider.addEventListener('mousemove', function(e) { if (isDragging) updateSlider(e); });
            document.addEventListener('mouseup', function() { isDragging = false; });
            handle.addEventListener('touchstart', function() { isDragging = true; });
            slider.addEventListener('touchmove', function(e) { if (isDragging) updateSlider(e); });
            document.addEventListener('touchend', function() { isDragging = false; });
        });
    })();
    </script>
    <?php
    return ob_get_clean();
}
}
add_shortcode('recent_work_gallery', 'clarkes_recent_work_gallery_shortcode');

