<?php
/**
 * Quick Add Workflow - Mobile-Friendly Content Creation
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Quick Add Menu Page
 */
if (!function_exists('clarkes_add_quick_add_menu')) {
function clarkes_add_quick_add_menu() {
    add_menu_page(
        __('Quick Add', 'clarkes-terraclean'),
        __('Quick Add', 'clarkes-terraclean'),
        'edit_posts',
        'clarkes-quick-add',
        'clarkes_quick_add_page',
        'dashicons-plus-alt',
        3
    );
}
}
add_action('admin_menu', 'clarkes_add_quick_add_menu');

/**
 * Enqueue scripts for Quick Add page
 */
if (!function_exists('clarkes_quick_add_scripts')) {
function clarkes_quick_add_scripts($hook) {
    if ($hook !== 'toplevel_page_clarkes-quick-add') {
        return;
    }
    
    wp_enqueue_media();
    wp_enqueue_script('jquery');
}
}
add_action('admin_enqueue_scripts', 'clarkes_quick_add_scripts');

/**
 * Quick Add Page
 */
if (!function_exists('clarkes_quick_add_page')) {
function clarkes_quick_add_page() {
    // Handle form submission
    if (isset($_POST['clarkes_quick_add_submit']) && wp_verify_nonce($_POST['clarkes_quick_add_nonce'], 'clarkes_quick_add')) {
        $content_type = sanitize_text_field($_POST['content_type']);
        $title = sanitize_text_field($_POST['title']);
        $description = sanitize_textarea_field($_POST['description']);
        $status = sanitize_text_field($_POST['status']);
        $images = isset($_POST['images']) ? array_map('absint', $_POST['images']) : array();
        
        $result = clarkes_create_quick_content($content_type, $title, $description, $images, $status);
        
        if ($result && !is_wp_error($result)) {
            echo '<div class="notice notice-success is-dismissible"><p>Content created successfully! <a href="' . get_edit_post_link($result) . '">Edit it here</a> or <a href="' . get_permalink($result) . '" target="_blank">View it</a>.</p></div>';
        } else {
            $error_msg = is_wp_error($result) ? $result->get_error_message() : 'Unknown error occurred.';
            echo '<div class="notice notice-error is-dismissible"><p>Error: ' . esc_html($error_msg) . '</p></div>';
        }
    }
    
    ?>
    <div class="wrap clarkes-quick-add" style="max-width: 800px; margin: 20px auto;">
        <h1><?php _e('Quick Add Content', 'clarkes-terraclean'); ?></h1>
        <p class="description"><?php _e('Quickly add new content on the go. Perfect for mobile use.', 'clarkes-terraclean'); ?></p>
        
        <form method="post" id="quick-add-form" enctype="multipart/form-data" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px; margin-top: 20px;">
            <?php wp_nonce_field('clarkes_quick_add', 'clarkes_quick_add_nonce'); ?>
            
            <!-- Content Type -->
            <div style="margin-bottom: 20px;">
                <label for="content_type" style="display: block; margin-bottom: 8px; font-weight: 600;">
                    <?php _e('What are you adding?', 'clarkes-terraclean'); ?>
                </label>
                <select id="content_type" name="content_type" required style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value=""><?php _e('Select content type...', 'clarkes-terraclean'); ?></option>
                    <option value="recent_work"><?php _e('Recent Work Gallery Item', 'clarkes-terraclean'); ?></option>
                    <option value="case_study"><?php _e('Case Study', 'clarkes-terraclean'); ?></option>
                    <option value="post"><?php _e('Blog Post', 'clarkes-terraclean'); ?></option>
                    <option value="review"><?php _e('Review (Manual Entry)', 'clarkes-terraclean'); ?></option>
                </select>
            </div>
            
            <!-- Title -->
            <div style="margin-bottom: 20px;">
                <label for="title" style="display: block; margin-bottom: 8px; font-weight: 600;">
                    <?php _e('Title', 'clarkes-terraclean'); ?> <span style="color: red;">*</span>
                </label>
                <input type="text" id="title" name="title" required style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ddd; border-radius: 4px;" placeholder="<?php esc_attr_e('Enter a title...', 'clarkes-terraclean'); ?>" />
            </div>
            
            <!-- Description -->
            <div style="margin-bottom: 20px;">
                <label for="description" style="display: block; margin-bottom: 8px; font-weight: 600;">
                    <?php _e('Description', 'clarkes-terraclean'); ?>
                </label>
                <textarea id="description" name="description" rows="4" style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ddd; border-radius: 4px;" placeholder="<?php esc_attr_e('Enter a short description...', 'clarkes-terraclean'); ?>"></textarea>
            </div>
            
            <!-- Image Upload -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                    <?php _e('Images', 'clarkes-terraclean'); ?>
                </label>
                <input type="file" id="image_upload" name="images[]" multiple accept="image/*" capture="environment" style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ddd; border-radius: 4px;" />
                <p class="description" style="margin-top: 5px; font-size: 14px; color: #666;">
                    <?php _e('You can select multiple images. On mobile, this will open your camera.', 'clarkes-terraclean'); ?>
                </p>
                <div id="image_preview" style="margin-top: 15px; display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px;"></div>
                <input type="hidden" id="image_ids" name="images" value="" />
            </div>
            
            <!-- Status -->
            <div style="margin-bottom: 20px;">
                <label for="status" style="display: block; margin-bottom: 8px; font-weight: 600;">
                    <?php _e('Status', 'clarkes-terraclean'); ?>
                </label>
                <select id="status" name="status" style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="draft"><?php _e('Save as Draft', 'clarkes-terraclean'); ?></option>
                    <option value="publish"><?php _e('Publish Immediately', 'clarkes-terraclean'); ?></option>
                </select>
            </div>
            
            <!-- Submit Button -->
            <div style="margin-top: 30px;">
                <button type="submit" name="clarkes_quick_add_submit" class="button button-primary button-large" style="width: 100%; padding: 15px; font-size: 18px; font-weight: 600;">
                    <?php _e('Save Content', 'clarkes-terraclean'); ?>
                </button>
            </div>
        </form>
    </div>
    
    <style>
    @media (max-width: 782px) {
        .clarkes-quick-add {
            padding: 10px;
        }
        .clarkes-quick-add form {
            padding: 15px;
        }
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        var uploadedImages = [];
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        
        // Handle image upload
        $('#image_upload').on('change', function(e) {
            var files = e.target.files;
            if (!files.length) return;
            
            var formData = new FormData();
            formData.append('action', 'clarkes_upload_quick_images');
            formData.append('nonce', '<?php echo wp_create_nonce('clarkes_upload_images'); ?>');
            
            for (var i = 0; i < files.length; i++) {
                formData.append('images[]', files[i]);
            }
            
            // Show loading
            $('#image_preview').html('<p style="padding: 20px; text-align: center;">Uploading images...</p>');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success && response.data) {
                        uploadedImages = uploadedImages.concat(response.data.ids);
                        $('#image_ids').val(uploadedImages.join(','));
                        
                        // Display previews
                        var previewHtml = '';
                        response.data.ids.forEach(function(id) {
                            var url = response.data.urls[id] || '';
                            previewHtml += '<div style="position: relative;"><img src="' + url + '" style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px; border: 2px solid #ddd;" /><button type="button" class="remove-image" data-id="' + id + '" style="position: absolute; top: 5px; right: 5px; background: #dc3232; color: white; border: none; border-radius: 50%; width: 28px; height: 28px; cursor: pointer; font-size: 18px; line-height: 1;">×</button></div>';
                        });
                        $('#image_preview').html(previewHtml);
                    } else {
                        alert('Error: ' + (response.data && response.data.message ? response.data.message : 'Failed to upload images'));
                        $('#image_preview').html('');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error uploading images: ' + error + '. Please try again.');
                    $('#image_preview').html('');
                }
            });
        });
        
        // Remove image
        $(document).on('click', '.remove-image', function() {
            var id = $(this).data('id');
            uploadedImages = uploadedImages.filter(function(imgId) { return imgId != id; });
            $('#image_ids').val(uploadedImages.join(','));
            $(this).closest('div').remove();
            
            if (uploadedImages.length === 0) {
                $('#image_preview').html('');
            }
        });
    });
    </script>
    <?php
}
}

