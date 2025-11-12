<?php
/**
 * Comprehensive AI SEO Module
 * Advanced SEO optimization following Google's latest guidelines
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add SEO Module Menu Page
 */
if (!function_exists('clarkes_add_seo_module_menu')) {
function clarkes_add_seo_module_menu() {
    add_menu_page(
        __('AI SEO Module', 'clarkes-terraclean'),
        __('AI SEO', 'clarkes-terraclean'),
        'manage_options',
        'clarkes-seo-module',
        'clarkes_seo_module_page',
        'dashicons-search',
        30
    );
    
    add_submenu_page(
        'clarkes-seo-module',
        __('SEO Dashboard', 'clarkes-terraclean'),
        __('Dashboard', 'clarkes-terraclean'),
        'manage_options',
        'clarkes-seo-module',
        'clarkes_seo_module_page'
    );
    
    add_submenu_page(
        'clarkes-seo-module',
        __('Site Analysis', 'clarkes-terraclean'),
        __('Site Analysis', 'clarkes-terraclean'),
        'manage_options',
        'clarkes-seo-analysis',
        'clarkes_seo_analysis_page'
    );
    
    add_submenu_page(
        'clarkes-seo-module',
        __('Content Optimizer', 'clarkes-terraclean'),
        __('Content Optimizer', 'clarkes-terraclean'),
        'manage_options',
        'clarkes-seo-content',
        'clarkes_seo_content_page'
    );
    
    add_submenu_page(
        'clarkes-seo-module',
        __('Schema & Structured Data', 'clarkes-terraclean'),
        __('Schema Data', 'clarkes-terraclean'),
        'manage_options',
        'clarkes-seo-schema',
        'clarkes_seo_schema_page'
    );
    
    add_submenu_page(
        'clarkes-seo-module',
        __('SEO Settings', 'clarkes-terraclean'),
        __('Settings', 'clarkes-terraclean'),
        'manage_options',
        'clarkes-seo-settings',
        'clarkes_seo_settings_page'
    );
}
}
add_action('admin_menu', 'clarkes_add_seo_module_menu');

/**
 * Enqueue SEO Module Scripts
 */
if (!function_exists('clarkes_seo_module_scripts')) {
function clarkes_seo_module_scripts($hook) {
    if (strpos($hook, 'clarkes-seo') === false) {
        return;
    }
    
    wp_enqueue_script('jquery');
    wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js', array(), '3.9.1', true);
    
    wp_enqueue_script(
        'clarkes-seo-module',
        get_template_directory_uri() . '/inc/seo-module.js',
        array('jquery', 'chart-js'),
        filemtime(get_template_directory() . '/inc/seo-module.js'),
        true
    );
    
    wp_localize_script('clarkes-seo-module', 'clarkesSEO', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('clarkes_seo_module'),
        'ai_api_key' => get_option('clarkes_ai_api_key', ''),
    ));
    
    wp_enqueue_style(
        'clarkes-seo-module-style',
        get_template_directory_uri() . '/inc/seo-module.css',
        array(),
        filemtime(get_template_directory() . '/inc/seo-module.css')
    );
}
}
add_action('admin_enqueue_scripts', 'clarkes_seo_module_scripts');

/**
 * SEO Module Dashboard Page
 */
