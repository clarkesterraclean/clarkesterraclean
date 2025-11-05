<?php
/**
 * Reviews System
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Review Custom Post Type
 */
function clarkes_register_review_post_type() {
    $labels = array(
        'name'                  => _x('Reviews', 'Post type general name', 'clarkes-terraclean'),
        'singular_name'         => _x('Review', 'Post type singular name', 'clarkes-terraclean'),
        'menu_name'             => _x('Reviews', 'Admin Menu text', 'clarkes-terraclean'),
        'name_admin_bar'        => _x('Review', 'Add New on Toolbar', 'clarkes-terraclean'),
        'add_new'               => __('Add New', 'clarkes-terraclean'),
        'add_new_item'          => __('Add New Review', 'clarkes-terraclean'),
        'new_item'              => __('New Review', 'clarkes-terraclean'),
        'edit_item'             => __('Edit Review', 'clarkes-terraclean'),
        'view_item'             => __('View Review', 'clarkes-terraclean'),
        'all_items'             => __('All Reviews', 'clarkes-terraclean'),
        'search_items'          => __('Search Reviews', 'clarkes-terraclean'),
        'not_found'             => __('No reviews found.', 'clarkes-terraclean'),
        'not_found_in_trash'    => __('No reviews found in Trash.', 'clarkes-terraclean'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-testimonial',
        'menu_position'      => 25,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('review', $args);
}
add_action('init', 'clarkes_register_review_post_type');

/**
 * Register Custom Post Statuses
 */
function clarkes_register_review_statuses() {
    register_post_status('review_read', array(
        'label'                     => _x('Read (not public)', 'post status', 'clarkes-terraclean'),
        'public'                    => false,
        'exclude_from_search'       => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Read (not public) <span class="count">(%s)</span>', 'Read (not public) <span class="count">(%s)</span>', 'clarkes-terraclean'),
    ));

    register_post_status('review_archived', array(
        'label'                     => _x('Archived', 'post status', 'clarkes-terraclean'),
        'public'                    => false,
        'exclude_from_search'       => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Archived <span class="count">(%s)</span>', 'Archived <span class="count">(%s)</span>', 'clarkes-terraclean'),
    ));
}
add_action('init', 'clarkes_register_review_statuses');

/**
 * Register Review Meta Fields
 */
function clarkes_register_review_meta() {
    register_post_meta('review', 'reviewer_name', array(
        'type'              => 'string',
        'description'       => 'Reviewer name',
        'single'            => true,
        'sanitize_callback' => 'sanitize_text_field',
        'show_in_rest'      => true,
    ));

    register_post_meta('review', 'reviewer_location', array(
        'type'              => 'string',
        'description'       => 'Reviewer location',
        'single'            => true,
        'sanitize_callback' => 'sanitize_text_field',
        'show_in_rest'      => true,
    ));

    register_post_meta('review', 'review_date', array(
        'type'              => 'string',
        'description'       => 'Review date',
        'single'            => true,
        'sanitize_callback' => 'sanitize_text_field',
        'show_in_rest'      => true,
    ));

    register_post_meta('review', 'review_rating', array(
        'type'              => 'integer',
        'description'       => 'Review rating (1-5)',
        'single'            => true,
        'sanitize_callback' => 'absint',
        'show_in_rest'      => true,
    ));
}
add_action('init', 'clarkes_register_review_meta');

/**
 * Add Review Details Metabox
 */
function clarkes_add_review_metabox() {
    add_meta_box(
        'review_details',
        __('Review Details', 'clarkes-terraclean'),
        'clarkes_review_metabox_callback',
        'review',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'clarkes_add_review_metabox');

/**
 * Review Metabox Callback
 */
function clarkes_review_metabox_callback($post) {
    wp_nonce_field('clarkes_review_meta', 'clarkes_review_meta_nonce');

    $reviewer_name = get_post_meta($post->ID, 'reviewer_name', true);
    $reviewer_location = get_post_meta($post->ID, 'reviewer_location', true);
    $review_date = get_post_meta($post->ID, 'review_date', true);
    $review_rating = get_post_meta($post->ID, 'review_rating', true);

    if (empty($review_date)) {
        $review_date = current_time('Y-m-d');
    }

    ?>
    <table class="form-table">
        <tr>
            <th><label for="reviewer_name"><?php _e('Reviewer Name', 'clarkes-terraclean'); ?></label></th>
            <td><input type="text" id="reviewer_name" name="reviewer_name" value="<?php echo esc_attr($reviewer_name); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="reviewer_location"><?php _e('Location', 'clarkes-terraclean'); ?></label></th>
            <td><input type="text" id="reviewer_location" name="reviewer_location" value="<?php echo esc_attr($reviewer_location); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="review_date"><?php _e('Review Date', 'clarkes-terraclean'); ?></label></th>
            <td><input type="date" id="review_date" name="review_date" value="<?php echo esc_attr($review_date); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="review_rating"><?php _e('Rating', 'clarkes-terraclean'); ?></label></th>
            <td>
                <select id="review_rating" name="review_rating">
                    <option value=""><?php _e('No rating', 'clarkes-terraclean'); ?></option>
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <option value="<?php echo $i; ?>" <?php selected($review_rating, $i); ?>><?php echo $i; ?> <?php _e('star(s)', 'clarkes-terraclean'); ?></option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save Review Meta
 */
function clarkes_save_review_meta($post_id) {
    if (!isset($_POST['clarkes_review_meta_nonce']) || !wp_verify_nonce($_POST['clarkes_review_meta_nonce'], 'clarkes_review_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (get_post_type($post_id) !== 'review') {
        return;
    }

    if (isset($_POST['reviewer_name'])) {
        update_post_meta($post_id, 'reviewer_name', sanitize_text_field($_POST['reviewer_name']));
    }

    if (isset($_POST['reviewer_location'])) {
        update_post_meta($post_id, 'reviewer_location', sanitize_text_field($_POST['reviewer_location']));
    }

    if (isset($_POST['review_date'])) {
        update_post_meta($post_id, 'review_date', sanitize_text_field($_POST['review_date']));
    }

    if (isset($_POST['review_rating'])) {
        $rating = absint($_POST['review_rating']);
        update_post_meta($post_id, 'review_rating', $rating > 0 && $rating <= 5 ? $rating : '');
    }
}
add_action('save_post', 'clarkes_save_review_meta');

/**
 * Add Quick Moderation Actions
 */
function clarkes_add_review_quick_actions($actions, $post) {
    if ($post->post_type === 'review') {
        $approve_url = wp_nonce_url(admin_url('admin.php?action=clarkes_approve_review&post=' . $post->ID), 'clarkes_approve_review_' . $post->ID);
        $read_url = wp_nonce_url(admin_url('admin.php?action=clarkes_read_review&post=' . $post->ID), 'clarkes_read_review_' . $post->ID);
        $archive_url = wp_nonce_url(admin_url('admin.php?action=clarkes_archive_review&post=' . $post->ID), 'clarkes_archive_review_' . $post->ID);

        $actions['clarkes_approve'] = '<a href="' . esc_url($approve_url) . '">' . __('Approve', 'clarkes-terraclean') . '</a>';
        $actions['clarkes_read'] = '<a href="' . esc_url($read_url) . '">' . __('Mark as Read', 'clarkes-terraclean') . '</a>';
        $actions['clarkes_archive'] = '<a href="' . esc_url($archive_url) . '">' . __('Archive', 'clarkes-terraclean') . '</a>';
    }
    return $actions;
}
add_filter('post_row_actions', 'clarkes_add_review_quick_actions', 10, 2);

/**
 * Handle Quick Actions
 */
function clarkes_handle_review_quick_actions() {
    if (!isset($_GET['action']) || !isset($_GET['post'])) {
        return;
    }

    $action = $_GET['action'];
    $post_id = absint($_GET['post']);

    if (get_post_type($post_id) !== 'review') {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    switch ($action) {
        case 'clarkes_approve_review':
            if (wp_verify_nonce($_GET['_wpnonce'], 'clarkes_approve_review_' . $post_id)) {
                wp_update_post(array('ID' => $post_id, 'post_status' => 'publish'));
            }
            break;

        case 'clarkes_read_review':
            if (wp_verify_nonce($_GET['_wpnonce'], 'clarkes_read_review_' . $post_id)) {
                wp_update_post(array('ID' => $post_id, 'post_status' => 'review_read'));
            }
            break;

        case 'clarkes_archive_review':
            if (wp_verify_nonce($_GET['_wpnonce'], 'clarkes_archive_review_' . $post_id)) {
                wp_update_post(array('ID' => $post_id, 'post_status' => 'review_archived'));
            }
            break;
    }

    wp_redirect(admin_url('edit.php?post_type=review'));
    exit;
}
add_action('admin_action_clarkes_approve_review', 'clarkes_handle_review_quick_actions');
add_action('admin_action_clarkes_read_review', 'clarkes_handle_review_quick_actions');
add_action('admin_action_clarkes_archive_review', 'clarkes_handle_review_quick_actions');

/**
 * Add Bulk Actions
 */
function clarkes_add_review_bulk_actions($bulk_actions) {
    $bulk_actions['clarkes_mark_read'] = __('Mark as Read', 'clarkes-terraclean');
    $bulk_actions['clarkes_archive'] = __('Archive', 'clarkes-terraclean');
    return $bulk_actions;
}
add_filter('bulk_actions-edit-review', 'clarkes_add_review_bulk_actions');

/**
 * Handle Bulk Actions
 */
function clarkes_handle_review_bulk_actions($redirect_to, $action, $post_ids) {
    if ($action !== 'clarkes_mark_read' && $action !== 'clarkes_archive') {
        return $redirect_to;
    }

    foreach ($post_ids as $post_id) {
        if (get_post_type($post_id) === 'review' && current_user_can('edit_post', $post_id)) {
            if ($action === 'clarkes_mark_read') {
                wp_update_post(array('ID' => $post_id, 'post_status' => 'review_read'));
            } elseif ($action === 'clarkes_archive') {
                wp_update_post(array('ID' => $post_id, 'post_status' => 'review_archived'));
            }
        }
    }

    $redirect_to = add_query_arg('bulk_reviews_updated', count($post_ids), $redirect_to);
    return $redirect_to;
}
add_filter('handle_bulk_actions-edit-review', 'clarkes_handle_review_bulk_actions', 10, 3);

/**
 * Add Admin List Columns
 */
function clarkes_add_review_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['reviewer_name'] = __('Name', 'clarkes-terraclean');
    $new_columns['reviewer_location'] = __('Location', 'clarkes-terraclean');
    $new_columns['review_rating'] = __('Rating', 'clarkes-terraclean');
    $new_columns['review_status'] = __('Status', 'clarkes-terraclean');
    $new_columns['date'] = $columns['date'];
    return $new_columns;
}
add_filter('manage_review_posts_columns', 'clarkes_add_review_columns');

/**
 * Populate Admin List Columns
 */
function clarkes_populate_review_columns($column, $post_id) {
    switch ($column) {
        case 'reviewer_name':
            $name = get_post_meta($post_id, 'reviewer_name', true);
            echo esc_html($name ?: '—');
            break;

        case 'reviewer_location':
            $location = get_post_meta($post_id, 'reviewer_location', true);
            echo esc_html($location ?: '—');
            break;

        case 'review_rating':
            $rating = absint(get_post_meta($post_id, 'review_rating', true));
            if ($rating > 0 && $rating <= 5) {
                echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
            } else {
                echo '—';
            }
            break;

        case 'review_status':
            $status = get_post_status($post_id);
            $status_obj = get_post_status_object($status);
            echo esc_html($status_obj ? $status_obj->label : $status);
            break;
    }
}
add_action('manage_review_posts_custom_column', 'clarkes_populate_review_columns', 10, 2);

/**
 * Make Review Columns Sortable
 */
function clarkes_make_review_columns_sortable($columns) {
    $columns['reviewer_name'] = 'reviewer_name';
    $columns['review_date'] = 'review_date';
    return $columns;
}
add_filter('manage_edit-review_sortable_columns', 'clarkes_make_review_columns_sortable');

/**
 * Review Form Shortcode
 */
function clarkes_review_form_shortcode($atts) {
    $atts = shortcode_atts(array(), $atts, 'clarkes_review_form');

    // Enqueue script
    wp_enqueue_script('clarkes-reviews', get_template_directory_uri() . '/inc/reviews.js', array('jquery'), wp_get_theme()->get('Version'), true);
    wp_localize_script('clarkes-reviews', 'CLARKES_REVIEWS', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('clarkes_review_nonce'),
    ));

    ob_start();
    ?>
    <div class="clarkes-review-form-container">
        <form id="clarkes-review-form" class="bg-carbon-dark border border-eco-green/30 rounded-lg p-8 space-y-6" aria-live="polite">
            <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('clarkes_review_nonce')); ?>">
            <input type="text" name="company" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

            <div>
                <label for="reviewer_name" class="block text-text-body font-medium mb-2">Your Name *</label>
                <input type="text" id="reviewer_name" name="reviewer_name" required class="w-full px-4 py-2 bg-carbon-dark border border-eco-green/30 rounded text-text-body focus:outline-none focus:border-eco-green" placeholder="John Smith">
                <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
            </div>

            <div>
                <label for="reviewer_location" class="block text-text-body font-medium mb-2">Location (Optional)</label>
                <input type="text" id="reviewer_location" name="reviewer_location" class="w-full px-4 py-2 bg-carbon-dark border border-eco-green/30 rounded text-text-body focus:outline-none focus:border-eco-green" placeholder="Kent, UK">
                <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
            </div>

            <div>
                <label for="review_rating" class="block text-text-body font-medium mb-2">Rating (Optional)</label>
                <select id="review_rating" name="review_rating" class="w-full px-4 py-2 bg-carbon-dark border border-eco-green/30 rounded text-text-body focus:outline-none focus:border-eco-green">
                    <option value="">No rating</option>
                    <option value="5">5 stars</option>
                    <option value="4">4 stars</option>
                    <option value="3">3 stars</option>
                    <option value="2">2 stars</option>
                    <option value="1">1 star</option>
                </select>
                <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
            </div>

            <div>
                <label for="review_content" class="block text-text-body font-medium mb-2">Your Review *</label>
                <textarea id="review_content" name="review_content" required rows="6" class="w-full px-4 py-2 bg-carbon-dark border border-eco-green/30 rounded text-text-body focus:outline-none focus:border-eco-green" placeholder="Tell us about your experience..."></textarea>
                <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
            </div>

            <button type="submit" class="border border-eco-green text-eco-green rounded-full px-8 py-3 text-base font-semibold hover:bg-eco-green hover:text-carbon-dark transition disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="submit-text">Submit Review</span>
            </button>
            <p class="error-message text-red-600 text-sm mt-2 hidden"></p>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('clarkes_review_form', 'clarkes_review_form_shortcode');

/**
 * AJAX Handler: Submit Review
 */
function clarkes_handle_submit_review() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'clarkes_review_nonce')) {
        wp_send_json_error(array('errors' => array('form' => 'Security check failed. Please refresh and try again.')));
    }

    // Honeypot check
    if (!empty($_POST['company'])) {
        wp_send_json_error(array('errors' => array('form' => 'Spam detected.')));
    }

    $errors = array();

    // Sanitize and validate
    $reviewer_name = isset($_POST['reviewer_name']) ? sanitize_text_field($_POST['reviewer_name']) : '';
    $reviewer_location = isset($_POST['reviewer_location']) ? sanitize_text_field($_POST['reviewer_location']) : '';
    $review_rating = isset($_POST['review_rating']) ? absint($_POST['review_rating']) : 0;
    $review_content = isset($_POST['review_content']) ? wp_kses_post($_POST['review_content']) : '';

    // Validation
    if (empty($reviewer_name)) {
        $errors['reviewer_name'] = 'Name is required.';
    }

    if (empty($review_content)) {
        $errors['review_content'] = 'Review content is required.';
    }

    if ($review_rating > 0 && ($review_rating < 1 || $review_rating > 5)) {
        $review_rating = 0;
    }

    if (!empty($errors)) {
        wp_send_json_error(array('errors' => $errors));
    }

    // Create review post
    $post_data = array(
        'post_type'    => 'review',
        'post_status'  => 'pending',
        'post_title'   => $reviewer_name ?: 'Customer Review',
        'post_content' => $review_content,
        'post_author'  => 1,
    );

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        wp_send_json_error(array('errors' => array('form' => 'Unable to submit review. Please try again.')));
    }

    // Save meta
    update_post_meta($post_id, 'reviewer_name', $reviewer_name);
    if (!empty($reviewer_location)) {
        update_post_meta($post_id, 'reviewer_location', $reviewer_location);
    }
    if ($review_rating > 0) {
        update_post_meta($post_id, 'review_rating', $review_rating);
    }
    update_post_meta($post_id, 'review_date', current_time('Y-m-d'));

    // Send email notification
    clarkes_send_review_notification($post_id, $reviewer_name, $reviewer_location, $review_rating, $review_content);

    wp_send_json_success(array('message' => 'Thanks! Your review has been submitted for approval.'));
}
add_action('wp_ajax_clarkes_submit_review', 'clarkes_handle_submit_review');
add_action('wp_ajax_nopriv_clarkes_submit_review', 'clarkes_handle_submit_review');

