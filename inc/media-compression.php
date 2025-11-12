<?php
/**
 * Media Compression Module
 * Batch Image and Video Compression
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Media Compression Menu
 */
if (!function_exists('clarkes_add_media_compression_menu')) {
function clarkes_add_media_compression_menu() {
    add_submenu_page(
        'clarkes-control-center',
        __('Media Compression', 'clarkes-terraclean'),
        __('Media Compression', 'clarkes-terraclean'),
        'manage_options',
        'clarkes-media-compression',
        'clarkes_media_compression_page'
    );
}
}
add_action('admin_menu', 'clarkes_add_media_compression_menu', 11);

/**
 * Enqueue Compression Scripts
 */
if (!function_exists('clarkes_compression_scripts')) {
function clarkes_compression_scripts($hook) {
    if (strpos($hook, 'clarkes-media-compression') === false) {
        return;
    }
    
    wp_enqueue_script('jquery');
    wp_enqueue_media();
    
    wp_enqueue_script(
        'clarkes-compression',
        get_template_directory_uri() . '/inc/compression.js',
        array('jquery'),
        filemtime(get_template_directory() . '/inc/compression.js'),
        true
    );
    
    wp_localize_script('clarkes-compression', 'clarkesCompression', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('clarkes_compression'),
    ));
    
    wp_enqueue_style(
        'clarkes-compression-style',
        get_template_directory_uri() . '/inc/compression.css',
        array(),
        filemtime(get_template_directory() . '/inc/compression.css')
    );
}
}
add_action('admin_enqueue_scripts', 'clarkes_compression_scripts');

/**
 * Media Compression Page
 */
