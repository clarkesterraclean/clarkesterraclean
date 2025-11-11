<?php
/**
 * Before/After Image Slider
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Before/After Slider Shortcode
 */
if (!function_exists('clarkes_before_after_slider_shortcode')) {
function clarkes_before_after_slider_shortcode($atts) {
    $atts = shortcode_atts(array(
        'before_image' => '',
        'after_image' => '',
        'before_label' => 'Before',
        'after_label' => 'After',
        'width' => '100%',
        'height' => '500px',
    ), $atts, 'clarkes_before_after');
    
    // Get image IDs
    $before_id = !empty($atts['before_image']) ? absint($atts['before_image']) : 0;
    $after_id = !empty($atts['after_image']) ? absint($atts['after_image']) : 0;
    
    if (!$before_id || !$after_id) {
        return '<p class="text-red-600">Error: Both before and after images are required.</p>';
    }
    
    $before_url = wp_get_attachment_image_url($before_id, 'large');
    $after_url = wp_get_attachment_image_url($after_id, 'large');
    
    if (!$before_url || !$after_url) {
        return '<p class="text-red-600">Error: Images not found.</p>';
    }
    
    $unique_id = 'ba-slider-' . uniqid();
    
    ob_start();
    ?>
    <div class="clarkes-before-after-slider" id="<?php echo esc_attr($unique_id); ?>" style="width: <?php echo esc_attr($atts['width']); ?>; height: <?php echo esc_attr($atts['height']); ?>; max-width: 100%; margin: 20px auto;">
        <div class="ba-container" style="position: relative; width: 100%; height: 100%; overflow: hidden; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);">
            <!-- After Image (Background) -->
            <div class="ba-image ba-after" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url(<?php echo esc_url($after_url); ?>); background-size: cover; background-position: center;"></div>
            
            <!-- Before Image (Overlay) -->
            <div class="ba-image ba-before" style="position: absolute; top: 0; left: 0; width: 50%; height: 100%; background-image: url(<?php echo esc_url($before_url); ?>); background-size: cover; background-position: center; clip-path: inset(0 0 0 0);"></div>
            
            <!-- Slider Control -->
            <div class="ba-slider-handle" style="position: absolute; top: 0; left: 50%; width: 4px; height: 100%; background-color: #4ade80; cursor: ew-resize; z-index: 10; transform: translateX(-50%);">
                <div class="ba-slider-button" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 48px; height: 48px; background-color: #4ade80; border: 3px solid white; border-radius: 50%; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3); display: flex; align-items: center; justify-content: center; transition: transform 0.1s ease;">
                    <svg width="24" height="24" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24" style="pointer-events: none;">
                        <path d="M8 12h8M12 8l4 4-4 4"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Labels -->
            <div class="ba-label ba-label-before" style="position: absolute; top: 20px; left: 20px; background-color: rgba(15, 15, 15, 0.85); color: white; padding: 10px 18px; border-radius: 6px; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; backdrop-filter: blur(4px);">
                <?php echo esc_html($atts['before_label']); ?>
            </div>
            <div class="ba-label ba-label-after" style="position: absolute; top: 20px; right: 20px; background-color: rgba(15, 15, 15, 0.85); color: white; padding: 10px 18px; border-radius: 6px; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; backdrop-filter: blur(4px);">
                <?php echo esc_html($atts['after_label']); ?>
            </div>
        </div>
    </div>
    <script>
    (function() {
        var slider = document.getElementById('<?php echo esc_js($unique_id); ?>');
        if (!slider) return;
        
        var container = slider.querySelector('.ba-container');
        var beforeImage = slider.querySelector('.ba-before');
        var handle = slider.querySelector('.ba-slider-handle');
        var isDragging = false;
        var currentPosition = 50; // Start at 50%
        
        function updateSlider(position) {
            position = Math.max(0, Math.min(100, position));
            currentPosition = position;
            beforeImage.style.width = position + '%';
            handle.style.left = position + '%';
        }
        
        function handleMove(e) {
            if (!isDragging) return;
            
            var rect = container.getBoundingClientRect();
            var x = e.clientX || (e.touches && e.touches[0].clientX);
            var position = ((x - rect.left) / rect.width) * 100;
            updateSlider(position);
        }
        
        function handleEnd() {
            isDragging = false;
            document.removeEventListener('mousemove', handleMove);
            document.removeEventListener('touchmove', handleMove);
            document.removeEventListener('mouseup', handleEnd);
            document.removeEventListener('touchend', handleEnd);
        }
        
        function handleStart(e) {
            e.preventDefault();
            isDragging = true;
            handle.querySelector('.ba-slider-button').style.transform = 'translate(-50%, -50%) scale(1.1)';
            document.addEventListener('mousemove', handleMove);
            document.addEventListener('touchmove', handleMove);
            document.addEventListener('mouseup', handleEnd);
            document.addEventListener('touchend', handleEnd);
            handleMove(e);
        }
        
        function handleEnd() {
            isDragging = false;
            handle.querySelector('.ba-slider-button').style.transform = 'translate(-50%, -50%) scale(1)';
            document.removeEventListener('mousemove', handleMove);
            document.removeEventListener('touchmove', handleMove);
            document.removeEventListener('mouseup', handleEnd);
            document.removeEventListener('touchend', handleEnd);
        }
        
        handle.addEventListener('mousedown', handleStart);
        handle.addEventListener('touchstart', handleStart);
        
        // Click on container to move slider
        container.addEventListener('click', function(e) {
            if (e.target === handle || e.target.closest('.ba-slider-handle')) return;
            var rect = container.getBoundingClientRect();
            var x = e.clientX || (e.touches && e.touches[0].clientX);
            var position = ((x - rect.left) / rect.width) * 100;
            updateSlider(position);
        });
        
        // Prevent image drag
        beforeImage.style.userSelect = 'none';
        beforeImage.style.pointerEvents = 'none';
    })();
    </script>
    <?php
    return ob_get_clean();
}
}
add_shortcode('clarkes_before_after', 'clarkes_before_after_slider_shortcode');

