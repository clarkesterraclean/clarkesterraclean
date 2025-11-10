<?php
/**
 * Case Studies System
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Case Study Custom Post Type
 */
if (!function_exists('clarkes_register_case_study_post_type')) {
function clarkes_register_case_study_post_type() {
    $labels = array(
        'name'                  => _x('Case Studies', 'Post type general name', 'clarkes-terraclean'),
        'singular_name'         => _x('Case Study', 'Post type singular name', 'clarkes-terraclean'),
        'menu_name'             => _x('Case Studies', 'Admin Menu text', 'clarkes-terraclean'),
        'name_admin_bar'        => _x('Case Study', 'Add New on Toolbar', 'clarkes-terraclean'),
        'add_new'               => __('Add New', 'clarkes-terraclean'),
        'add_new_item'          => __('Add New Case Study', 'clarkes-terraclean'),
        'new_item'              => __('New Case Study', 'clarkes-terraclean'),
        'edit_item'             => __('Edit Case Study', 'clarkes-terraclean'),
        'view_item'             => __('View Case Study', 'clarkes-terraclean'),
        'all_items'             => __('All Case Studies', 'clarkes-terraclean'),
        'search_items'          => __('Search Case Studies', 'clarkes-terraclean'),
        'not_found'             => __('No case studies found.', 'clarkes-terraclean'),
        'not_found_in_trash'    => __('No case studies found in Trash.', 'clarkes-terraclean'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-portfolio',
        'menu_position'      => 24,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
        'show_in_rest'       => true,
    );

    register_post_type('case_study', $args);
}
}
add_action('init', 'clarkes_register_case_study_post_type');

/**
 * Register Case Study Meta Fields
 */
if (!function_exists('clarkes_register_case_study_meta')) {
function clarkes_register_case_study_meta() {
    register_post_meta('case_study', 'vehicle_make_model', array(
        'type'              => 'string',
        'description'       => 'Vehicle make and model',
        'single'            => true,
        'sanitize_callback' => 'sanitize_text_field',
        'show_in_rest'      => true,
    ));

    register_post_meta('case_study', 'problem', array(
        'type'              => 'string',
        'description'       => 'Problem description',
        'single'            => true,
        'sanitize_callback' => 'sanitize_textarea_field',
        'show_in_rest'      => true,
    ));

    register_post_meta('case_study', 'dealer_said', array(
        'type'              => 'string',
        'description'       => 'What the dealer said',
        'single'            => true,
        'sanitize_callback' => 'sanitize_textarea_field',
        'show_in_rest'      => true,
    ));

    register_post_meta('case_study', 'what_we_did', array(
        'type'              => 'string',
        'description'       => 'What we did to fix it',
        'single'            => true,
        'sanitize_callback' => 'sanitize_textarea_field',
        'show_in_rest'      => true,
    ));

    register_post_meta('case_study', 'result', array(
        'type'              => 'string',
        'description'       => 'Result/outcome',
        'single'            => true,
        'sanitize_callback' => 'sanitize_textarea_field',
        'show_in_rest'      => true,
    ));

    register_post_meta('case_study', 'display_order', array(
        'type'              => 'integer',
        'description'       => 'Display order (lower numbers appear first)',
        'single'            => true,
        'sanitize_callback' => 'absint',
        'show_in_rest'      => true,
        'default'           => 0,
    ));
}
}
add_action('init', 'clarkes_register_case_study_meta');

/**
 * Add Case Study Meta Boxes
 */
if (!function_exists('clarkes_add_case_study_meta_boxes')) {
function clarkes_add_case_study_meta_boxes() {
    add_meta_box(
        'case_study_details',
        __('Case Study Details', 'clarkes-terraclean'),
        'clarkes_case_study_details_callback',
        'case_study',
        'normal',
        'high'
    );
}
}
add_action('add_meta_boxes', 'clarkes_add_case_study_meta_boxes');

/**
 * Case Study Details Meta Box Callback
 */
if (!function_exists('clarkes_case_study_details_callback')) {
function clarkes_case_study_details_callback($post) {
    wp_nonce_field('clarkes_case_study_meta', 'clarkes_case_study_meta_nonce');
    
    $vehicle = get_post_meta($post->ID, 'vehicle_make_model', true);
    $problem = get_post_meta($post->ID, 'problem', true);
    $dealer_said = get_post_meta($post->ID, 'dealer_said', true);
    $what_we_did = get_post_meta($post->ID, 'what_we_did', true);
    $result = get_post_meta($post->ID, 'result', true);
    $display_order = get_post_meta($post->ID, 'display_order', true);
    if ($display_order === '') $display_order = 0;
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="vehicle_make_model"><?php _e('Vehicle Make/Model', 'clarkes-terraclean'); ?></label></th>
            <td><input type="text" id="vehicle_make_model" name="vehicle_make_model" value="<?php echo esc_attr($vehicle); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="problem"><?php _e('Problem', 'clarkes-terraclean'); ?></label></th>
            <td><textarea id="problem" name="problem" rows="3" class="large-text"><?php echo esc_textarea($problem); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="dealer_said"><?php _e('Dealer Said (Optional)', 'clarkes-terraclean'); ?></label></th>
            <td><textarea id="dealer_said" name="dealer_said" rows="2" class="large-text"><?php echo esc_textarea($dealer_said); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="what_we_did"><?php _e('What We Did', 'clarkes-terraclean'); ?></label></th>
            <td><textarea id="what_we_did" name="what_we_did" rows="3" class="large-text"><?php echo esc_textarea($what_we_did); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="result"><?php _e('Result', 'clarkes-terraclean'); ?></label></th>
            <td><textarea id="result" name="result" rows="3" class="large-text"><?php echo esc_textarea($result); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="display_order"><?php _e('Display Order', 'clarkes-terraclean'); ?></label></th>
            <td><input type="number" id="display_order" name="display_order" value="<?php echo esc_attr($display_order); ?>" class="small-text" />
            <p class="description"><?php _e('Lower numbers appear first. Leave at 0 for default order.', 'clarkes-terraclean'); ?></p></td>
        </tr>
    </table>
    <p class="description">
        <strong><?php _e('Note:', 'clarkes-terraclean'); ?></strong> <?php _e('Use the Featured Image option to add an image for this case study. The image will automatically appear on the homepage and case studies page.', 'clarkes-terraclean'); ?>
    </p>
    <?php
}
}

/**
 * Save Case Study Meta
 */
if (!function_exists('clarkes_save_case_study_meta')) {
function clarkes_save_case_study_meta($post_id) {
    if (!isset($_POST['clarkes_case_study_meta_nonce']) || !wp_verify_nonce($_POST['clarkes_case_study_meta_nonce'], 'clarkes_case_study_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (get_post_type($post_id) !== 'case_study') {
        return;
    }

    $fields = array('vehicle_make_model', 'problem', 'dealer_said', 'what_we_did', 'result', 'display_order');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            if ($field === 'display_order') {
                update_post_meta($post_id, $field, absint($_POST[$field]));
            } else {
                update_post_meta($post_id, $field, sanitize_textarea_field($_POST[$field]));
            }
        }
    }
}
}
add_action('save_post', 'clarkes_save_case_study_meta');

/**
 * Get Case Studies for Display
 */
if (!function_exists('clarkes_get_case_studies')) {
function clarkes_get_case_studies($limit = -1) {
    $args = array(
        'post_type'      => 'case_study',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'meta_key'       => 'display_order',
        'orderby'        => array('meta_value_num' => 'ASC', 'date' => 'DESC'),
        'order'          => 'ASC',
    );

    return new WP_Query($args);
}
}