if (!function_exists('clarkes_media_compression_page')) {
function clarkes_media_compression_page() {
    $total_media = wp_count_posts('attachment')->inherit;
    $media_sizes = clarkes_get_media_sizes();
    ?>
    <div class="wrap clarkes-compression">
        <div class="compression-header">
            <h1><?php _e('Media Compression', 'clarkes-terraclean'); ?></h1>
            <p class="description"><?php _e('Reduce image and video file sizes to improve page load speeds', 'clarkes-terraclean'); ?></p>
        </div>
        
        <!-- Stats -->
        <div class="compression-stats">
            <div class="stat-box">
                <h3><?php echo number_format($total_media); ?></h3>
                <p>Total Media Files</p>
            </div>
            <div class="stat-box">
                <h3><?php echo $media_sizes['formatted']; ?></h3>
                <p>Total Media Size</p>
            </div>
            <div class="stat-box">
                <h3 id="savings-amount">0 MB</h3>
                <p>Potential Savings</p>
            </div>
        </div>
        
        <!-- Compression Options -->
        <div class="compression-options">
            <h2><?php _e('Compression Settings', 'clarkes-terraclean'); ?></h2>
            
            <div class="option-group">
                <label>
                    <input type="checkbox" id="compress-images" checked />
                    <?php _e('Compress Images (JPEG, PNG, WebP)', 'clarkes-terraclean'); ?>
                </label>
                <div class="option-details" id="image-options" style="margin-left: 30px; margin-top: 10px;">
                    <label>
                        <?php _e('JPEG Quality:', 'clarkes-terraclean'); ?>
                        <input type="range" id="jpeg-quality" min="60" max="100" value="85" />
                        <span id="jpeg-quality-value">85</span>%
                    </label>
                    <label>
                        <?php _e('PNG Compression Level:', 'clarkes-terraclean'); ?>
                        <input type="range" id="png-compression" min="1" max="9" value="6" />
                        <span id="png-compression-value">6</span>
                    </label>
                    <label>
                        <input type="checkbox" id="convert-to-webp" />
                        <?php _e('Convert to WebP format (smaller file sizes)', 'clarkes-terraclean'); ?>
                    </label>
                    <label>
                        <input type="checkbox" id="resize-large-images" checked />
                        <?php _e('Resize images larger than', 'clarkes-terraclean'); ?>
                        <input type="number" id="max-image-width" value="1920" style="width: 80px;" /> px width
                    </label>
                </div>
            </div>
            
            <div class="option-group">
                <label>
                    <input type="checkbox" id="compress-videos" />
                    <?php _e('Compress Videos (MP4, WebM)', 'clarkes-terraclean'); ?>
                </label>
                <div class="option-details" id="video-options" style="margin-left: 30px; margin-top: 10px; display: none;">
                    <p class="description"><?php _e('Video compression requires FFmpeg to be installed on your server. Contact your hosting provider if this feature is unavailable.', 'clarkes-terraclean'); ?></p>
                    <label>
                        <?php _e('Video Quality (CRF):', 'clarkes-terraclean'); ?>
                        <input type="range" id="video-quality" min="18" max="28" value="23" />
                        <span id="video-quality-value">23</span> (lower = higher quality, larger file)
                    </label>
                    <label>
                        <input type="checkbox" id="resize-videos" />
                        <?php _e('Resize videos larger than', 'clarkes-terraclean'); ?>
                        <input type="number" id="max-video-width" value="1920" style="width: 80px;" /> px width
                    </label>
                </div>
            </div>
            
            <div class="option-group">
                <label>
                    <?php _e('Compression Mode:', 'clarkes-terraclean'); ?>
                    <select id="compression-mode">
                        <option value="all"><?php _e('All Media Files', 'clarkes-terraclean'); ?></option>
                        <option value="selected"><?php _e('Selected Media Files', 'clarkes-terraclean'); ?></option>
                        <option value="large"><?php _e('Files Larger Than', 'clarkes-terraclean'); ?></option>
                    </select>
                </label>
                <div id="size-threshold" style="margin-top: 10px; display: none;">
                    <label>
                        <?php _e('Minimum file size (MB):', 'clarkes-terraclean'); ?>
                        <input type="number" id="min-file-size" value="1" step="0.1" />
                    </label>
                </div>
            </div>
            
            <div class="option-group">
                <label>
                    <input type="checkbox" id="backup-originals" checked />
                    <?php _e('Create backup of original files', 'clarkes-terraclean'); ?>
                </label>
            </div>
        </div>
        
        <!-- Media Selection -->
        <div class="media-selection-section">
            <h2><?php _e('Select Media to Compress', 'clarkes-terraclean'); ?></h2>
            <div class="selection-actions">
                <button type="button" id="select-media-batch" class="button button-primary">
                    <?php _e('Select Media Files', 'clarkes-terraclean'); ?>
                </button>
                <button type="button" id="select-all-media" class="button">
                    <?php _e('Select All Media', 'clarkes-terraclean'); ?>
                </button>
                <button type="button" id="clear-selection" class="button">
                    <?php _e('Clear Selection', 'clarkes-terraclean'); ?>
                </button>
            </div>
            <div id="selected-media-list" class="selected-media-list">
                <p class="description"><?php _e('No media selected. Click "Select Media Files" to choose files to compress.', 'clarkes-terraclean'); ?></p>
            </div>
        </div>
        
        <!-- Start Compression -->
        <div class="compression-actions">
            <button type="button" id="start-compression" class="button button-primary button-large" disabled>
                <?php _e('Start Compression', 'clarkes-terraclean'); ?>
            </button>
            <div id="compression-progress" style="display: none; margin-top: 20px;">
                <div class="progress-bar">
                    <div id="compression-progress-bar" class="progress-fill" style="width: 0%;"></div>
                </div>
                <p id="compression-status"><?php _e('Preparing...', 'clarkes-terraclean'); ?></p>
            </div>
        </div>
        
        <!-- Results -->
        <div id="compression-results" class="compression-results" style="display: none;">
            <h2><?php _e('Compression Results', 'clarkes-terraclean'); ?></h2>
            <div id="results-content"></div>
        </div>
    </div>
    <?php
}
}

/**
 * AJAX: Get Media List
 */
if (!function_exists('clarkes_ajax_get_media_list')) {
function clarkes_ajax_get_media_list() {
    check_ajax_referer('clarkes_compression', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' => array('image', 'video'),
        'posts_per_page' => -1,
        'post_status' => 'inherit',
    );
    
    $media = get_posts($args);
    $media_list = array();
    
    foreach ($media as $item) {
        $file_path = get_attached_file($item->ID);
        $file_size = file_exists($file_path) ? filesize($file_path) : 0;
        $metadata = wp_get_attachment_metadata($item->ID);
        
        $media_list[] = array(
            'id' => $item->ID,
            'title' => $item->post_title,
            'filename' => basename($file_path),
            'url' => wp_get_attachment_url($item->ID),
            'type' => $item->post_mime_type,
            'size' => $file_size,
            'size_formatted' => size_format($file_size),
            'width' => isset($metadata['width']) ? $metadata['width'] : 0,
            'height' => isset($metadata['height']) ? $metadata['height'] : 0,
        );
    }
    
    wp_send_json_success(array('media' => $media_list));
}
}
add_action('wp_ajax_clarkes_get_media_list', 'clarkes_ajax_get_media_list');

