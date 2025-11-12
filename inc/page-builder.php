<?php
/**
 * Comprehensive Page Builder Module
 * DIVI-Level Features for Visual Page Building
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Page Builder Menu
 */
if (!function_exists('clarkes_add_page_builder_menu')) {
function clarkes_add_page_builder_menu() {
    add_menu_page(
        __('Page Builder', 'clarkes-terraclean'),
        __('Page Builder', 'clarkes-terraclean'),
        'edit_pages',
        'clarkes-page-builder',
        'clarkes_page_builder_page',
        'dashicons-layout',
        4
    );
}
}
add_action('admin_menu', 'clarkes_add_page_builder_menu');

/**
 * Enqueue Page Builder Scripts
 */
if (!function_exists('clarkes_page_builder_scripts')) {
function clarkes_page_builder_scripts($hook) {
    // Always load on admin pages, check hook for builder page
    if (strpos($hook, 'clarkes-page-builder') === false) {
        if (!is_admin() || (is_admin() && strpos($hook, 'post.php') === false && strpos($hook, 'post-new.php') === false)) {
            return;
        }
    }
    
    // Enqueue on page edit screen
    global $post;
    if (is_admin() && isset($post) && $post->post_type === 'page') {
        $use_builder = get_post_meta($post->ID, '_clarkes_use_page_builder', true);
        if ($use_builder) {
            // Enqueue builder scripts for frontend preview
        }
    }
    
    if (strpos($hook, 'clarkes-page-builder') !== false) {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_media();
        
        // React-like state management
        wp_enqueue_script('vue-js', 'https://cdn.jsdelivr.net/npm/vue@3.3.4/dist/vue.global.prod.js', array(), '3.3.4', true);
        
        wp_enqueue_script(
            'clarkes-page-builder',
            get_template_directory_uri() . '/inc/page-builder.js',
            array('jquery', 'vue-js'),
            filemtime(get_template_directory() . '/inc/page-builder.js'),
            true
        );
        
        wp_localize_script('clarkes-page-builder', 'clarkesPageBuilder', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('clarkes_page_builder'),
        ));
        
        wp_enqueue_style(
            'clarkes-page-builder-style',
            get_template_directory_uri() . '/inc/page-builder.css',
            array(),
            filemtime(get_template_directory() . '/inc/page-builder.css')
        );
    }
}
}
add_action('admin_enqueue_scripts', 'clarkes_page_builder_scripts');
add_action('wp_enqueue_scripts', 'clarkes_page_builder_scripts');

/**
 * Add Page Builder Meta Box
 */
if (!function_exists('clarkes_add_page_builder_meta_box')) {
function clarkes_add_page_builder_meta_box() {
    add_meta_box(
        'clarkes-page-builder-meta',
        __('Page Builder', 'clarkes-terraclean'),
        'clarkes_page_builder_meta_box',
        'page',
        'normal',
        'high'
    );
}
}
add_action('add_meta_boxes', 'clarkes_add_page_builder_meta_box');

/**
 * Page Builder Meta Box
 */
if (!function_exists('clarkes_page_builder_meta_box')) {
function clarkes_page_builder_meta_box($post) {
    $use_builder = get_post_meta($post->ID, '_clarkes_use_page_builder', true);
    $builder_data = get_post_meta($post->ID, '_clarkes_page_builder_data', true);
    wp_nonce_field('clarkes_page_builder_save', 'clarkes_page_builder_nonce');
    ?>
    <div class="clarkes-page-builder-meta">
        <label>
            <input type="checkbox" name="clarkes_use_page_builder" value="1" <?php checked($use_builder, 1); ?> />
            <?php _e('Use Page Builder for this page', 'clarkes-terraclean'); ?>
        </label>
        <p class="description">
            <?php _e('Enable the visual page builder to design this page with drag-and-drop elements.', 'clarkes-terraclean'); ?>
        </p>
        <p>
            <a href="<?php echo admin_url('admin.php?page=clarkes-page-builder&post_id=' . $post->ID); ?>" class="button button-primary">
                <?php _e('Open Page Builder', 'clarkes-terraclean'); ?>
            </a>
        </p>
        <input type="hidden" name="clarkes_page_builder_data" id="clarkes_page_builder_data" value="<?php echo esc_attr(json_encode($builder_data)); ?>" />
    </div>
    <?php
}
}

/**
 * Save Page Builder Data
 */
