<?php
/**
 * Unified Theme Control Center
 * Central Dashboard for All Theme Features
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Theme Control Center Menu
 */
if (!function_exists('clarkes_add_control_center_menu')) {
function clarkes_add_control_center_menu() {
    add_menu_page(
        __('Theme Control Center', 'clarkes-terraclean'),
        __('Theme Control', 'clarkes-terraclean'),
        'manage_options',
        'clarkes-control-center',
        'clarkes_control_center_page',
        'dashicons-admin-generic',
        2
    );
}
}
add_action('admin_menu', 'clarkes_add_control_center_menu');

/**
 * Enqueue Control Center Scripts
 */
if (!function_exists('clarkes_control_center_scripts')) {
function clarkes_control_center_scripts($hook) {
    if (strpos($hook, 'clarkes-control-center') === false) {
        return;
    }
    
    wp_enqueue_script('jquery');
    wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js', array(), '3.9.1', true);
    
    wp_enqueue_script(
        'clarkes-control-center',
        get_template_directory_uri() . '/inc/control-center.js',
        array('jquery', 'chart-js'),
        filemtime(get_template_directory() . '/inc/control-center.js'),
        true
    );
    
    wp_localize_script('clarkes-control-center', 'clarkesControlCenter', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('clarkes_control_center'),
    ));
    
    wp_enqueue_style(
        'clarkes-control-center-style',
        get_template_directory_uri() . '/inc/control-center.css',
        array(),
        filemtime(get_template_directory() . '/inc/control-center.css')
    );
}
}
add_action('admin_enqueue_scripts', 'clarkes_control_center_scripts');

/**
 * Control Center Main Page
 */