/**
 * AJAX: Compress Media
 */
if (!function_exists('clarkes_ajax_compress_media')) {
function clarkes_ajax_compress_media() {
    check_ajax_referer('clarkes_compression', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $media_id = isset($_POST['media_id']) ? absint($_POST['media_id']) : 0;
    $settings = isset($_POST['settings']) ? $_POST['settings'] : array();
    
    if (!$media_id) {
        wp_send_json_error(array('message' => 'Invalid media ID'));
        return;
    }
    
    $result = clarkes_compress_single_media($media_id, $settings);
    
    if ($result['success']) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result);
    }
}
}
add_action('wp_ajax_clarkes_compress_media', 'clarkes_ajax_compress_media');

/**
 * Compress Single Media File
 */
if (!function_exists('clarkes_compress_single_media')) {
function clarkes_compress_single_media($media_id, $settings = array()) {
    $file_path = get_attached_file($media_id);
    
    if (!file_exists($file_path)) {
        return array('success' => false, 'message' => 'File not found');
    }
    
    $mime_type = get_post_mime_type($media_id);
    $original_size = filesize($file_path);
    
    // Image compression
    if (strpos($mime_type, 'image') !== false) {
        return clarkes_compress_image($file_path, $media_id, $settings);
    }
    
    // Video compression
    if (strpos($mime_type, 'video') !== false) {
        return clarkes_compress_video($file_path, $media_id, $settings);
    }
    
    return array('success' => false, 'message' => 'Unsupported file type');
}
}

/**
 * Compress Image
 */
if (!function_exists('clarkes_compress_image')) {
function clarkes_compress_image($file_path, $media_id, $settings = array()) {
    $jpeg_quality = isset($settings['jpeg_quality']) ? intval($settings['jpeg_quality']) : 85;
    $png_compression = isset($settings['png_compression']) ? intval($settings['png_compression']) : 6;
    $convert_to_webp = isset($settings['convert_to_webp']) ? $settings['convert_to_webp'] : false;
    $max_width = isset($settings['max_width']) ? intval($settings['max_width']) : 1920;
    $backup = isset($settings['backup']) ? $settings['backup'] : true;
    
    $original_size = filesize($file_path);
    $image_info = getimagesize($file_path);
    
    if (!$image_info) {
        return array('success' => false, 'message' => 'Invalid image file');
    }
    
    $width = $image_info[0];
    $height = $image_info[1];
    $mime_type = $image_info['mime'];
    
    // Create backup
    if ($backup) {
        $backup_path = $file_path . '.backup';
        copy($file_path, $backup_path);
    }
    
    // Load image
    switch ($mime_type) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($file_path);
            break;
        case 'image/png':
            $image = imagecreatefrompng($file_path);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($file_path);
            break;
        case 'image/webp':
            $image = imagecreatefromwebp($file_path);
            break;
        default:
            return array('success' => false, 'message' => 'Unsupported image format');
    }
    
    if (!$image) {
        return array('success' => false, 'message' => 'Failed to load image');
    }
    
    // Resize if needed
    $new_width = $width;
    $new_height = $height;
    if ($width > $max_width) {
        $new_width = $max_width;
        $new_height = intval(($height * $max_width) / $width);
        
        $resized = imagecreatetruecolor($new_width, $new_height);
        
        // Preserve transparency for PNG
        if ($mime_type === 'image/png') {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
            imagefill($resized, 0, 0, $transparent);
        }
        
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagedestroy($image);
        $image = $resized;
    }
    
    // Save compressed image
    $output_path = $file_path;
    if ($convert_to_webp && function_exists('imagewebp')) {
        $output_path = str_replace(array('.jpg', '.jpeg', '.png'), '.webp', $file_path);
    }
    
    $saved = false;
    if ($convert_to_webp && function_exists('imagewebp')) {
        $saved = imagewebp($image, $output_path, $jpeg_quality);
    } elseif ($mime_type === 'image/jpeg' || strpos($file_path, '.jpg') !== false || strpos($file_path, '.jpeg') !== false) {
        $saved = imagejpeg($image, $output_path, $jpeg_quality);
    } elseif ($mime_type === 'image/png' || strpos($file_path, '.png') !== false) {
        $saved = imagepng($image, $output_path, $png_compression);
    } elseif ($mime_type === 'image/gif' || strpos($file_path, '.gif') !== false) {
        $saved = imagegif($image, $output_path);
    }
    
    imagedestroy($image);
    
    if (!$saved) {
        return array('success' => false, 'message' => 'Failed to save compressed image');
    }
    
    $new_size = filesize($output_path);
    $savings = $original_size - $new_size;
    $savings_percent = $original_size > 0 ? round(($savings / $original_size) * 100, 2) : 0;
    
    // Update attachment metadata if file changed
    if ($output_path !== $file_path && $convert_to_webp) {
        update_attached_file($media_id, $output_path);
    }
    
    // Regenerate thumbnails
    if (function_exists('wp_generate_attachment_metadata')) {
        $metadata = wp_generate_attachment_metadata($media_id, $output_path);
        wp_update_attachment_metadata($media_id, $metadata);
    }
    
    return array(
        'success' => true,
        'original_size' => $original_size,
        'new_size' => $new_size,
        'savings' => $savings,
        'savings_percent' => $savings_percent,
        'message' => sprintf('Compressed: Saved %s (%d%%)', size_format($savings), $savings_percent),
    );
}
}

