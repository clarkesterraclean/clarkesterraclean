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
                <button type="button" class="button button-primary button-large" id="btn-crawl-and-fix" style="background: #4ade80; border-color: #4ade80; color: white;">
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0;"><?php _e('Processing...', 'clarkes-terraclean'); ?></h3>
                <div style="display: flex; gap: 10px;">
                    <button type="button" class="button button-small" id="btn-clear-log" style="display: none;">
                        <?php _e('Clear Log', 'clarkes-terraclean'); ?>
                    </button>
                    <button type="button" class="button button-small" id="btn-refresh-log" style="display: none;">
                        <?php _e('Refresh', 'clarkes-terraclean'); ?>
                    </button>
                </div>
            </div>
            <div style="background: #f3f4f6; border-radius: 4px; height: 30px; overflow: hidden; margin-bottom: 15px; position: relative;">
                <div id="seo-progress-bar" style="background: linear-gradient(90deg, #3b82f6, #8b5cf6); height: 100%; width: 0%; transition: width 0.3s; position: relative;">
                    <div id="seo-progress-percent" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-weight: bold; font-size: 12px; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">0%</div>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <p id="seo-progress-text" style="margin: 0; font-weight: 500; color: #374151;"></p>
                <span id="seo-progress-stats" style="color: #6b7280; font-size: 14px;"></span>
            </div>
            
            <!-- Activity Log -->
            <div id="seo-activity-log" style="background: #1f2937; color: #e5e7eb; padding: 15px; border-radius: 4px; max-height: 300px; overflow-y: auto; font-family: 'Courier New', monospace; font-size: 12px; line-height: 1.6;">
                <div id="seo-log-content">
                    <div style="color: #9ca3af; font-style: italic;"><?php _e('Activity log will appear here...', 'clarkes-terraclean'); ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
}

/**
 * Site Analysis Page
 */
if (!function_exists('clarkes_seo_analysis_page')) {
function clarkes_seo_analysis_page() {
    ?>
    <div class="wrap clarkes-seo-analysis">
        <h1><?php _e('Site Analysis', 'clarkes-terraclean'); ?></h1>
        
        <div class="analysis-controls" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px; margin: 20px 0;">
            <button type="button" class="button button-primary button-large" id="btn-start-analysis">
                <?php _e('Start Full Site Analysis', 'clarkes-terraclean'); ?>
            </button>
            <button type="button" class="button button-large" id="btn-analyze-page">
                <?php _e('Analyze Current Page', 'clarkes-terraclean'); ?>
            </button>
        </div>
        
        <div id="analysis-results" style="display: none;">
            <!-- Results will be populated via AJAX -->
        </div>
    </div>
    <?php
}
}

/**
 * Content Optimizer Page
 */