if (!function_exists('clarkes_save_page_builder_data')) {
function clarkes_save_page_builder_data($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!isset($_POST['clarkes_page_builder_nonce']) || !wp_verify_nonce($_POST['clarkes_page_builder_nonce'], 'clarkes_page_builder_save')) {
        return;
    }
    
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }
    
    $use_builder = isset($_POST['clarkes_use_page_builder']) ? 1 : 0;
    update_post_meta($post_id, '_clarkes_use_page_builder', $use_builder);
    
    if (isset($_POST['clarkes_page_builder_data'])) {
        $builder_data = json_decode(stripslashes($_POST['clarkes_page_builder_data']), true);
        update_post_meta($post_id, '_clarkes_page_builder_data', $builder_data);
    }
}
}
add_action('save_post', 'clarkes_save_page_builder_data');

/**
 * Page Builder Main Page
 */
if (!function_exists('clarkes_page_builder_page')) {
function clarkes_page_builder_page() {
    $post_id = isset($_GET['post_id']) ? absint($_GET['post_id']) : 0;
    $post = $post_id ? get_post($post_id) : null;
    $builder_data = $post ? get_post_meta($post_id, '_clarkes_page_builder_data', true) : array();
    
    if (!$post) {
        // Show page selection
        $pages = get_pages();
        ?>
        <div class="wrap">
            <h1><?php _e('Page Builder', 'clarkes-terraclean'); ?></h1>
            <div class="page-selector" style="background: white; padding: 20px; border: 1px solid #ddd; border-radius: 4px; margin-top: 20px;">
                <h2><?php _e('Select a Page to Edit', 'clarkes-terraclean'); ?></h2>
                <ul style="list-style: none; padding: 0;">
                    <?php foreach ($pages as $page) : ?>
                    <li style="padding: 10px; border-bottom: 1px solid #eee;">
                        <a href="<?php echo admin_url('admin.php?page=clarkes-page-builder&post_id=' . $page->ID); ?>" class="button">
                            <?php echo esc_html($page->post_title); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
        return;
    }
    ?>
    <div class="wrap clarkes-page-builder-wrap" id="clarkes-page-builder-app">
        <div class="page-builder-header" style="background: white; padding: 15px; border-bottom: 1px solid #ddd; margin: -20px -20px 20px -20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="margin: 0;"><?php _e('Page Builder', 'clarkes-terraclean'); ?></h1>
                    <p style="margin: 5px 0 0 0; color: #666;"><?php echo esc_html($post->post_title); ?></p>
                </div>
                <div>
                    <button type="button" class="button" id="btn-preview"><?php _e('Preview', 'clarkes-terraclean'); ?></button>
                    <button type="button" class="button button-primary" id="btn-save-builder"><?php _e('Save', 'clarkes-terraclean'); ?></button>
                    <a href="<?php echo get_edit_post_link($post_id); ?>" class="button"><?php _e('Back to Editor', 'clarkes-terraclean'); ?></a>
                </div>
            </div>
        </div>
        
        <div class="page-builder-container" style="display: flex; height: calc(100vh - 120px);">
            <!-- Left Sidebar - Elements -->
            <div class="builder-sidebar-left" style="width: 300px; background: #f5f5f5; border-right: 1px solid #ddd; overflow-y: auto; padding: 15px;">
                <h3 style="margin-top: 0;"><?php _e('Elements', 'clarkes-terraclean'); ?></h3>
                
                <div class="element-categories">
                    <div class="element-category">
                        <h4><?php _e('Layout', 'clarkes-terraclean'); ?></h4>
                        <div class="element-list">
                            <div class="builder-element" data-type="row" draggable="true">
                                <span class="dashicons dashicons-editor-table"></span>
                                <?php _e('Row', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="column" draggable="true">
                                <span class="dashicons dashicons-columns"></span>
                                <?php _e('Column', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="container" draggable="true">
                                <span class="dashicons dashicons-align-center"></span>
                                <?php _e('Container', 'clarkes-terraclean'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="element-category">
                        <h4><?php _e('Content', 'clarkes-terraclean'); ?></h4>
                        <div class="element-list">
                            <div class="builder-element" data-type="text" draggable="true">
                                <span class="dashicons dashicons-text"></span>
                                <?php _e('Text', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="heading" draggable="true">
                                <span class="dashicons dashicons-heading"></span>
                                <?php _e('Heading', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="image" draggable="true">
                                <span class="dashicons dashicons-format-image"></span>
                                <?php _e('Image', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="video" draggable="true">
                                <span class="dashicons dashicons-video-alt3"></span>
                                <?php _e('Video', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="gallery" draggable="true">
                                <span class="dashicons dashicons-format-gallery"></span>
                                <?php _e('Gallery', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="button" draggable="true">
                                <span class="dashicons dashicons-admin-links"></span>
                                <?php _e('Button', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="divider" draggable="true">
                                <span class="dashicons dashicons-minus"></span>
                                <?php _e('Divider', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="spacer" draggable="true">
                                <span class="dashicons dashicons-arrow-down-alt"></span>
                                <?php _e('Spacer', 'clarkes-terraclean'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="element-category">
                        <h4><?php _e('Advanced', 'clarkes-terraclean'); ?></h4>
                        <div class="element-list">
                            <div class="builder-element" data-type="accordion" draggable="true">
                                <span class="dashicons dashicons-list-view"></span>
                                <?php _e('Accordion', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="tabs" draggable="true">
                                <span class="dashicons dashicons-category"></span>
                                <?php _e('Tabs', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="testimonial" draggable="true">
                                <span class="dashicons dashicons-format-quote"></span>
                                <?php _e('Testimonial', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="pricing" draggable="true">
                                <span class="dashicons dashicons-cart"></span>
                                <?php _e('Pricing Table', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="countdown" draggable="true">
                                <span class="dashicons dashicons-clock"></span>
                                <?php _e('Countdown', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="progress" draggable="true">
                                <span class="dashicons dashicons-chart-bar"></span>
                                <?php _e('Progress Bar', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="map" draggable="true">
                                <span class="dashicons dashicons-location"></span>
                                <?php _e('Google Map', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="form" draggable="true">
                                <span class="dashicons dashicons-email"></span>
                                <?php _e('Contact Form', 'clarkes-terraclean'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="element-category">
                        <h4><?php _e('WooCommerce', 'clarkes-terraclean'); ?></h4>
                        <div class="element-list">
                            <div class="builder-element" data-type="products" draggable="true">
                                <span class="dashicons dashicons-products"></span>
                                <?php _e('Products', 'clarkes-terraclean'); ?>
                            </div>
                            <div class="builder-element" data-type="cart" draggable="true">
                                <span class="dashicons dashicons-cart"></span>
                                <?php _e('Cart', 'clarkes-terraclean'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Center - Canvas -->
            <div class="builder-canvas-container" style="flex: 1; background: #e5e5e5; overflow-y: auto; padding: 20px;">
                <div class="builder-canvas" id="builder-canvas" style="background: white; min-height: 800px; padding: 20px; max-width: 1200px; margin: 0 auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div class="canvas-empty-state" style="text-align: center; padding: 100px 20px; color: #999;">
                        <p><?php _e('Drag elements from the left sidebar to start building your page', 'clarkes-terraclean'); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Right Sidebar - Settings -->
            <div class="builder-sidebar-right" style="width: 350px; background: white; border-left: 1px solid #ddd; overflow-y: auto; padding: 15px;">
                <h3 style="margin-top: 0;"><?php _e('Element Settings', 'clarkes-terraclean'); ?></h3>
                <div id="element-settings-panel">
                    <p class="description"><?php _e('Select an element to edit its settings', 'clarkes-terraclean'); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <script type="application/json" id="builder-data"><?php echo wp_json_encode($builder_data); ?></script>
    <?php
}
}

/**
 * Render Page Builder Content
 */
if (!function_exists('clarkes_render_page_builder_content')) {
function clarkes_render_page_builder_content($content) {
    global $post;
    
    if (!$post) {
        return $content;
    }
    
    $use_builder = get_post_meta($post->ID, '_clarkes_use_page_builder', true);
    if (!$use_builder) {
        return $content;
    }
    
    $builder_data = get_post_meta($post->ID, '_clarkes_page_builder_data', true);
    if (empty($builder_data) || !is_array($builder_data)) {
        return $content;
    }
    
    // Render builder content
    ob_start();
    clarkes_render_builder_elements($builder_data);
    $builder_content = ob_get_clean();
    
    return $builder_content ?: $content;
}
}
add_filter('the_content', 'clarkes_render_page_builder_content', 1);

/**
 * Render Builder Elements
 */
if (!function_exists('clarkes_render_builder_elements')) {
function clarkes_render_builder_elements($elements) {
    foreach ($elements as $element) {
        if (!isset($element['type'])) {
            continue;
        }
        
        $type = $element['type'];
        $settings = isset($element['settings']) ? $element['settings'] : array();
        $children = isset($element['children']) ? $element['children'] : array();
        
        echo '<div class="pb-element pb-' . esc_attr($type) . '" data-element-id="' . esc_attr($element['id']) . '">';
        
        switch ($type) {
            case 'row':
                echo '<div class="pb-row" style="display: flex; gap: ' . esc_attr($settings['gap'] ?? '20px') . ';">';
                clarkes_render_builder_elements($children);
                echo '</div>';
                break;
                
            case 'column':
                $width = isset($settings['width']) ? $settings['width'] : '100%';
                echo '<div class="pb-column" style="width: ' . esc_attr($width) . ';">';
                clarkes_render_builder_elements($children);
                echo '</div>';
                break;
                
            case 'text':
                $text = isset($settings['text']) ? $settings['text'] : '';
                $tag = isset($settings['tag']) ? $settings['tag'] : 'p';
                echo '<' . esc_attr($tag) . ' class="pb-text">' . wp_kses_post($text) . '</' . esc_attr($tag) . '>';
                break;
                
            case 'heading':
                $text = isset($settings['text']) ? $settings['text'] : '';
                $level = isset($settings['level']) ? $settings['level'] : 2;
                echo '<h' . esc_attr($level) . ' class="pb-heading">' . esc_html($text) . '</h' . esc_attr($level) . '>';
                break;
                
            case 'image':
                $image_id = isset($settings['image_id']) ? $settings['image_id'] : 0;
                $size = isset($settings['size']) ? $settings['size'] : 'large';
                if ($image_id) {
                    echo wp_get_attachment_image($image_id, $size, false, array('class' => 'pb-image'));
                }
                break;
                
            case 'button':
                $text = isset($settings['text']) ? $settings['text'] : 'Button';
                $url = isset($settings['url']) ? $settings['url'] : '#';
                $style = isset($settings['style']) ? $settings['style'] : 'primary';
                echo '<a href="' . esc_url($url) . '" class="pb-button pb-button-' . esc_attr($style) . '">' . esc_html($text) . '</a>';
                break;
                
            case 'spacer':
                $height = isset($settings['height']) ? $settings['height'] : '50px';
                echo '<div class="pb-spacer" style="height: ' . esc_attr($height) . ';"></div>';
                break;
                
            case 'divider':
                $divider_style = isset($settings['style']) ? $settings['style'] : 'solid';
                $divider_color = isset($settings['color']) ? $settings['color'] : '#ddd';
                echo '<hr class="pb-divider" style="border-style: ' . esc_attr($divider_style) . '; border-color: ' . esc_attr($divider_color) . ';" />';
                break;
                
            case 'video':
                $video_id = isset($settings['video_id']) ? $settings['video_id'] : 0;
                $video_url = isset($settings['video_url']) ? $settings['video_url'] : '';
                if ($video_id && $video_url) {
                    echo '<video class="pb-video" controls';
                    if (isset($settings['autoplay']) && $settings['autoplay']) echo ' autoplay';
                    if (isset($settings['loop']) && $settings['loop']) echo ' loop';
                    if (isset($settings['muted']) && $settings['muted']) echo ' muted';
                    echo '><source src="' . esc_url($video_url) . '" type="video/mp4"></video>';
                } else {
                    echo '<div class="pb-video-placeholder">Video Placeholder</div>';
                }
                break;
                
            case 'gallery':
                $images = isset($settings['images']) ? $settings['images'] : array();
                $columns = isset($settings['columns']) ? $settings['columns'] : 3;
                if (!empty($images)) {
                    echo '<div class="pb-gallery pb-gallery-' . esc_attr($columns) . '-cols" style="display: grid; grid-template-columns: repeat(' . esc_attr($columns) . ', 1fr); gap: 10px;">';
                    foreach ($images as $img) {
                        if (isset($img['url'])) {
                            echo '<img src="' . esc_url($img['url']) . '" class="pb-gallery-image" />';
                        }
                    }
                    echo '</div>';
                } else {
                    echo '<div class="pb-gallery-placeholder">Gallery Placeholder</div>';
                }
                break;
                
            case 'accordion':
                $items = isset($settings['items']) ? intval($settings['items']) : 3;
                echo '<div class="pb-accordion">';
                for ($i = 1; $i <= $items; $i++) {
                    echo '<div class="pb-accordion-item"><div class="pb-accordion-header">Accordion Item ' . $i . '</div><div class="pb-accordion-content">Content for item ' . $i . '</div></div>';
                }
                echo '</div>';
                break;
                
            case 'tabs':
                $tabs = isset($settings['tabs']) ? intval($settings['tabs']) : 3;
                echo '<div class="pb-tabs"><div class="pb-tabs-nav">';
                for ($i = 1; $i <= $tabs; $i++) {
                    echo '<button class="pb-tab-button">Tab ' . $i . '</button>';
                }
                echo '</div><div class="pb-tabs-content">Tab content</div></div>';
                break;
                
            case 'testimonial':
                $quote = isset($settings['quote']) ? $settings['quote'] : 'Testimonial text';
                $author = isset($settings['author']) ? $settings['author'] : 'Author';
                $title = isset($settings['title']) ? $settings['title'] : '';
                echo '<blockquote class="pb-testimonial"><p>' . esc_html($quote) . '</p><cite>' . esc_html($author);
                if ($title) echo ' - ' . esc_html($title);
                echo '</cite></blockquote>';
                break;
                
            case 'pricing':
                $price = isset($settings['price']) ? $settings['price'] : '£99';
                $currency = isset($settings['currency']) ? $settings['currency'] : '£';
                $period = isset($settings['period']) ? $settings['period'] : '/month';
                echo '<div class="pb-pricing-table"><div class="pb-price">' . esc_html($currency) . esc_html($price) . '<span class="pb-period">' . esc_html($period) . '</span></div><div class="pb-features"><ul><li>Feature 1</li><li>Feature 2</li><li>Feature 3</li></ul></div></div>';
                break;
                
            case 'countdown':
                $date = isset($settings['date']) ? $settings['date'] : '';
                echo '<div class="pb-countdown" data-date="' . esc_attr($date) . '">00:00:00</div>';
                break;
                
            case 'progress':
                $value = isset($settings['value']) ? intval($settings['value']) : 50;
                $color = isset($settings['color']) ? $settings['color'] : '#3b82f6';
                echo '<div class="pb-progress-bar"><div class="pb-progress-fill" style="width: ' . esc_attr($value) . '%; background-color: ' . esc_attr($color) . ';">' . esc_html($value) . '%</div></div>';
                break;
                
            case 'map':
                $address = isset($settings['address']) ? $settings['address'] : '';
                $zoom = isset($settings['zoom']) ? intval($settings['zoom']) : 15;
                echo '<div class="pb-map" data-address="' . esc_attr($address) . '" data-zoom="' . esc_attr($zoom) . '">Google Map - ' . esc_html($address) . '</div>';
                break;
                
            case 'form':
                $submit_text = isset($settings['submit_text']) ? $settings['submit_text'] : 'Submit';
                echo '<form class="pb-contact-form"><input type="text" placeholder="Name" required /><input type="email" placeholder="Email" required /><textarea placeholder="Message" required></textarea><button type="submit">' . esc_html($submit_text) . '</button></form>';
                break;
        }
        
        // Apply custom styles if set
        if (isset($settings['bg_color']) && $settings['bg_color']) {
            echo '<style>.pb-element[data-element-id="' . esc_attr($element['id']) . '"] { background-color: ' . esc_attr($settings['bg_color']) . '; }</style>';
        }
        if (isset($settings['text_color']) && $settings['text_color']) {
            echo '<style>.pb-element[data-element-id="' . esc_attr($element['id']) . '"] { color: ' . esc_attr($settings['text_color']) . '; }</style>';
        }
        if (isset($settings['padding']) && $settings['padding']) {
            echo '<style>.pb-element[data-element-id="' . esc_attr($element['id']) . '"] { padding: ' . esc_attr($settings['padding']) . '; }</style>';
        }
        if (isset($settings['margin']) && $settings['margin']) {
            echo '<style>.pb-element[data-element-id="' . esc_attr($element['id']) . '"] { margin: ' . esc_attr($settings['margin']) . '; }</style>';
        }
        
        echo '</div>';
    }
}
}

/**
 * AJAX: Save Builder Data
 */
if (!function_exists('clarkes_ajax_save_builder')) {
function clarkes_ajax_save_builder() {
    check_ajax_referer('clarkes_page_builder', 'nonce');
    
    if (!current_user_can('edit_pages')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
    $builder_data = isset($_POST['builder_data']) ? json_decode(stripslashes($_POST['builder_data']), true) : array();
    
    if (!$post_id) {
        wp_send_json_error(array('message' => 'Invalid post ID'));
        return;
    }
    
    update_post_meta($post_id, '_clarkes_page_builder_data', $builder_data);
    update_post_meta($post_id, '_clarkes_use_page_builder', 1);
    
    wp_send_json_success(array('message' => 'Page builder data saved successfully'));
}
}
add_action('wp_ajax_clarkes_save_builder', 'clarkes_ajax_save_builder');