/**
 * Send Review Notification Email
 */
function clarkes_send_review_notification($post_id, $reviewer_name, $reviewer_location, $review_rating, $review_content) {
    // Get recipient
    $customizer_email = get_theme_mod('contact_email', '');
    if (!empty($customizer_email) && is_email($customizer_email)) {
        $to = $customizer_email;
    } else {
        $to = get_option('admin_email');
    }

    if (empty($to) || !is_email($to)) {
        $to = get_option('admin_email');
    }

    $subject = 'New customer review from ' . $reviewer_name;

    $body = "New Customer Review\n\n";
    $body .= "Name: " . $reviewer_name . "\n";
    if (!empty($reviewer_location)) {
        $body .= "Location: " . $reviewer_location . "\n";
    }
    if ($review_rating > 0) {
        $body .= "Rating: " . str_repeat('★', $review_rating) . "\n";
    }
    $body .= "\nReview:\n";
    $body .= wp_trim_words($review_content, 200) . "\n\n";
    $body .= "Edit review: " . admin_url('post.php?action=edit&post=' . $post_id) . "\n";
    $body .= "All reviews: " . admin_url('edit.php?post_type=review') . "\n";

    wp_mail($to, $subject, $body);
}

/**
 * Reviews Display Shortcode
 */
function clarkes_reviews_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit'  => '6',
        'layout' => 'grid',
        'cols'   => '3',
    ), $atts, 'clarkes_reviews');

    $limit = absint($atts['limit']);
    $layout = sanitize_text_field($atts['layout']);
    $cols = absint($atts['cols']);

    $args = array(
        'post_type'      => 'review',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'meta_key'       => 'review_date',
        'orderby'        => 'meta_value',
        'order'          => 'DESC',
    );

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p class="text-text-body">No reviews yet.</p>';
    }

    $grid_class = $layout === 'grid' ? 'grid grid-cols-1 md:grid-cols-' . min($cols, 4) . ' gap-6' : 'space-y-6';

    ob_start();
    ?>
    <div class="clarkes-reviews-container <?php echo esc_attr($grid_class); ?>">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <?php
            $reviewer_name = get_post_meta(get_the_ID(), 'reviewer_name', true);
            $reviewer_location = get_post_meta(get_the_ID(), 'reviewer_location', true);
            $review_rating = absint(get_post_meta(get_the_ID(), 'review_rating', true));
            $review_date = get_post_meta(get_the_ID(), 'review_date', true);
            $display_date = $review_date ? date_i18n(get_option('date_format'), strtotime($review_date)) : get_the_date();
            ?>
            <div class="bg-carbon-dark border-l-4 border-eco-green p-6 rounded-lg">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="font-semibold text-white mb-1"><?php echo esc_html($reviewer_name ?: 'Customer'); ?></h3>
                        <?php if (!empty($reviewer_location)) : ?>
                            <p class="text-text-body text-sm"><?php echo esc_html($reviewer_location); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if ($review_rating > 0) : ?>
                        <div class="text-eco-green text-lg" aria-label="<?php echo esc_attr($review_rating); ?> star rating">
                            <?php echo str_repeat('★', $review_rating) . str_repeat('☆', 5 - $review_rating); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <p class="text-text-body text-sm mb-3"><?php echo esc_html($display_date); ?></p>
                <div class="text-text-body prose prose-sm max-w-none">
                    <?php the_content(); ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('clarkes_reviews', 'clarkes_reviews_shortcode');