if (!function_exists('clarkes_seo_content_page')) {
function clarkes_seo_content_page() {
    $posts = get_posts(array('numberposts' => -1, 'post_status' => 'publish'));
    $pages = get_pages();
    ?>
    <div class="wrap clarkes-seo-content">
        <h1><?php _e('Content Optimizer', 'clarkes-terraclean'); ?></h1>
        
        <div class="content-list" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px; margin: 20px 0;">
            <h2><?php _e('Pages & Posts', 'clarkes-terraclean'); ?></h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Title', 'clarkes-terraclean'); ?></th>
                        <th><?php _e('Type', 'clarkes-terraclean'); ?></th>
                        <th><?php _e('SEO Score', 'clarkes-terraclean'); ?></th>
                        <th><?php _e('Meta Title', 'clarkes-terraclean'); ?></th>
                        <th><?php _e('Meta Description', 'clarkes-terraclean'); ?></th>
                        <th><?php _e('Actions', 'clarkes-terraclean'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_merge($posts, $pages) as $item) : 
                        $seo_score = get_post_meta($item->ID, '_clarkes_seo_score', true) ?: 0;
                        $meta_title = get_post_meta($item->ID, '_clarkes_seo_title', true);
                        $meta_desc = get_post_meta($item->ID, '_clarkes_seo_description', true);
                    ?>
                    <tr>
                        <td><strong><?php echo esc_html($item->post_title); ?></strong></td>
                        <td><?php echo $item->post_type === 'page' ? __('Page', 'clarkes-terraclean') : __('Post', 'clarkes-terraclean'); ?></td>
                        <td>
                            <span style="color: <?php echo $seo_score >= 80 ? '#4ade80' : ($seo_score >= 60 ? '#fbbf24' : '#ef4444'); ?>; font-weight: bold;">
                                <?php echo esc_html($seo_score); ?>/100
                            </span>
                        </td>
                        <td><?php echo $meta_title ? esc_html($meta_title) : '<span style="color: #999;">—</span>'; ?></td>
                        <td><?php echo $meta_desc ? esc_html(substr($meta_desc, 0, 50)) . '...' : '<span style="color: #999;">—</span>'; ?></td>
                        <td>
                            <button type="button" class="button button-small optimize-page-btn" data-id="<?php echo $item->ID; ?>">
                                <?php _e('Optimize', 'clarkes-terraclean'); ?>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
}

/**
 * Schema & Structured Data Page
 */
if (!function_exists('clarkes_seo_schema_page')) {
function clarkes_seo_schema_page() {
    $schema_enabled = get_option('clarkes_seo_schema_enabled', true);
    $schema_type = get_option('clarkes_seo_schema_type', 'LocalBusiness');
    ?>
    <div class="wrap clarkes-seo-schema">
        <h1><?php _e('Schema & Structured Data', 'clarkes-terraclean'); ?></h1>
        
        <form method="post" action="options.php" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px; margin: 20px 0;">
            <?php settings_fields('clarkes_seo_schema'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Enable Schema Markup', 'clarkes-terraclean'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="clarkes_seo_schema_enabled" value="1" <?php checked($schema_enabled, 1); ?> />
                            <?php _e('Enable JSON-LD structured data', 'clarkes-terraclean'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Schema Type', 'clarkes-terraclean'); ?></th>
                    <td>
                        <select name="clarkes_seo_schema_type">
                            <option value="LocalBusiness" <?php selected($schema_type, 'LocalBusiness'); ?>>Local Business</option>
                            <option value="AutomotiveBusiness" <?php selected($schema_type, 'AutomotiveBusiness'); ?>>Automotive Business</option>
                            <option value="Service" <?php selected($schema_type, 'Service'); ?>>Service</option>
                            <option value="Organization" <?php selected($schema_type, 'Organization'); ?>>Organization</option>
                        </select>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
        
        <div class="schema-preview" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px; margin: 20px 0;">
            <h2><?php _e('Schema Preview', 'clarkes-terraclean'); ?></h2>
            <pre id="schema-preview-code" style="background: #f3f4f6; padding: 15px; border-radius: 4px; overflow-x: auto;"><?php echo esc_html(json_encode(clarkes_generate_schema_data(), JSON_PRETTY_PRINT)); ?></pre>
        </div>
    </div>
    <?php
}
}

/**
 * SEO Settings Page
 */
if (!function_exists('clarkes_seo_settings_page')) {
function clarkes_seo_settings_page() {
    ?>
    <div class="wrap clarkes-seo-settings">
        <h1><?php _e('SEO Settings', 'clarkes-terraclean'); ?></h1>
        
        <form method="post" action="options.php" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px; margin: 20px 0;">
            <?php settings_fields('clarkes_seo_settings'); ?>
            
            <h2><?php _e('General Settings', 'clarkes-terraclean'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Site Name', 'clarkes-terraclean'); ?></th>
                    <td><input type="text" name="clarkes_seo_site_name" value="<?php echo esc_attr(get_option('clarkes_seo_site_name', get_bloginfo('name'))); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Default Meta Description', 'clarkes-terraclean'); ?></th>
                    <td><textarea name="clarkes_seo_default_description" rows="3" class="large-text"><?php echo esc_textarea(get_option('clarkes_seo_default_description', '')); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Focus Keywords', 'clarkes-terraclean'); ?></th>
                    <td>
                        <input type="text" name="clarkes_seo_focus_keywords" value="<?php echo esc_attr(get_option('clarkes_seo_focus_keywords', 'DPF cleaning, engine cleaning, EGR cleaning, Kent')); ?>" class="large-text" />
                        <p class="description"><?php _e('Comma-separated list of primary keywords', 'clarkes-terraclean'); ?></p>
                    </td>
                </tr>
            </table>
            
            <h2><?php _e('Local SEO', 'clarkes-terraclean'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Business Name', 'clarkes-terraclean'); ?></th>
                    <td><input type="text" name="clarkes_seo_business_name" value="<?php echo esc_attr(get_option('clarkes_seo_business_name', "Clarke's DPF & Engine Specialists")); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Address', 'clarkes-terraclean'); ?></th>
                    <td><textarea name="clarkes_seo_address" rows="3" class="large-text"><?php echo esc_textarea(get_option('clarkes_seo_address', '')); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Phone', 'clarkes-terraclean'); ?></th>
                    <td><input type="text" name="clarkes_seo_phone" value="<?php echo esc_attr(get_option('clarkes_seo_phone', '07706 230867')); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Google Maps URL', 'clarkes-terraclean'); ?></th>
                    <td><input type="url" name="clarkes_seo_map_url" value="<?php echo esc_url(get_option('clarkes_seo_map_url', '')); ?>" class="large-text" /></td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
}

/**
 * Register SEO Settings
 */
if (!function_exists('clarkes_register_seo_settings')) {
function clarkes_register_seo_settings() {
    register_setting('clarkes_seo_settings', 'clarkes_seo_site_name');
    register_setting('clarkes_seo_settings', 'clarkes_seo_default_description');
    register_setting('clarkes_seo_settings', 'clarkes_seo_focus_keywords');
    register_setting('clarkes_seo_settings', 'clarkes_seo_business_name');
    register_setting('clarkes_seo_settings', 'clarkes_seo_address');
    register_setting('clarkes_seo_settings', 'clarkes_seo_phone');
    register_setting('clarkes_seo_settings', 'clarkes_seo_map_url');
    register_setting('clarkes_seo_schema', 'clarkes_seo_schema_enabled');
    register_setting('clarkes_seo_schema', 'clarkes_seo_schema_type');
}
}
add_action('admin_init', 'clarkes_register_seo_settings');

/**
 * Generate Schema.org JSON-LD Data
 */
if (!function_exists('clarkes_generate_schema_data')) {
function clarkes_generate_schema_data() {
    $schema_type = get_option('clarkes_seo_schema_type', 'LocalBusiness');
    $business_name = get_option('clarkes_seo_business_name', "Clarke's DPF & Engine Specialists");
    $address = get_option('clarkes_seo_address', '');
    $phone = get_option('clarkes_seo_phone', '07706 230867');
    $site_url = home_url();
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => $schema_type,
        'name' => $business_name,
        'url' => $site_url,
        'telephone' => $phone,
    );
    
    if ($address) {
        $schema['address'] = array(
            '@type' => 'PostalAddress',
            'streetAddress' => $address,
        );
    }
    
    // Add services
    $schema['hasOfferCatalog'] = array(
        '@type' => 'OfferCatalog',
        'name' => 'Engine Cleaning Services',
        'itemListElement' => array(
            array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'DPF Cleaning')),
            array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'Engine Carbon Cleaning')),
            array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'EGR Valve Cleaning')),
            array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'Injector Cleaning')),
        ),
    );
    
    return $schema;
}
}