/**
 * Compress Video
 */
if (!function_exists('clarkes_compress_video')) {
function clarkes_compress_video($file_path, $media_id, $settings = array()) {
    // Check if FFmpeg is available
    $ffmpeg_path = clarkes_get_ffmpeg_path();
    if (!$ffmpeg_path) {
        return array('success' => false, 'message' => 'FFmpeg is not available on this server. Contact your hosting provider.');
    }
    
    $crf = isset($settings['video_quality']) ? intval($settings['video_quality']) : 23;
    $max_width = isset($settings['max_width']) ? intval($settings['max_width']) : 1920;
    $backup = isset($settings['backup']) ? $settings['backup'] : true;
    
    $original_size = filesize($file_path);
    $output_path = $file_path . '.compressed.mp4';
    
    // Create backup
    if ($backup) {
        $backup_path = $file_path . '.backup';
        copy($file_path, $backup_path);
    }
    
    // Build FFmpeg command
    $command = escapeshellarg($ffmpeg_path) . ' -i ' . escapeshellarg($file_path);
    $command .= ' -c:v libx264 -crf ' . $crf;
    $command .= ' -preset medium';
    $command .= ' -c:a aac -b:a 128k';
    
    if ($max_width > 0) {
        $command .= ' -vf scale=' . $max_width . ':-2';
    }
    
    $command .= ' -y ' . escapeshellarg($output_path);
    $command .= ' 2>&1';
    
    exec($command, $output, $return_code);
    
    if ($return_code !== 0 || !file_exists($output_path)) {
        return array('success' => false, 'message' => 'Video compression failed: ' . implode("\n", $output));
    }
    
    $new_size = filesize($output_path);
    $savings = $original_size - $new_size;
    $savings_percent = $original_size > 0 ? round(($savings / $original_size) * 100, 2) : 0;
    
    // Replace original with compressed
    if ($savings > 0) {
        rename($output_path, $file_path);
    } else {
        unlink($output_path);
    }
    
    return array(
        'success' => true,
        'original_size' => $original_size,
        'new_size' => $new_size,
        'savings' => $savings,
        'savings_percent' => $savings_percent,
        'message' => sprintf('Compressed: Saved %s (%d%%)', size_format($savings), $savings_percent),
    );
}
}

/**
 * Get FFmpeg Path
 */
if (!function_exists('clarkes_get_ffmpeg_path')) {
function clarkes_get_ffmpeg_path() {
    $paths = array('ffmpeg', '/usr/bin/ffmpeg', '/usr/local/bin/ffmpeg', '/opt/homebrew/bin/ffmpeg');
    
    foreach ($paths as $path) {
        $output = array();
        $return = 0;
        exec(escapeshellarg($path) . ' -version 2>&1', $output, $return);
        if ($return === 0) {
            return $path;
        }
    }
    
    return false;
}
}

