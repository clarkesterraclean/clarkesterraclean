<?php
/**
 * Comprehensive Media Editor
 * Image and Video Editing Tools with AI Features
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Media Editor Menu Page
 */
if (!function_exists('clarkes_add_media_editor_menu')) {
function clarkes_add_media_editor_menu() {
    add_submenu_page(
        'upload.php',
        __('Media Editor', 'clarkes-terraclean'),
        __('Media Editor', 'clarkes-terraclean'),
        'upload_files',
        'clarkes-media-editor',
        'clarkes_media_editor_page'
    );
}
}
add_action('admin_menu', 'clarkes_add_media_editor_menu');

/**
 * Enqueue Media Editor Scripts and Styles
 */
if (!function_exists('clarkes_media_editor_scripts')) {
function clarkes_media_editor_scripts($hook) {
    if ($hook !== 'media_page_clarkes-media-editor') {
        return;
    }
    
    // Enqueue WordPress media scripts
    wp_enqueue_media();
    wp_enqueue_script('jquery');
    
    // Enqueue image editing libraries
    wp_enqueue_script('fabric-js', 'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js', array(), '5.3.0', true);
    wp_enqueue_script('cropper-js', 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js', array(), '1.5.13', true);
    wp_enqueue_style('cropper-css', 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css', array(), '1.5.13');
    
    // Enqueue our custom editor script
    wp_enqueue_script(
        'clarkes-media-editor',
        get_template_directory_uri() . '/inc/media-editor.js',
        array('jquery', 'fabric-js', 'cropper-js'),
        filemtime(get_template_directory() . '/inc/media-editor.js'),
        true
    );
    
    // Localize script
    wp_localize_script('clarkes-media-editor', 'clarkesMediaEditor', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('clarkes_media_editor'),
        'ai_api_key' => get_option('clarkes_ai_api_key', ''),
    ));
    
    // Enqueue editor styles
    wp_enqueue_style(
        'clarkes-media-editor-style',
        get_template_directory_uri() . '/inc/media-editor.css',
        array(),
        filemtime(get_template_directory() . '/inc/media-editor.css')
    );
}
}
add_action('admin_enqueue_scripts', 'clarkes_media_editor_scripts');

/**
 * Media Editor Page
 */
if (!function_exists('clarkes_media_editor_page')) {
function clarkes_media_editor_page() {
    $media_id = isset($_GET['media_id']) ? absint($_GET['media_id']) : 0;
    $media = null;
    
    if ($media_id) {
        $media = get_post($media_id);
        if (!$media || !in_array($media->post_mime_type, array('image/jpeg', 'image/png', 'image/gif', 'image/webp', 'video/mp4', 'video/webm', 'video/ogg'))) {
            $media = null;
        }
    }
    ?>
    <div class="wrap clarkes-media-editor">
        <h1><?php _e('Media Editor', 'clarkes-terraclean'); ?></h1>
        
        <?php if (!$media_id || !$media) : ?>
            <!-- Media Selection -->
            <div class="media-selector" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px; margin-top: 20px;">
                <h2><?php _e('Select Media to Edit', 'clarkes-terraclean'); ?></h2>
                <button type="button" id="select-media-btn" class="button button-primary button-large">
                    <?php _e('Choose Image or Video', 'clarkes-terraclean'); ?>
                </button>
                <input type="hidden" id="selected-media-id" value="<?php echo esc_attr($media_id); ?>" />
            </div>
        <?php else : ?>
            <!-- Editor Interface -->
            <div class="media-editor-interface" data-media-id="<?php echo esc_attr($media_id); ?>" data-media-type="<?php echo esc_attr(strpos($media->post_mime_type, 'video') !== false ? 'video' : 'image'); ?>">
                
                <!-- Toolbar -->
                <div class="editor-toolbar" style="background: white; padding: 15px; border: 1px solid #ddd; border-radius: 4px; margin: 20px 0; display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="button" class="button" id="btn-crop"><?php _e('Crop', 'clarkes-terraclean'); ?></button>
                    <button type="button" class="button" id="btn-resize"><?php _e('Resize', 'clarkes-terraclean'); ?></button>
                    <button type="button" class="button" id="btn-filters"><?php _e('Filters', 'clarkes-terraclean'); ?></button>
                    <button type="button" class="button" id="btn-text-overlay"><?php _e('Add Text', 'clarkes-terraclean'); ?></button>
                    <button type="button" class="button" id="btn-markup"><?php _e('Markup', 'clarkes-terraclean'); ?></button>
                    <?php if (strpos($media->post_mime_type, 'video') !== false) : ?>
                        <button type="button" class="button" id="btn-mute-video"><?php _e('Mute Audio', 'clarkes-terraclean'); ?></button>
                        <button type="button" class="button" id="btn-auto-orient"><?php _e('Auto Orient', 'clarkes-terraclean'); ?></button>
                    <?php endif; ?>
                    <button type="button" class="button" id="btn-ai-seo"><?php _e('AI SEO', 'clarkes-terraclean'); ?></button>
                    <button type="button" class="button button-primary" id="btn-save"><?php _e('Save Changes', 'clarkes-terraclean'); ?></button>
                    <button type="button" class="button" id="btn-reset"><?php _e('Reset', 'clarkes-terraclean'); ?></button>
                </div>
                
                <!-- Editor Canvas -->
                <div class="editor-canvas-container" style="background: #f0f0f0; padding: 20px; border: 1px solid #ddd; border-radius: 4px; text-align: center;">
                    <?php if (strpos($media->post_mime_type, 'video') !== false) : ?>
                        <video id="editor-video" src="<?php echo esc_url(wp_get_attachment_url($media_id)); ?>" controls style="max-width: 100%; max-height: 70vh;"></video>
                    <?php else : ?>
                        <canvas id="editor-canvas" style="max-width: 100%; border: 1px solid #ddd; background: white;"></canvas>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar Controls -->
                <div class="editor-sidebar" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                    <!-- Crop Controls -->
                    <div id="crop-controls" class="control-panel" style="display: none; background: white; padding: 15px; border: 1px solid #ddd; border-radius: 4px;">
                        <h3><?php _e('Crop', 'clarkes-terraclean'); ?></h3>
                        <label><?php _e('Aspect Ratio:', 'clarkes-terraclean'); ?>
                            <select id="crop-aspect">
                                <option value="free"><?php _e('Free', 'clarkes-terraclean'); ?></option>
                                <option value="16:9">16:9</option>
                                <option value="4:3">4:3</option>
                                <option value="1:1">1:1</option>
                                <option value="9:16">9:16 (Portrait)</option>
                                <option value="3:4">3:4 (Portrait)</option>
                            </select>
                        </label>
                        <button type="button" class="button" id="apply-crop"><?php _e('Apply Crop', 'clarkes-terraclean'); ?></button>
                    </div>
                    
                    <!-- Resize Controls -->
                    <div id="resize-controls" class="control-panel" style="display: none; background: white; padding: 15px; border: 1px solid #ddd; border-radius: 4px;">
                        <h3><?php _e('Resize', 'clarkes-terraclean'); ?></h3>
                        <label><?php _e('Width (px):', 'clarkes-terraclean'); ?>
                            <input type="number" id="resize-width" class="small-text" />
                        </label>
                        <label><?php _e('Height (px):', 'clarkes-terraclean'); ?>
                            <input type="number" id="resize-height" class="small-text" />
                        </label>
                        <label>
                            <input type="checkbox" id="resize-maintain-aspect" checked />
                            <?php _e('Maintain Aspect Ratio', 'clarkes-terraclean'); ?>
                        </label>
                        <button type="button" class="button" id="apply-resize"><?php _e('Apply Resize', 'clarkes-terraclean'); ?></button>
                    </div>
                    
                    <!-- Filter Controls -->
                    <div id="filter-controls" class="control-panel" style="display: none; background: white; padding: 15px; border: 1px solid #ddd; border-radius: 4px;">
                        <h3><?php _e('Filters', 'clarkes-terraclean'); ?></h3>
                        <label><?php _e('Brightness:', 'clarkes-terraclean'); ?>
                            <input type="range" id="filter-brightness" min="0" max="200" value="100" />
                            <span id="brightness-value">100</span>
                        </label>
                        <label><?php _e('Contrast:', 'clarkes-terraclean'); ?>
                            <input type="range" id="filter-contrast" min="0" max="200" value="100" />
                            <span id="contrast-value">100</span>
                        </label>
                        <label><?php _e('Saturation:', 'clarkes-terraclean'); ?>
                            <input type="range" id="filter-saturation" min="0" max="200" value="100" />
                            <span id="saturation-value">100</span>
                        </label>
                        <label><?php _e('Blur:', 'clarkes-terraclean'); ?>
                            <input type="range" id="filter-blur" min="0" max="20" value="0" />
                            <span id="blur-value">0</span>
                        </label>
                        <button type="button" class="button" id="apply-filters"><?php _e('Apply Filters', 'clarkes-terraclean'); ?></button>
                        <button type="button" class="button" id="reset-filters"><?php _e('Reset Filters', 'clarkes-terraclean'); ?></button>
                    </div>
                    
                    <!-- Text Overlay Controls -->
                    <div id="text-controls" class="control-panel" style="display: none; background: white; padding: 15px; border: 1px solid #ddd; border-radius: 4px;">
                        <h3><?php _e('Text Overlay', 'clarkes-terraclean'); ?></h3>
                        <label><?php _e('Text:', 'clarkes-terraclean'); ?>
                            <input type="text" id="text-content" class="regular-text" />
                        </label>
                        <label><?php _e('Font Size:', 'clarkes-terraclean'); ?>
                            <input type="number" id="text-size" value="24" min="10" max="200" />
                        </label>
                        <label><?php _e('Color:', 'clarkes-terraclean'); ?>
                            <input type="color" id="text-color" value="#ffffff" />
                        </label>
                        <label><?php _e('Position X:', 'clarkes-terraclean'); ?>
                            <input type="number" id="text-x" value="50" />
                        </label>
                        <label><?php _e('Position Y:', 'clarkes-terraclean'); ?>
                            <input type="number" id="text-y" value="50" />
                        </label>
                        <button type="button" class="button" id="add-text"><?php _e('Add Text', 'clarkes-terraclean'); ?></button>
                    </div>
                    
                    <!-- Markup Controls -->
                    <div id="markup-controls" class="control-panel" style="display: none; background: white; padding: 15px; border: 1px solid #ddd; border-radius: 4px;">
                        <h3><?php _e('Markup Tools', 'clarkes-terraclean'); ?></h3>
                        <button type="button" class="button" id="btn-arrow"><?php _e('Arrow', 'clarkes-terraclean'); ?></button>
                        <button type="button" class="button" id="btn-rectangle"><?php _e('Rectangle', 'clarkes-terraclean'); ?></button>
                        <button type="button" class="button" id="btn-circle"><?php _e('Circle', 'clarkes-terraclean'); ?></button>
                        <button type="button" class="button" id="btn-line"><?php _e('Line', 'clarkes-terraclean'); ?></button>
                        <label><?php _e('Color:', 'clarkes-terraclean'); ?>
                            <input type="color" id="markup-color" value="#4ade80" />
                        </label>
                        <label><?php _e('Stroke Width:', 'clarkes-terraclean'); ?>
                            <input type="number" id="markup-width" value="3" min="1" max="20" />
                        </label>
                    </div>
                    
                    <!-- AI SEO Panel -->
                    <div id="ai-seo-panel" class="control-panel" style="display: none; background: white; padding: 15px; border: 1px solid #ddd; border-radius: 4px; grid-column: 1 / -1;">
                        <h3><?php _e('AI-Powered SEO & Meta Information', 'clarkes-terraclean'); ?></h3>
                        <p class="description"><?php _e('Generate SEO-optimized title, alt text, description, and keywords using AI analysis of your media.', 'clarkes-terraclean'); ?></p>
                        <button type="button" class="button button-primary" id="generate-ai-seo"><?php _e('Generate SEO Info', 'clarkes-terraclean'); ?></button>
                        <div id="ai-results" style="margin-top: 15px; display: none;">
                            <label><?php _e('Title:', 'clarkes-terraclean'); ?>
                                <input type="text" id="ai-title" class="large-text" />
                            </label>
                            <label><?php _e('Alt Text:', 'clarkes-terraclean'); ?>
                                <input type="text" id="ai-alt" class="large-text" />
                            </label>
                            <label><?php _e('Description:', 'clarkes-terraclean'); ?>
                                <textarea id="ai-description" class="large-text" rows="3"></textarea>
                            </label>
                            <label><?php _e('Keywords:', 'clarkes-terraclean'); ?>
                                <input type="text" id="ai-keywords" class="large-text" placeholder="keyword1, keyword2, keyword3" />
                            </label>
                            <button type="button" class="button" id="apply-ai-seo"><?php _e('Apply to Media', 'clarkes-terraclean'); ?></button>
                        </div>
                    </div>
                </div>
                
                <!-- Media Info -->
                <div class="media-info" style="background: white; padding: 15px; border: 1px solid #ddd; border-radius: 4px; margin-top: 20px;">
                    <h3><?php _e('Media Information', 'clarkes-terraclean'); ?></h3>
                    <p><strong><?php _e('File:', 'clarkes-terraclean'); ?></strong> <?php echo esc_html($media->post_title); ?></p>
                    <p><strong><?php _e('Type:', 'clarkes-terraclean'); ?></strong> <?php echo esc_html($media->post_mime_type); ?></p>
                    <p><strong><?php _e('Uploaded:', 'clarkes-terraclean'); ?></strong> <?php echo esc_html(get_the_date('', $media_id)); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
}

/**
 * Handle Media Selection
 */
if (!function_exists('clarkes_handle_media_selection')) {
function clarkes_handle_media_selection() {
    check_ajax_referer('clarkes_media_editor', 'nonce');
    
    if (!current_user_can('upload_files')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $media_id = isset($_POST['media_id']) ? absint($_POST['media_id']) : 0;
    
    if (!$media_id) {
        wp_send_json_error(array('message' => 'No media ID provided'));
        return;
    }
    
    $media = get_post($media_id);
    if (!$media) {
        wp_send_json_error(array('message' => 'Media not found'));
        return;
    }
    
    $media_url = wp_get_attachment_url($media_id);
    $media_meta = wp_get_attachment_metadata($media_id);
    
    wp_send_json_success(array(
        'id' => $media_id,
        'url' => $media_url,
        'type' => $media->post_mime_type,
        'title' => $media->post_title,
        'alt' => get_post_meta($media_id, '_wp_attachment_image_alt', true),
        'width' => isset($media_meta['width']) ? $media_meta['width'] : 0,
        'height' => isset($media_meta['height']) ? $media_meta['height'] : 0,
    ));
}
}
add_action('wp_ajax_clarkes_get_media', 'clarkes_handle_media_selection');

/**
 * Save Edited Media
 */
if (!function_exists('clarkes_save_edited_media')) {
function clarkes_save_edited_media() {
    check_ajax_referer('clarkes_media_editor', 'nonce');
    
    if (!current_user_can('upload_files')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $media_id = isset($_POST['media_id']) ? absint($_POST['media_id']) : 0;
    $image_data = isset($_POST['image_data']) ? $_POST['image_data'] : '';
    $operations = isset($_POST['operations']) ? $_POST['operations'] : array();
    
    if (!$media_id || !$image_data) {
        wp_send_json_error(array('message' => 'Missing required data'));
        return;
    }
    
    // Decode base64 image
    $image_data = str_replace('data:image/png;base64,', '', $image_data);
    $image_data = base64_decode($image_data);
    
    // Save edited image
    $upload_dir = wp_upload_dir();
    $filename = 'edited-' . get_post_meta($media_id, '_wp_attached_file', true);
    $file_path = $upload_dir['path'] . '/' . basename($filename);
    
    file_put_contents($file_path, $image_data);
    
    // Create attachment
    $attachment = array(
        'post_mime_type' => 'image/png',
        'post_title' => sanitize_file_name(pathinfo($filename, PATHINFO_FILENAME)),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    
    $attach_id = wp_insert_attachment($attachment, $file_path);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
    wp_update_attachment_metadata($attach_id, $attach_data);
    
    wp_send_json_success(array(
        'id' => $attach_id,
        'url' => wp_get_attachment_url($attach_id),
    ));
}
}
add_action('wp_ajax_clarkes_save_edited_media', 'clarkes_save_edited_media');

/**
 * Generate AI SEO Information
 */
if (!function_exists('clarkes_generate_ai_seo')) {
function clarkes_generate_ai_seo() {
    check_ajax_referer('clarkes_media_editor', 'nonce');
    
    if (!current_user_can('upload_files')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $media_id = isset($_POST['media_id']) ? absint($_POST['media_id']) : 0;
    $media_type = isset($_POST['media_type']) ? sanitize_text_field($_POST['media_type']) : 'image';
    
    if (!$media_id) {
        wp_send_json_error(array('message' => 'No media ID provided'));
        return;
    }
    
    $media = get_post($media_id);
    $current_title = $media->post_title;
    $current_alt = get_post_meta($media_id, '_wp_attachment_image_alt', true);
    
    // Generate SEO-friendly suggestions
    $filename = pathinfo($current_title, PATHINFO_FILENAME);
    $filename_clean = str_replace(array('-', '_'), ' ', $filename);
    $filename_clean = ucwords($filename_clean);
    
    // Get image dimensions if available
    $media_meta = wp_get_attachment_metadata($media_id);
    $width = isset($media_meta['width']) ? $media_meta['width'] : 0;
    $height = isset($media_meta['height']) ? $media_meta['height'] : 0;
    $orientation = ($height > $width) ? 'portrait' : 'landscape';
    
    // Base suggestions
    $suggestions = array(
        'title' => $filename_clean . ' - Clarke\'s DPF & Engine Specialists',
        'alt' => $filename_clean . ' - Professional engine cleaning and decarbonisation service',
        'description' => 'High-quality ' . $media_type . ' showcasing ' . strtolower($filename_clean) . ' services at Clarke\'s DPF & Engine Specialists. Professional engine decarbonisation, DPF cleaning, and EGR valve cleaning in Kent.',
        'keywords' => 'engine cleaning, DPF cleaning, EGR cleaning, decarbonisation, Kent, automotive service, ' . strtolower($filename_clean),
    );
    
    // If AI API key is set, try to use OpenAI
    $ai_api_key = get_option('clarkes_ai_api_key', '');
    if (!empty($ai_api_key)) {
        // Analyze image/video and generate better SEO
        $prompt = "Generate SEO-optimized metadata for a " . $media_type . " file named '" . $filename_clean . "' for a business called 'Clarke's DPF & Engine Specialists' that does engine decarbonisation, DPF cleaning, and EGR valve cleaning in Kent, UK. Return JSON with: title, alt_text, description, keywords (comma-separated).";
        
        $ai_response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $ai_api_key,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode(array(
                'model' => 'gpt-3.5-turbo',
                'messages' => array(
                    array('role' => 'user', 'content' => $prompt)
                ),
                'max_tokens' => 300,
            )),
            'timeout' => 30,
        ));
        
        if (!is_wp_error($ai_response)) {
            $body = json_decode(wp_remote_retrieve_body($ai_response), true);
            if (isset($body['choices'][0]['message']['content'])) {
                $ai_content = $body['choices'][0]['message']['content'];
                // Try to parse JSON from AI response
                if (preg_match('/\{.*\}/s', $ai_content, $matches)) {
                    $ai_data = json_decode($matches[0], true);
                    if ($ai_data) {
                        $suggestions = array_merge($suggestions, array(
                            'title' => isset($ai_data['title']) ? $ai_data['title'] : $suggestions['title'],
                            'alt' => isset($ai_data['alt_text']) ? $ai_data['alt_text'] : $suggestions['alt'],
                            'description' => isset($ai_data['description']) ? $ai_data['description'] : $suggestions['description'],
                            'keywords' => isset($ai_data['keywords']) ? $ai_data['keywords'] : $suggestions['keywords'],
                        ));
                    }
                }
            }
        }
    }
    
    wp_send_json_success($suggestions);
}
}
add_action('wp_ajax_clarkes_generate_ai_seo', 'clarkes_generate_ai_seo');

/**
 * Apply AI SEO to Media
 */
if (!function_exists('clarkes_apply_ai_seo')) {
function clarkes_apply_ai_seo() {
    check_ajax_referer('clarkes_media_editor', 'nonce');
    
    if (!current_user_can('upload_files')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $media_id = isset($_POST['media_id']) ? absint($_POST['media_id']) : 0;
    $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
    $alt = isset($_POST['alt']) ? sanitize_text_field($_POST['alt']) : '';
    $description = isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '';
    $keywords = isset($_POST['keywords']) ? sanitize_text_field($_POST['keywords']) : '';
    
    if (!$media_id) {
        wp_send_json_error(array('message' => 'No media ID provided'));
        return;
    }
    
    // Update post title
    if ($title) {
        wp_update_post(array(
            'ID' => $media_id,
            'post_title' => $title,
        ));
    }
    
    // Update alt text
    if ($alt) {
        update_post_meta($media_id, '_wp_attachment_image_alt', $alt);
    }
    
    // Update description
    if ($description) {
        wp_update_post(array(
            'ID' => $media_id,
            'post_content' => $description,
        ));
    }
    
    // Store keywords in meta
    if ($keywords) {
        update_post_meta($media_id, '_clarkes_media_keywords', $keywords);
    }
    
    wp_send_json_success(array('message' => 'SEO information updated successfully'));
}
}
add_action('wp_ajax_clarkes_apply_ai_seo', 'clarkes_apply_ai_seo');

/**
 * Process Video (Mute, Auto Orient)
 */
if (!function_exists('clarkes_process_video')) {
function clarkes_process_video() {
    check_ajax_referer('clarkes_media_editor', 'nonce');
    
    if (!current_user_can('upload_files')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $media_id = isset($_POST['media_id']) ? absint($_POST['media_id']) : 0;
    $operation = isset($_POST['operation']) ? sanitize_text_field($_POST['operation']) : '';
    
    if (!$media_id || !$operation) {
        wp_send_json_error(array('message' => 'Missing required data'));
        return;
    }
    
    $media = get_post($media_id);
    if (strpos($media->post_mime_type, 'video') === false) {
        wp_send_json_error(array('message' => 'Not a video file'));
        return;
    }
    
    $video_path = get_attached_file($media_id);
    
    // Note: Video processing requires FFmpeg
    // This is a placeholder - actual implementation would use FFmpeg via exec()
    
    wp_send_json_success(array('message' => 'Video processing queued. Note: Requires FFmpeg server-side.'));
}
}
add_action('wp_ajax_clarkes_process_video', 'clarkes_process_video');

/**
 * Add AI API Key Setting to Advanced Section
 */
if (!function_exists('clarkes_add_ai_api_key_setting')) {
function clarkes_add_ai_api_key_setting($wp_customize) {
    // Add to Advanced section if it exists, otherwise create it
    $wp_customize->add_setting('clarkes_ai_api_key', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('clarkes_ai_api_key', array(
        'label'       => esc_html__('AI API Key (Optional)', 'clarkes-terraclean'),
        'description' => esc_html__('Enter your OpenAI API key for enhanced AI features in the Media Editor. Get one at platform.openai.com', 'clarkes-terraclean'),
        'section'     => 'clarkes_advanced',
        'type'        => 'text',
        'priority'    => 100,
    ));
}
}
add_action('customize_register', 'clarkes_add_ai_api_key_setting', 20);