if (!function_exists('clarkes_seo_module_page')) {
function clarkes_seo_module_page() {
    $site_url = home_url();
    $total_posts = wp_count_posts()->publish;
    $total_pages = wp_count_posts('page')->publish;
    $seo_score = get_option('clarkes_seo_score', 0);
    $last_crawl = get_option('clarkes_seo_last_crawl', 'Never');
    
    // Get SEO issues
    $seo_issues = get_option('clarkes_seo_issues', array());
    $critical_issues = array_filter($seo_issues, function($issue) {
        return isset($issue['severity']) && $issue['severity'] === 'critical';
    });
    $warning_issues = array_filter($seo_issues, function($issue) {
        return isset($issue['severity']) && $issue['severity'] === 'warning';
    });
    ?>
    <div class="wrap clarkes-seo-dashboard">
        <h1><?php _e('AI SEO Module Dashboard', 'clarkes-terraclean'); ?></h1>
        
        <!-- Overview Cards -->
        <div class="seo-overview" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 20px 0;">
            <div class="seo-card" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px;">
                <h3 style="margin: 0 0 10px 0; color: #666;"><?php _e('SEO Score', 'clarkes-terraclean'); ?></h3>
                <div style="font-size: 48px; font-weight: bold; color: <?php echo $seo_score >= 80 ? '#4ade80' : ($seo_score >= 60 ? '#fbbf24' : '#ef4444'); ?>;">
                    <?php echo esc_html($seo_score); ?>/100
                </div>
            </div>
            
            <div class="seo-card" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px;">
                <h3 style="margin: 0 0 10px 0; color: #666;"><?php _e('Total Pages', 'clarkes-terraclean'); ?></h3>
                <div style="font-size: 48px; font-weight: bold; color: #3b82f6;">
                    <?php echo esc_html($total_pages + $total_posts); ?>
                </div>
            </div>
            
            <div class="seo-card" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px;">
                <h3 style="margin: 0 0 10px 0; color: #666;"><?php _e('Critical Issues', 'clarkes-terraclean'); ?></h3>
                <div style="font-size: 48px; font-weight: bold; color: #ef4444;">
                    <?php echo count($critical_issues); ?>
                </div>
            </div>
            
            <div class="seo-card" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px;">
                <h3 style="margin: 0 0 10px 0; color: #666;"><?php _e('Last Crawl', 'clarkes-terraclean'); ?></h3>
                <div style="font-size: 18px; font-weight: bold; color: #666;">
                    <?php echo esc_html($last_crawl); ?>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="seo-actions" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px; margin: 20px 0;">
            <h2><?php _e('Quick Actions', 'clarkes-terraclean'); ?></h2>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 15px;">
                <button type="button" class="button button-primary button-large" id="btn-crawl-site">
                    <?php _e('🕷️ Crawl & Analyze Site', 'clarkes-terraclean'); ?>
                </button>
                <button type="button" class="button button-primary button-large" id="btn-crawl-and-fix" style="background: #4ade80; border-color: #4ade80;">
                    <?php _e('⚡ Crawl & Auto-Fix All Issues', 'clarkes-terraclean'); ?>
                </button>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="button" class="button button-large" id="btn-generate-sitemap">
                    <?php _e('🗺️ Generate Sitemap', 'clarkes-terraclean'); ?>
                </button>
                <button type="button" class="button button-large" id="btn-optimize-all">
                    <?php _e('⚡ Optimize All Pages', 'clarkes-terraclean'); ?>
                </button>
                <button type="button" class="button button-large" id="btn-generate-schema">
                    <?php _e('📊 Generate Schema Data', 'clarkes-terraclean'); ?>
                </button>
                <button type="button" class="button button-large" id="btn-seo-report">
                    <?php _e('📈 Generate SEO Report', 'clarkes-terraclean'); ?>
                </button>
            </div>
        </div>
        
        <!-- SEO Issues -->
        <?php if (!empty($seo_issues)) : ?>
        <div class="seo-issues" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px; margin: 20px 0;">
            <h2><?php _e('SEO Issues', 'clarkes-terraclean'); ?></h2>
            
            <?php if (!empty($critical_issues)) : ?>
            <div class="critical-issues" style="margin-bottom: 20px;">
                <h3 style="color: #ef4444;"><?php _e('Critical Issues', 'clarkes-terraclean'); ?></h3>
                <ul style="list-style: none; padding: 0;">
                    <?php foreach ($critical_issues as $issue) : ?>
                    <li style="padding: 10px; margin-bottom: 10px; background: #fee2e2; border-left: 4px solid #ef4444;">
                        <strong><?php echo esc_html($issue['title']); ?></strong>
                        <p style="margin: 5px 0 0 0;"><?php echo esc_html($issue['description']); ?></p>
                        <?php if (isset($issue['fix'])) : ?>
                        <button type="button" class="button button-small" data-fix="<?php echo esc_attr($issue['fix']); ?>">
                            <?php _e('Fix Now', 'clarkes-terraclean'); ?>
                        </button>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($warning_issues)) : ?>
            <div class="warning-issues">
                <h3 style="color: #f59e0b;"><?php _e('Warnings', 'clarkes-terraclean'); ?></h3>
                <ul style="list-style: none; padding: 0;">
                    <?php foreach (array_slice($warning_issues, 0, 5) as $issue) : ?>
                    <li style="padding: 10px; margin-bottom: 10px; background: #fef3c7; border-left: 4px solid #f59e0b;">
                        <strong><?php echo esc_html($issue['title']); ?></strong>
                        <p style="margin: 5px 0 0 0;"><?php echo esc_html($issue['description']); ?></p>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- SEO Recommendations -->
        <div class="seo-recommendations" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px; margin: 20px 0;">
            <h2><?php _e('AI-Powered Recommendations', 'clarkes-terraclean'); ?></h2>
            <div id="seo-recommendations-list">
                <p class="description"><?php _e('Run site analysis to get AI-powered SEO recommendations', 'clarkes-terraclean'); ?></p>
            </div>
        </div>
        
        <!-- Progress Indicator -->
        <div id="seo-progress" style="display: none; background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px; margin: 20px 0;">
            <h3><?php _e('Processing...', 'clarkes-terraclean'); ?></h3>
            <div style="background: #f3f4f6; border-radius: 4px; height: 30px; overflow: hidden;">
                <div id="seo-progress-bar" style="background: #3b82f6; height: 100%; width: 0%; transition: width 0.3s;"></div>
            </div>
            <p id="seo-progress-text" style="margin: 10px 0 0 0;"></p>
        </div>
    </div>
    <?php
}
}

// ... existing code ...