/**
 * Reviews Slider Shortcode
 */
function clarkes_reviews_slider_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => '8',
    ), $atts, 'clarkes_reviews_slider');

    $limit = absint($atts['limit']);

    $args = array(
        'post_type'      => 'review',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'meta_key'       => 'review_date',
        'orderby'        => 'meta_value',
        'order'          => 'DESC',
    );

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p class="text-text-body">No reviews yet.</p>';
    }

    ob_start();
    ?>
    <div class="clarkes-reviews-slider overflow-x-auto">
        <div class="flex gap-6 pb-4" style="scroll-snap-type: x mandatory;">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php
                $reviewer_name = get_post_meta(get_the_ID(), 'reviewer_name', true);
                $reviewer_location = get_post_meta(get_the_ID(), 'reviewer_location', true);
                $review_rating = absint(get_post_meta(get_the_ID(), 'review_rating', true));
                $review_date = get_post_meta(get_the_ID(), 'review_date', true);
                $display_date = $review_date ? date_i18n(get_option('date_format'), strtotime($review_date)) : get_the_date();
                ?>
                <div class="bg-carbon-dark border-l-4 border-eco-green p-6 rounded-lg min-w-[300px] flex-shrink-0" style="scroll-snap-align: start;">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="font-semibold text-white mb-1"><?php echo esc_html($reviewer_name ?: 'Customer'); ?></h3>
                            <?php if (!empty($reviewer_location)) : ?>
                                <p class="text-text-body text-sm"><?php echo esc_html($reviewer_location); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if ($review_rating > 0) : ?>
                            <div class="text-eco-green text-lg" aria-label="<?php echo esc_attr($review_rating); ?> star rating">
                                <?php echo str_repeat('★', $review_rating) . str_repeat('☆', 5 - $review_rating); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <p class="text-text-body text-sm mb-3"><?php echo esc_html($display_date); ?></p>
                    <div class="text-text-body prose prose-sm max-w-none">
                        <?php the_content(); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('clarkes_reviews_slider', 'clarkes_reviews_slider_shortcode');