/**
 * Add Before/After Slider Meta Box to Case Studies
 */
if (!function_exists('clarkes_add_before_after_meta_box')) {
function clarkes_add_before_after_meta_box() {
    add_meta_box(
        'before_after_slider',
        __('Before/After Images', 'clarkes-terraclean'),
        'clarkes_before_after_meta_box_callback',
        array('case_study', 'post', 'page'),
        'normal',
        'high'
    );
}
}
add_action('add_meta_boxes', 'clarkes_add_before_after_meta_box');

/**
 * Before/After Meta Box Callback
 */
if (!function_exists('clarkes_before_after_meta_box_callback')) {
function clarkes_before_after_meta_box_callback($post) {
    wp_nonce_field('clarkes_before_after_meta', 'clarkes_before_after_meta_nonce');
    
    $before_image = get_post_meta($post->ID, 'before_after_before', true);
    $after_image = get_post_meta($post->ID, 'before_after_after', true);
    $before_label = get_post_meta($post->ID, 'before_after_before_label', true);
    $after_label = get_post_meta($post->ID, 'before_after_after_label', true);
    if (empty($before_label)) $before_label = 'Before';
    if (empty($after_label)) $after_label = 'After';
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="before_after_before"><?php _e('Before Image', 'clarkes-terraclean'); ?></label></th>
            <td>
                <input type="hidden" id="before_after_before" name="before_after_before" value="<?php echo esc_attr($before_image); ?>" />
                <button type="button" class="button" id="upload_before_btn"><?php _e('Upload Before Image', 'clarkes-terraclean'); ?></button>
                <button type="button" class="button" id="remove_before_btn" style="<?php echo empty($before_image) ? 'display:none;' : ''; ?>"><?php _e('Remove', 'clarkes-terraclean'); ?></button>
                <div id="before_image_preview" style="margin-top: 10px;">
                    <?php if ($before_image) : ?>
                        <?php echo wp_get_attachment_image($before_image, 'medium'); ?>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <th><label for="before_after_after"><?php _e('After Image', 'clarkes-terraclean'); ?></label></th>
            <td>
                <input type="hidden" id="before_after_after" name="before_after_after" value="<?php echo esc_attr($after_image); ?>" />
                <button type="button" class="button" id="upload_after_btn"><?php _e('Upload After Image', 'clarkes-terraclean'); ?></button>
                <button type="button" class="button" id="remove_after_btn" style="<?php echo empty($after_image) ? 'display:none;' : ''; ?>"><?php _e('Remove', 'clarkes-terraclean'); ?></button>
                <div id="after_image_preview" style="margin-top: 10px;">
                    <?php if ($after_image) : ?>
                        <?php echo wp_get_attachment_image($after_image, 'medium'); ?>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <th><label for="before_after_before_label"><?php _e('Before Label', 'clarkes-terraclean'); ?></label></th>
            <td><input type="text" id="before_after_before_label" name="before_after_before_label" value="<?php echo esc_attr($before_label); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="before_after_after_label"><?php _e('After Label', 'clarkes-terraclean'); ?></label></th>
            <td><input type="text" id="before_after_after_label" name="before_after_after_label" value="<?php echo esc_attr($after_label); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <p class="description">
        <?php _e('Upload before and after images to create an interactive slider. Use the shortcode <code>[clarkes_before_after before_image="ID" after_image="ID"]</code> in your content, or the slider will automatically appear if both images are set.', 'clarkes-terraclean'); ?>
    </p>
    <script>
    jQuery(document).ready(function($) {
        var mediaUploader;
        
        // Before Image Upload
        $('#upload_before_btn').on('click', function(e) {
            e.preventDefault();
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media({
                title: 'Choose Before Image',
                button: { text: 'Use this image' },
                multiple: false
            });
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#before_after_before').val(attachment.id);
                $('#before_image_preview').html('<img src="' + attachment.url + '" style="max-width: 300px;" />');
                $('#remove_before_btn').show();
            });
            mediaUploader.open();
        });
        
        // After Image Upload
        $('#upload_after_btn').on('click', function(e) {
            e.preventDefault();
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media({
                title: 'Choose After Image',
                button: { text: 'Use this image' },
                multiple: false
            });
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#before_after_after').val(attachment.id);
                $('#after_image_preview').html('<img src="' + attachment.url + '" style="max-width: 300px;" />');
                $('#remove_after_btn').show();
            });
            mediaUploader.open();
        });
        
        // Remove buttons
        $('#remove_before_btn').on('click', function() {
            $('#before_after_before').val('');
            $('#before_image_preview').html('');
            $(this).hide();
        });
        
        $('#remove_after_btn').on('click', function() {
            $('#before_after_after').val('');
            $('#after_image_preview').html('');
            $(this).hide();
        });
    });
    </script>
    <?php
}
}