/**
 * Output Schema Data in Head
 */
if (!function_exists('clarkes_output_schema_data')) {
function clarkes_output_schema_data() {
    if (!get_option('clarkes_seo_schema_enabled', true)) {
        return;
    }
    
    $schema = clarkes_generate_schema_data();
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
}
add_action('wp_head', 'clarkes_output_schema_data', 5);

/**
 * Add Meta Tags to Head
 */
if (!function_exists('clarkes_add_seo_meta_tags')) {
function clarkes_add_seo_meta_tags() {
    global $post;
    
    if (is_singular() && $post) {
        $meta_title = get_post_meta($post->ID, '_clarkes_seo_title', true);
        $meta_description = get_post_meta($post->ID, '_clarkes_seo_description', true);
        $meta_keywords = get_post_meta($post->ID, '_clarkes_seo_keywords', true);
        
        // For case studies, use SEO title if available
        if (get_post_type($post->ID) === 'case_study' && !$meta_title) {
            $meta_title = $post->post_title;
        }
        
        if (!$meta_title) {
            $meta_title = $post->post_title . ' - ' . get_bloginfo('name');
        }
        
        // For case studies, use SEO description if available
        if (get_post_type($post->ID) === 'case_study' && !$meta_description) {
            $meta_description = get_post_meta($post->ID, '_clarkes_seo_description', true);
        }
        
        if (!$meta_description) {
            $meta_description = wp_trim_words(strip_tags($post->post_content), 30);
        }
        
        // Meta tags
        if ($meta_description) {
            echo '<meta name="description" content="' . esc_attr($meta_description) . '" />' . "\n";
        }
        if ($meta_keywords) {
            echo '<meta name="keywords" content="' . esc_attr($meta_keywords) . '" />' . "\n";
        }
        
        // Open Graph
        echo '<meta property="og:title" content="' . esc_attr($meta_title) . '" />' . "\n";
        if ($meta_description) {
            echo '<meta property="og:description" content="' . esc_attr($meta_description) . '" />' . "\n";
        }
        echo '<meta property="og:url" content="' . esc_url(get_permalink($post->ID)) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        
        if (has_post_thumbnail($post->ID)) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
            if ($image) {
                echo '<meta property="og:image" content="' . esc_url($image[0]) . '" />' . "\n";
                echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '" />' . "\n";
                echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '" />' . "\n";
            }
        }
        
        // Twitter Card
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($meta_title) . '" />' . "\n";
        if ($meta_description) {
            echo '<meta name="twitter:description" content="' . esc_attr($meta_description) . '" />' . "\n";
        }
        
        // Canonical URL
        echo '<link rel="canonical" href="' . esc_url(get_permalink($post->ID)) . '" />' . "\n";
    } else {
        // Homepage or Archive pages
        $site_name = get_option('clarkes_seo_site_name', get_bloginfo('name'));
        $default_desc = get_option('clarkes_seo_default_description', '');
        $home_desc = $default_desc ?: get_bloginfo('description');
        
        if ($home_desc) {
            echo '<meta name="description" content="' . esc_attr($home_desc) . '" />' . "\n";
        }
        echo '<meta property="og:title" content="' . esc_attr($site_name) . '" />' . "\n";
        if ($home_desc) {
            echo '<meta property="og:description" content="' . esc_attr($home_desc) . '" />' . "\n";
        }
        echo '<meta property="og:url" content="' . esc_url(home_url()) . '" />' . "\n";
        echo '<meta property="og:type" content="website" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />' . "\n";
        echo '<link rel="canonical" href="' . esc_url(home_url()) . '" />' . "\n";
    }
}
}
add_action('wp_head', 'clarkes_add_seo_meta_tags', 1);

/**
 * AJAX: Crawl Site
 */