if (!function_exists('clarkes_control_center_page')) {
function clarkes_control_center_page() {
    $total_media = wp_count_posts('attachment')->inherit;
    $total_pages = wp_count_posts('page')->publish;
    $total_posts = wp_count_posts()->publish;
    $seo_score = get_option('clarkes_seo_score', 0);
    
    // Get media sizes
    $media_sizes = clarkes_get_media_sizes();
    ?>
    <div class="wrap clarkes-control-center">
        <div class="control-center-header">
            <h1><?php _e('Theme Control Center', 'clarkes-terraclean'); ?></h1>
            <p class="description"><?php _e('Unified dashboard for all theme features and settings', 'clarkes-terraclean'); ?></p>
        </div>
        
        <!-- Quick Stats -->
        <div class="stats-grid">
            <div class="stat-card stat-primary">
                <div class="stat-icon">📊</div>
                <div class="stat-content">
                    <h3><?php echo number_format($total_media); ?></h3>
                    <p>Media Files</p>
                </div>
            </div>
            <div class="stat-card stat-success">
                <div class="stat-icon">📄</div>
                <div class="stat-content">
                    <h3><?php echo number_format($total_pages + $total_posts); ?></h3>
                    <p>Pages & Posts</p>
                </div>
            </div>
            <div class="stat-card stat-warning">
                <div class="stat-icon">🔍</div>
                <div class="stat-content">
                    <h3><?php echo esc_html($seo_score); ?>/100</h3>
                    <p>SEO Score</p>
                </div>
            </div>
            <div class="stat-card stat-info">
                <div class="stat-icon">💾</div>
                <div class="stat-content">
                    <h3><?php echo size_format($media_sizes['total']); ?></h3>
                    <p>Total Media Size</p>
                </div>
            </div>
        </div>
        
        <!-- Main Modules Grid -->
        <div class="modules-grid">
            <!-- Media Editor Module -->
            <div class="module-card">
                <div class="module-header">
                    <div class="module-icon">🎨</div>
                    <h2><?php _e('Media Editor', 'clarkes-terraclean'); ?></h2>
                </div>
                <div class="module-content">
                    <p><?php _e('Edit images and videos with advanced tools: crop, resize, filters, AI SEO, and more.', 'clarkes-terraclean'); ?></p>
                    <div class="module-actions">
                        <a href="<?php echo admin_url('admin.php?page=clarkes-media-editor'); ?>" class="button button-primary">
                            <?php _e('Open Media Editor', 'clarkes-terraclean'); ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Image Compression Module -->
            <div class="module-card">
                <div class="module-header">
                    <div class="module-icon">🗜️</div>
                    <h2><?php _e('Image Compression', 'clarkes-terraclean'); ?></h2>
                </div>
                <div class="module-content">
                    <p><?php _e('Batch compress images and videos to reduce file sizes and improve page load speeds.', 'clarkes-terraclean'); ?></p>
                    <div class="module-actions">
                        <a href="<?php echo admin_url('admin.php?page=clarkes-media-compression'); ?>" class="button button-primary">
                            <?php _e('Compress Media', 'clarkes-terraclean'); ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Page Builder Module -->
            <div class="module-card">
                <div class="module-header">
                    <div class="module-icon">🏗️</div>
                    <h2><?php _e('Page Builder', 'clarkes-terraclean'); ?></h2>
                </div>
                <div class="module-content">
                    <p><?php _e('Visual drag-and-drop page builder with 20+ element types for creating custom layouts.', 'clarkes-terraclean'); ?></p>
                    <div class="module-actions">
                        <a href="<?php echo admin_url('admin.php?page=clarkes-page-builder'); ?>" class="button button-primary">
                            <?php _e('Open Page Builder', 'clarkes-terraclean'); ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- SEO Module -->
            <div class="module-card">
                <div class="module-header">
                    <div class="module-icon">🔍</div>
                    <h2><?php _e('AI SEO Module', 'clarkes-terraclean'); ?></h2>
                </div>
                <div class="module-content">
                    <p><?php _e('Comprehensive SEO optimization with AI-powered content analysis and Google compliance.', 'clarkes-terraclean'); ?></p>
                    <div class="module-actions">
                        <a href="<?php echo admin_url('admin.php?page=clarkes-seo-module'); ?>" class="button button-primary">
                            <?php _e('Open SEO Module', 'clarkes-terraclean'); ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Quick Add Module -->
            <div class="module-card">
                <div class="module-header">
                    <div class="module-icon">➕</div>
                    <h2><?php _e('Quick Add', 'clarkes-terraclean'); ?></h2>
                </div>
                <div class="module-content">
                    <p><?php _e('Mobile-friendly workflow for quickly adding content on the go with camera upload support.', 'clarkes-terraclean'); ?></p>
                    <div class="module-actions">
                        <a href="<?php echo admin_url('admin.php?page=clarkes-quick-add'); ?>" class="button button-primary">
                            <?php _e('Quick Add Content', 'clarkes-terraclean'); ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Customizer Module -->
            <div class="module-card">
                <div class="module-header">
                    <div class="module-icon">⚙️</div>
                    <h2><?php _e('Theme Customizer', 'clarkes-terraclean'); ?></h2>
                </div>
                <div class="module-content">
                    <p><?php _e('Customize colors, layouts, typography, spacing, and all theme settings with live preview.', 'clarkes-terraclean'); ?></p>
                    <div class="module-actions">
                        <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary">
                            <?php _e('Open Customizer', 'clarkes-terraclean'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="activity-section">
            <h2><?php _e('Recent Activity', 'clarkes-terraclean'); ?></h2>
            <div class="activity-list">
                <div class="activity-item">
                    <span class="activity-icon">📝</span>
                    <div class="activity-content">
                        <p><strong>Last SEO Analysis:</strong> <?php echo esc_html(get_option('clarkes_seo_last_crawl', 'Never')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
}

/**
 * Get Media Sizes
 */
if (!function_exists('clarkes_get_media_sizes')) {
function clarkes_get_media_sizes() {
    global $wpdb;
    
    $total_size = $wpdb->get_var("
        SELECT SUM(meta_value) 
        FROM {$wpdb->postmeta} 
        WHERE meta_key = '_wp_attachment_file_size'
    ");
    
    if (!$total_size) {
        // Fallback: calculate from files
        $upload_dir = wp_upload_dir();
        $total_size = 0;
        if (is_dir($upload_dir['basedir'])) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($upload_dir['basedir']));
            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $total_size += $file->getSize();
                }
            }
        }
    }
    
    return array(
        'total' => $total_size ?: 0,
        'formatted' => size_format($total_size ?: 0),
    );
}
}