/**
 * Handle Image Upload via AJAX
 */
if (!function_exists('clarkes_handle_quick_image_upload')) {
function clarkes_handle_quick_image_upload() {
    check_ajax_referer('clarkes_upload_images', 'nonce');
    
    if (!current_user_can('upload_files')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    if (empty($_FILES['images'])) {
        wp_send_json_error(array('message' => 'No files uploaded'));
        return;
    }
    
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    
    $uploaded_ids = array();
    $uploaded_urls = array();
    
    foreach ($_FILES['images']['name'] as $key => $value) {
        if ($_FILES['images']['name'][$key]) {
            $file = array(
                'name'     => $_FILES['images']['name'][$key],
                'type'     => $_FILES['images']['type'][$key],
                'tmp_name' => $_FILES['images']['tmp_name'][$key],
                'error'    => $_FILES['images']['error'][$key],
                'size'     => $_FILES['images']['size'][$key]
            );
            
            $upload = wp_handle_upload($file, array('test_form' => false));
            
            if (isset($upload['error'])) {
                continue;
            }
            
            $attachment = array(
                'post_mime_type' => $upload['type'],
                'post_title'     => sanitize_file_name(pathinfo($upload['file'], PATHINFO_FILENAME)),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );
            
            $attach_id = wp_insert_attachment($attachment, $upload['file']);
            $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
            wp_update_attachment_metadata($attach_id, $attach_data);
            
            $uploaded_ids[] = $attach_id;
            $uploaded_urls[$attach_id] = wp_get_attachment_image_url($attach_id, 'medium');
        }
    }
    
    wp_send_json_success(array(
        'ids' => $uploaded_ids,
        'urls' => $uploaded_urls
    ));
}
}
add_action('wp_ajax_clarkes_upload_quick_images', 'clarkes_handle_quick_image_upload');

/**
 * Create Quick Content
 */
if (!function_exists('clarkes_create_quick_content')) {
function clarkes_create_quick_content($content_type, $title, $description, $images, $status = 'draft') {
    if (empty($title) || empty($content_type)) {
        return new WP_Error('missing_fields', 'Title and content type are required.');
    }
    
    $post_data = array(
        'post_title'   => $title,
        'post_content' => $description,
        'post_status'  => $status,
        'post_type'    => $content_type,
        'post_author'  => get_current_user_id(),
    );
    
    $post_id = wp_insert_post($post_data);
    
    if (is_wp_error($post_id)) {
        return $post_id;
    }
    
    // Set featured image (first image)
    if (!empty($images) && is_array($images)) {
        set_post_thumbnail($post_id, $images[0]);
        
        // For recent_work, store all images in a gallery
        if ($content_type === 'recent_work' && count($images) > 1) {
            update_post_meta($post_id, 'work_gallery_images', $images);
        }
    }
    
    // For case studies, set vehicle if title looks like a vehicle
    if ($content_type === 'case_study') {
        update_post_meta($post_id, 'vehicle_make_model', $title);
        if (!empty($description)) {
            update_post_meta($post_id, 'result', $description);
        }
    }
    
    return $post_id;
}
}