if (!function_exists('clarkes_ajax_crawl_site')) {
function clarkes_ajax_crawl_site() {
    check_ajax_referer('clarkes_seo_module', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $auto_fix = isset($_POST['auto_fix']) && $_POST['auto_fix'] === 'true';
    
    $posts = get_posts(array('numberposts' => -1, 'post_status' => 'publish'));
    $pages = get_pages();
    $all_items = array_merge($posts, $pages);
    
    $issues = array();
    $total_score = 0;
    $analyzed = 0;
    $fixed_count = 0;
    
    foreach ($all_items as $item) {
        $analysis = clarkes_analyze_page_seo($item->ID, true); // Return detailed analysis
        $score = $analysis['score'];
        $total_score += $score;
        $analyzed++;
        
        // Collect issues
        if ($score < 60) {
            $issues[] = array(
                'severity' => 'critical',
                'title' => sprintf(__('Low SEO score for: %s', 'clarkes-terraclean'), $item->post_title),
                'description' => sprintf(__('Page has SEO score of %d/100', 'clarkes-terraclean'), $score),
                'page_id' => $item->ID,
                'issues' => $analysis['issues'],
            );
        }
        
        // Auto-fix if enabled
        if ($auto_fix && $score < 80) {
            $fixed = clarkes_auto_optimize_page($item->ID, $analysis);
            if ($fixed) {
                $fixed_count++;
                // Re-analyze after fix
                $new_analysis = clarkes_analyze_page_seo($item->ID, true);
                $total_score -= $score;
                $total_score += $new_analysis['score'];
            }
        } else {
            // Just collect issues
            if (!get_post_meta($item->ID, '_clarkes_seo_title', true)) {
                $issues[] = array(
                    'severity' => 'warning',
                    'title' => sprintf(__('Missing meta title: %s', 'clarkes-terraclean'), $item->post_title),
                    'description' => __('Page is missing a custom meta title', 'clarkes-terraclean'),
                    'page_id' => $item->ID,
                );
            }
            
            if (!get_post_meta($item->ID, '_clarkes_seo_description', true)) {
                $issues[] = array(
                    'severity' => 'warning',
                    'title' => sprintf(__('Missing meta description: %s', 'clarkes-terraclean'), $item->post_title),
                    'description' => __('Page is missing a custom meta description', 'clarkes-terraclean'),
                    'page_id' => $item->ID,
                );
            }
        }
    }
    
    $average_score = $analyzed > 0 ? round($total_score / $analyzed) : 0;
    update_option('clarkes_seo_score', $average_score);
    update_option('clarkes_seo_issues', $issues);
    update_option('clarkes_seo_last_crawl', current_time('mysql'));
    
    $message = sprintf(__('Analyzed %d pages. Average SEO score: %d/100', 'clarkes-terraclean'), $analyzed, $average_score);
    if ($auto_fix && $fixed_count > 0) {
        $message .= sprintf(__('. Auto-fixed %d pages.', 'clarkes-terraclean'), $fixed_count);
    }
    
    wp_send_json_success(array(
        'score' => $average_score,
        'analyzed' => $analyzed,
        'issues' => count($issues),
        'fixed' => $fixed_count,
        'message' => $message,
    ));
}
}
add_action('wp_ajax_clarkes_crawl_site', 'clarkes_ajax_crawl_site');

/**
 * Analyze Page SEO - Enhanced with detailed issue detection
 */
if (!function_exists('clarkes_analyze_page_seo')) {
function clarkes_analyze_page_seo($post_id, $detailed = false) {
    $score = 100;
    $post = get_post($post_id);
    $issues = array();
    $focus_keywords = explode(',', get_option('clarkes_seo_focus_keywords', 'DPF cleaning, engine cleaning, EGR cleaning, Kent'));
    $focus_keywords = array_map('trim', $focus_keywords);
    
    // Check meta title
    $meta_title = get_post_meta($post_id, '_clarkes_seo_title', true);
    if (!$meta_title) {
        $score -= 15;
        if ($detailed) {
            $issues[] = array('type' => 'missing_meta_title', 'severity' => 'critical');
        }
    } else {
        $title_length = strlen($meta_title);
        if ($title_length < 30) {
            $score -= 10;
            if ($detailed) {
                $issues[] = array('type' => 'title_too_short', 'severity' => 'warning', 'current' => $title_length, 'recommended' => '30-60');
            }
        } elseif ($title_length > 60) {
            $score -= 10;
            if ($detailed) {
                $issues[] = array('type' => 'title_too_long', 'severity' => 'warning', 'current' => $title_length, 'recommended' => '30-60');
            }
        }
        // Check if title contains focus keywords
        $title_lower = strtolower($meta_title);
        $has_keyword = false;
        foreach ($focus_keywords as $keyword) {
            if (stripos($title_lower, strtolower($keyword)) !== false) {
                $has_keyword = true;
                break;
            }
        }
        if (!$has_keyword && !empty($focus_keywords[0])) {
            $score -= 5;
            if ($detailed) {
                $issues[] = array('type' => 'title_missing_keyword', 'severity' => 'warning');
            }
        }
    }
    
    // Check meta description
    $meta_description = get_post_meta($post_id, '_clarkes_seo_description', true);
    if (!$meta_description) {
        $score -= 15;
        if ($detailed) {
            $issues[] = array('type' => 'missing_meta_description', 'severity' => 'critical');
        }
    } else {
        $desc_length = strlen($meta_description);
        if ($desc_length < 120) {
            $score -= 10;
            if ($detailed) {
                $issues[] = array('type' => 'description_too_short', 'severity' => 'warning', 'current' => $desc_length, 'recommended' => '120-160');
            }
        } elseif ($desc_length > 160) {
            $score -= 10;
            if ($detailed) {
                $issues[] = array('type' => 'description_too_long', 'severity' => 'warning', 'current' => $desc_length, 'recommended' => '120-160');
            }
        }
    }
    
    // Check content length
    $content = strip_tags($post->post_content);
    $content_length = strlen($content);
    if ($content_length < 300) {
        $score -= 10;
        if ($detailed) {
            $issues[] = array('type' => 'content_too_short', 'severity' => 'warning', 'current' => $content_length, 'recommended' => '300+');
        }
    }
    
    // Check keyword density
    if (!empty($focus_keywords[0])) {
        $primary_keyword = strtolower(trim($focus_keywords[0]));
        $content_lower = strtolower($content);
        $word_count = str_word_count($content_lower);
        $keyword_count = substr_count($content_lower, $primary_keyword);
        $density = $word_count > 0 ? ($keyword_count / $word_count) * 100 : 0;
        
        if ($density < 0.5) {
            $score -= 5;
            if ($detailed) {
                $issues[] = array('type' => 'low_keyword_density', 'severity' => 'warning', 'current' => round($density, 2), 'recommended' => '0.5-2.5');
            }
        } elseif ($density > 3) {
            $score -= 10;
            if ($detailed) {
                $issues[] = array('type' => 'high_keyword_density', 'severity' => 'critical', 'current' => round($density, 2), 'recommended' => '0.5-2.5');
            }
        }
    }
    
    // Check for images with alt text
    $images = get_attached_media('image', $post_id);
    $images_with_alt = 0;
    $images_without_alt = array();
    foreach ($images as $image) {
        $alt = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
        if ($alt) {
            $images_with_alt++;
        } else {
            $images_without_alt[] = $image->ID;
        }
    }
    if (count($images) > 0) {
        $alt_percentage = ($images_with_alt / count($images)) * 100;
        if ($alt_percentage < 80) {
            $score -= 10;
            if ($detailed) {
                $issues[] = array('type' => 'missing_image_alt', 'severity' => 'warning', 'missing' => count($images_without_alt), 'total' => count($images), 'image_ids' => $images_without_alt);
            }
        }
    }
    
    // Check for headings
    $has_h1 = (stripos($post->post_content, '<h1') !== false);
    $has_h2 = (stripos($post->post_content, '<h2') !== false);
    if (!$has_h1 && !$has_h2) {
        $score -= 10;
        if ($detailed) {
            $issues[] = array('type' => 'missing_headings', 'severity' => 'warning');
        }
    }
    
    // Check for internal links
    $internal_links = preg_match_all('/<a[^>]+href=["\']' . preg_quote(home_url(), '/') . '/', $post->post_content);
    if ($internal_links < 2) {
        $score -= 5;
        if ($detailed) {
            $issues[] = array('type' => 'insufficient_internal_links', 'severity' => 'warning', 'current' => $internal_links, 'recommended' => '2+');
        }
    }
    
    // Check for external links (no-follow)
    $external_links = preg_match_all('/<a[^>]+href=["\'](?!' . preg_quote(home_url(), '/') . ')/', $post->post_content);
    if ($external_links > 0) {
        $nofollow_count = preg_match_all('/<a[^>]+rel=["\'][^"\']*nofollow[^"\']*["\']/', $post->post_content);
        if ($nofollow_count < $external_links * 0.8) {
            $score -= 3;
            if ($detailed) {
                $issues[] = array('type' => 'external_links_no_nofollow', 'severity' => 'info', 'current' => $nofollow_count, 'total' => $external_links);
            }
        }
    }
    
    $final_score = max(0, $score);
    update_post_meta($post_id, '_clarkes_seo_score', $final_score);
    
    if ($detailed) {
        return array(
            'score' => $final_score,
            'issues' => $issues,
            'meta_title' => $meta_title,
            'meta_description' => $meta_description,
            'content_length' => $content_length,
            'images_count' => count($images),
            'images_with_alt' => $images_with_alt,
        );
    }
    
    return $final_score;
}
}

/**
 * Auto-Optimize Page - Fixes SEO issues automatically
 */
if (!function_exists('clarkes_auto_optimize_page')) {
function clarkes_auto_optimize_page($post_id, $analysis) {
    $post = get_post($post_id);
    if (!$post) {
        return false;
    }
    
    $fixed = false;
    $focus_keywords = explode(',', get_option('clarkes_seo_focus_keywords', 'DPF cleaning, engine cleaning, EGR cleaning, Kent'));
    $focus_keywords = array_map('trim', $focus_keywords);
    $primary_keyword = !empty($focus_keywords[0]) ? trim($focus_keywords[0]) : '';
    $ai_api_key = get_option('clarkes_ai_api_key', '');
    
    // Fix missing or poor meta title
    $meta_title = get_post_meta($post_id, '_clarkes_seo_title', true);
    $needs_title_fix = false;
    foreach ($analysis['issues'] as $issue) {
        if (in_array($issue['type'], array('missing_meta_title', 'title_too_short', 'title_too_long', 'title_missing_keyword'))) {
            $needs_title_fix = true;
            break;
        }
    }
    
    if ($needs_title_fix) {
        if ($ai_api_key) {
            $seo_data = clarkes_generate_ai_seo_content($post, $ai_api_key, implode(', ', $focus_keywords));
            if (isset($seo_data['title']) && strlen($seo_data['title']) >= 30 && strlen($seo_data['title']) <= 60) {
                update_post_meta($post_id, '_clarkes_seo_title', $seo_data['title']);
                $fixed = true;
            }
        }
        
        if (!$meta_title || $needs_title_fix) {
            // Generate title from post title + keyword
            $new_title = $post->post_title;
            if ($primary_keyword && stripos(strtolower($new_title), strtolower($primary_keyword)) === false) {
                $new_title = $primary_keyword . ' - ' . $new_title;
            }
            $new_title .= ' | ' . get_bloginfo('name');
            
            // Ensure proper length
            if (strlen($new_title) > 60) {
                $new_title = substr($new_title, 0, 57) . '...';
            } elseif (strlen($new_title) < 30 && $primary_keyword) {
                $new_title = $primary_keyword . ' Services - ' . $post->post_title;
            }
            
            update_post_meta($post_id, '_clarkes_seo_title', $new_title);
            $fixed = true;
        }
    }
    
    // Fix missing or poor meta description
    $meta_description = get_post_meta($post_id, '_clarkes_seo_description', true);
    $needs_desc_fix = false;
    foreach ($analysis['issues'] as $issue) {
        if (in_array($issue['type'], array('missing_meta_description', 'description_too_short', 'description_too_long'))) {
            $needs_desc_fix = true;
            break;
        }
    }
    
    if ($needs_desc_fix) {
        if ($ai_api_key) {
            $seo_data = clarkes_generate_ai_seo_content($post, $ai_api_key, implode(', ', $focus_keywords));
            if (isset($seo_data['description']) && strlen($seo_data['description']) >= 120 && strlen($seo_data['description']) <= 160) {
                update_post_meta($post_id, '_clarkes_seo_description', $seo_data['description']);
                $fixed = true;
            }
        }
        
        if (!$meta_description || $needs_desc_fix) {
            // Generate description from content
            $content = wp_strip_all_tags($post->post_content);
            $new_desc = wp_trim_words($content, 25);
            
            // Add keyword if not present
            if ($primary_keyword && stripos(strtolower($new_desc), strtolower($primary_keyword)) === false) {
                $new_desc = $primary_keyword . ' services. ' . $new_desc;
            }
            
            // Ensure proper length
            if (strlen($new_desc) > 160) {
                $new_desc = substr($new_desc, 0, 157) . '...';
            } elseif (strlen($new_desc) < 120) {
                $new_desc .= ' Professional service in Kent. Contact us today for expert assistance.';
            }
            
            update_post_meta($post_id, '_clarkes_seo_description', $new_desc);
            $fixed = true;
        }
    }
    
    // Fix missing image alt tags
    foreach ($analysis['issues'] as $issue) {
        if ($issue['type'] === 'missing_image_alt' && isset($issue['image_ids'])) {
            foreach ($issue['image_ids'] as $image_id) {
                $image = get_post($image_id);
                if ($image) {
                    $filename = basename(get_attached_file($image_id));
                    $filename_clean = str_replace(array('-', '_', '.jpg', '.jpeg', '.png', '.gif', '.webp'), ' ', $filename);
                    $filename_clean = ucwords(strtolower($filename_clean));
                    
                    // Try to make it more descriptive
                    if ($primary_keyword) {
                        $alt_text = $primary_keyword . ' - ' . $filename_clean;
                    } else {
                        $alt_text = $post->post_title . ' - ' . $filename_clean;
                    }
                    
                    update_post_meta($image_id, '_wp_attachment_image_alt', $alt_text);
                    $fixed = true;
                }
            }
        }
    }
    
    // Fix keyword density (if too low)
    foreach ($analysis['issues'] as $issue) {
        if ($issue['type'] === 'low_keyword_density' && $primary_keyword) {
            $content = $post->post_content;
            $content_lower = strtolower($content);
            $word_count = str_word_count($content_lower);
            $keyword_count = substr_count($content_lower, strtolower($primary_keyword));
            $current_density = $word_count > 0 ? ($keyword_count / $word_count) * 100 : 0;
            
            // Target density: 1-2%
            $target_density = 1.5;
            $target_count = ceil(($word_count * $target_density) / 100);
            $needed = max(0, $target_count - $keyword_count);
            
            if ($needed > 0 && $needed <= 3) {
                // Add keyword naturally in content (only if reasonable)
                $content_updated = $content;
                // Add keyword in first paragraph if not present
                if (stripos($content, $primary_keyword) === false) {
                    $first_para = strpos($content, '</p>');
                    if ($first_para !== false) {
                        $insert_pos = $first_para;
                        $insert_text = ' ' . $primary_keyword;
                        $content_updated = substr_replace($content_updated, $insert_text, $insert_pos, 0);
                    }
                }
                
                // Only update if we made a reasonable change
                if ($content_updated !== $content) {
                    wp_update_post(array(
                        'ID' => $post_id,
                        'post_content' => $content_updated
                    ));
                    $fixed = true;
                }
            }
        }
    }
    
    // Fix missing headings
    foreach ($analysis['issues'] as $issue) {
        if ($issue['type'] === 'missing_headings') {
            $content = $post->post_content;
            // Add H2 if content is substantial but no headings
            if (strlen(strip_tags($content)) > 200 && stripos($content, '<h') === false) {
                $first_para = strpos($content, '<p>');
                if ($first_para !== false) {
                    $heading = '<h2>' . esc_html($post->post_title) . '</h2>';
                    $content_updated = substr_replace($content, $heading . "\n\n", $first_para, 0);
                    wp_update_post(array(
                        'ID' => $post_id,
                        'post_content' => $content_updated
                    ));
                    $fixed = true;
                }
            }
        }
    }
    
    return $fixed;
}
}

/**
 * AJAX: Optimize Page
 */
if (!function_exists('clarkes_ajax_optimize_page')) {
function clarkes_ajax_optimize_page() {
    check_ajax_referer('clarkes_seo_module', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    if (!$post_id) {
        wp_send_json_error(array('message' => 'Invalid post ID'));
        return;
    }
    
    $post = get_post($post_id);
    if (!$post) {
        wp_send_json_error(array('message' => 'Post not found'));
        return;
    }
    
    // Get detailed analysis
    $analysis = clarkes_analyze_page_seo($post_id, true);
    
    // Auto-optimize
    $fixed = clarkes_auto_optimize_page($post_id, $analysis);
    
    // Generate SEO content using AI (for additional optimization)
    $ai_api_key = get_option('clarkes_ai_api_key', '');
    $focus_keywords = get_option('clarkes_seo_focus_keywords', 'DPF cleaning, engine cleaning, EGR cleaning, Kent');
    
    $seo_data = clarkes_generate_ai_seo_content($post, $ai_api_key, $focus_keywords);
    
    // Save SEO data
    if (isset($seo_data['title'])) {
        update_post_meta($post_id, '_clarkes_seo_title', $seo_data['title']);
    }
    if (isset($seo_data['description'])) {
        update_post_meta($post_id, '_clarkes_seo_description', $seo_data['description']);
    }
    if (isset($seo_data['keywords'])) {
        update_post_meta($post_id, '_clarkes_seo_keywords', $seo_data['keywords']);
    }
    
    // Re-analyze
    $new_analysis = clarkes_analyze_page_seo($post_id, true);
    
    wp_send_json_success(array(
        'score' => $new_analysis['score'],
        'seo_data' => $seo_data,
        'fixed_issues' => count($analysis['issues']) - count($new_analysis['issues']),
        'message' => __('Page optimized successfully', 'clarkes-terraclean'),
    ));
}
}
add_action('wp_ajax_clarkes_optimize_page', 'clarkes_ajax_optimize_page');

/**
 * Generate AI SEO Content
 */
if (!function_exists('clarkes_generate_ai_seo_content')) {
function clarkes_generate_ai_seo_content($post, $ai_api_key, $focus_keywords) {
    $content = wp_strip_all_tags($post->post_content);
    $content_preview = substr($content, 0, 1000);
    
    $prompt = "Generate SEO-optimized metadata for this WordPress page:\n\n";
    $prompt .= "Title: " . $post->post_title . "\n\n";
    $prompt .= "Content: " . $content_preview . "\n\n";
    $prompt .= "Focus Keywords: " . $focus_keywords . "\n\n";
    $prompt .= "Generate:\n";
    $prompt .= "1. A meta title (50-60 characters, include primary keyword)\n";
    $prompt .= "2. A meta description (150-160 characters, compelling, include keywords)\n";
    $prompt .= "3. Comma-separated keywords (5-10 relevant keywords)\n\n";
    $prompt .= "Return JSON format: {\"title\": \"...\", \"description\": \"...\", \"keywords\": \"...\"}";
    
    $seo_data = array(
        'title' => $post->post_title . ' - ' . get_bloginfo('name'),
        'description' => wp_trim_words($content, 25),
        'keywords' => $focus_keywords,
    );
    
    if (!empty($ai_api_key)) {
        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
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
        
        if (!is_wp_error($response)) {
            $body = json_decode(wp_remote_retrieve_body($response), true);
            if (isset($body['choices'][0]['message']['content'])) {
                $ai_content = $body['choices'][0]['message']['content'];
                if (preg_match('/\{.*\}/s', $ai_content, $matches)) {
                    $ai_data = json_decode($matches[0], true);
                    if ($ai_data) {
                        $seo_data = array_merge($seo_data, $ai_data);
                    }
                }
            }
        }
    }
    
    return $seo_data;
}
}

/**
 * Generate XML Sitemap
 */
if (!function_exists('clarkes_generate_sitemap')) {
function clarkes_generate_sitemap() {
    $posts = get_posts(array('numberposts' => -1, 'post_status' => 'publish'));
    $pages = get_pages();
    
    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    // Homepage
    $sitemap .= '<url>' . "\n";
    $sitemap .= '<loc>' . esc_url(home_url()) . '</loc>' . "\n";
    $sitemap .= '<lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
    $sitemap .= '<changefreq>daily</changefreq>' . "\n";
    $sitemap .= '<priority>1.0</priority>' . "\n";
    $sitemap .= '</url>' . "\n";
    
    // Pages
    foreach ($pages as $page) {
        $sitemap .= '<url>' . "\n";
        $sitemap .= '<loc>' . esc_url(get_permalink($page->ID)) . '</loc>' . "\n";
        $sitemap .= '<lastmod>' . date('Y-m-d', strtotime($page->post_modified)) . '</lastmod>' . "\n";
        $sitemap .= '<changefreq>weekly</changefreq>' . "\n";
        $sitemap .= '<priority>0.8</priority>' . "\n";
        $sitemap .= '</url>' . "\n";
    }
    
    // Posts
    foreach ($posts as $post) {
        $sitemap .= '<url>' . "\n";
        $sitemap .= '<loc>' . esc_url(get_permalink($post->ID)) . '</loc>' . "\n";
        $sitemap .= '<lastmod>' . date('Y-m-d', strtotime($post->post_modified)) . '</lastmod>' . "\n";
        $sitemap .= '<changefreq>monthly</changefreq>' . "\n";
        $sitemap .= '<priority>0.6</priority>' . "\n";
        $sitemap .= '</url>' . "\n";
    }
    
    $sitemap .= '</urlset>';
    
    // Save sitemap
    $upload_dir = wp_upload_dir();
    $sitemap_file = $upload_dir['basedir'] . '/sitemap.xml';
    file_put_contents($sitemap_file, $sitemap);
    
    return $sitemap_file;
}
}

/**
 * AJAX: Generate Sitemap
 */
if (!function_exists('clarkes_ajax_generate_sitemap')) {
function clarkes_ajax_generate_sitemap() {
    check_ajax_referer('clarkes_seo_module', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $sitemap_file = clarkes_generate_sitemap();
    $sitemap_url = str_replace(ABSPATH, home_url('/'), $sitemap_file);
    
    wp_send_json_success(array(
        'url' => $sitemap_url,
        'message' => __('Sitemap generated successfully', 'clarkes-terraclean'),
    ));
}
}
add_action('wp_ajax_clarkes_generate_sitemap', 'clarkes_ajax_generate_sitemap');

/**
 * AJAX: Get All Pages
 */
if (!function_exists('clarkes_ajax_get_all_pages')) {
function clarkes_ajax_get_all_pages() {
    check_ajax_referer('clarkes_seo_module', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $posts = get_posts(array('numberposts' => -1, 'post_status' => 'publish', 'fields' => 'ids'));
    $pages = get_pages(array('post_status' => 'publish', 'fields' => 'ids'));
    
    wp_send_json_success(array(
        'ids' => array_merge($posts, $pages),
    ));
}
}
add_action('wp_ajax_clarkes_get_all_pages', 'clarkes_ajax_get_all_pages');

/**
 * Generate Robots.txt
 */
if (!function_exists('clarkes_generate_robots_txt')) {
function clarkes_generate_robots_txt() {
    $sitemap_url = home_url('/sitemap.xml');
    
    $robots = "User-agent: *\n";
    $robots .= "Allow: /\n";
    $robots .= "Disallow: /wp-admin/\n";
    $robots .= "Disallow: /wp-includes/\n";
    $robots .= "Disallow: /wp-content/plugins/\n";
    $robots .= "Disallow: /?s=\n";
    $robots .= "Disallow: /search/\n\n";
    $robots .= "Sitemap: " . $sitemap_url . "\n";
    
    return $robots;
}
}

/**
 * Add Robots Meta to Head
 */
if (!function_exists('clarkes_add_robots_meta')) {
function clarkes_add_robots_meta() {
    echo '<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />' . "\n";
}
}
add_action('wp_head', 'clarkes_add_robots_meta', 1);

/**
 * Add Breadcrumbs Schema
 */
if (!function_exists('clarkes_add_breadcrumbs_schema')) {
function clarkes_add_breadcrumbs_schema() {
    if (!is_singular()) {
        return;
    }
    
    global $post;
    $breadcrumbs = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array(
            array(
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => home_url(),
            ),
        ),
    );
    
    if (is_page() && $post->post_parent) {
        $ancestors = get_post_ancestors($post->ID);
        $ancestors = array_reverse($ancestors);
        
        $position = 2;
        foreach ($ancestors as $ancestor_id) {
            $breadcrumbs['itemListElement'][] = array(
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => get_the_title($ancestor_id),
                'item' => get_permalink($ancestor_id),
            );
        }
    }
    
    $breadcrumbs['itemListElement'][] = array(
        '@type' => 'ListItem',
        'position' => count($breadcrumbs['itemListElement']) + 1,
        'name' => get_the_title(),
        'item' => get_permalink(),
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
}
add_action('wp_head', 'clarkes_add_breadcrumbs_schema', 6);

/**
 * Optimize Images for SEO
 */
if (!function_exists('clarkes_optimize_image_seo')) {
function clarkes_optimize_image_seo($post_id) {
    $images = get_attached_media('image', $post_id);
    
    foreach ($images as $image) {
        $alt_text = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
        
        if (empty($alt_text)) {
            // Generate alt text from image filename or post title
            $filename = basename(get_attached_file($image->ID));
            $filename_clean = str_replace(array('-', '_', '.'), ' ', $filename);
            $filename_clean = ucwords($filename_clean);
            
            update_post_meta($image->ID, '_wp_attachment_image_alt', $filename_clean);
        }
    }
}
}

/**
 * Add Performance Optimizations
 */
if (!function_exists('clarkes_add_performance_meta')) {
function clarkes_add_performance_meta() {
    // Preconnect to important domains
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" />' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . "\n";
    
    // DNS Prefetch
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com" />' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com" />' . "\n";
}
}
add_action('wp_head', 'clarkes_add_performance_meta', 0);

/**
 * Add Viewport Meta for Mobile
 */
if (!function_exists('clarkes_add_viewport_meta')) {
function clarkes_add_viewport_meta() {
    if (!has_action('wp_head', 'wp_head')) {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />' . "\n";
    }
}
}
add_action('wp_head', 'clarkes_add_viewport_meta', 0);