/**
 * Save Before/After Meta
 */
if (!function_exists('clarkes_save_before_after_meta')) {
function clarkes_save_before_after_meta($post_id) {
    if (!isset($_POST['clarkes_before_after_meta_nonce']) || !wp_verify_nonce($_POST['clarkes_before_after_meta_nonce'], 'clarkes_before_after_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array(
        'before_after_before' => 'absint',
        'before_after_after' => 'absint',
        'before_after_before_label' => 'sanitize_text_field',
        'before_after_after_label' => 'sanitize_text_field',
    );
    
    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            $value = $sanitize === 'absint' ? absint($_POST[$field]) : sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $field, $value);
        }
    }
}
}
add_action('save_post', 'clarkes_save_before_after_meta');

/**
 * Automatically display before/after slider in case studies if images are set
 */
if (!function_exists('clarkes_display_before_after_in_content')) {
function clarkes_display_before_after_in_content($content) {
    global $post;
    
    if (!$post || !in_array($post->post_type, array('case_study', 'post', 'page'))) {
        return $content;
    }
    
    $before_image = get_post_meta($post->ID, 'before_after_before', true);
    $after_image = get_post_meta($post->ID, 'before_after_after', true);
    
    if ($before_image && $after_image) {
        $before_label = get_post_meta($post->ID, 'before_after_before_label', true) ?: 'Before';
        $after_label = get_post_meta($post->ID, 'before_after_after_label', true) ?: 'After';
        
        $slider = do_shortcode('[clarkes_before_after before_image="' . $before_image . '" after_image="' . $after_image . '" before_label="' . esc_attr($before_label) . '" after_label="' . esc_attr($after_label) . '"]');
        
        // Insert slider at the beginning of content
        $content = $slider . $content;
    }
    
    return $content;
}
}
add_filter('the_content', 'clarkes_display_before_after_in_content', 5);