/**
 * Create Leave a Review Page
 */
function clarkes_create_review_page() {
    $page = get_page_by_path('leave-a-review');
    if (!$page) {
        $page_data = array(
            'post_title'   => 'Leave a Review',
            'post_name'    => 'leave-a-review',
            'post_content' => '[clarkes_review_form]',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        );
        wp_insert_post($page_data);
    }
}
add_action('after_switch_theme', 'clarkes_create_review_page');

/**
 * Add Quick Moderation Buttons to Submit Box
 */
function clarkes_add_review_submit_meta_box($post) {
    if ($post->post_type !== 'review') {
        return;
    }

    $approve_url = wp_nonce_url(admin_url('admin.php?action=clarkes_approve_review&post=' . $post->ID), 'clarkes_approve_review_' . $post->ID);
    $read_url = wp_nonce_url(admin_url('admin.php?action=clarkes_read_review&post=' . $post->ID), 'clarkes_read_review_' . $post->ID);
    $archive_url = wp_nonce_url(admin_url('admin.php?action=clarkes_archive_review&post=' . $post->ID), 'clarkes_archive_review_' . $post->ID);

    ?>
    <div class="misc-pub-section">
        <strong><?php _e('Quick Actions:', 'clarkes-terraclean'); ?></strong><br>
        <a href="<?php echo esc_url($approve_url); ?>" class="button button-small"><?php _e('Approve', 'clarkes-terraclean'); ?></a>
        <a href="<?php echo esc_url($read_url); ?>" class="button button-small"><?php _e('Mark as Read', 'clarkes-terraclean'); ?></a>
        <a href="<?php echo esc_url($archive_url); ?>" class="button button-small"><?php _e('Archive', 'clarkes-terraclean'); ?></a>
    </div>
    <?php
}
add_action('post_submitbox_misc_actions', 'clarkes_add_review_submit_meta_box');

