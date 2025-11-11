<?php
/**
 * Recent Work Gallery System
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Recent Work Custom Post Type
 */
if (!function_exists('clarkes_register_recent_work_post_type')) {
function clarkes_register_recent_work_post_type() {
    $labels = array(
        'name'                  => _x('Recent Work', 'Post type general name', 'clarkes-terraclean'),
        'singular_name'         => _x('Work Item', 'Post type singular name', 'clarkes-terraclean'),
        'menu_name'             => _x('Recent Work', 'Admin Menu text', 'clarkes-terraclean'),
        'name_admin_bar'        => _x('Work Item', 'Add New on Toolbar', 'clarkes-terraclean'),
        'add_new'               => __('Add New', 'clarkes-terraclean'),
        'add_new_item'          => __('Add New Work Item', 'clarkes-terraclean'),
        'new_item'              => __('New Work Item', 'clarkes-terraclean'),
        'edit_item'             => __('Edit Work Item', 'clarkes-terraclean'),
        'view_item'             => __('View Work Item', 'clarkes-terraclean'),
        'all_items'             => __('All Work Items', 'clarkes-terraclean'),
        'search_items'          => __('Search Work Items', 'clarkes-terraclean'),
        'not_found'             => __('No work items found.', 'clarkes-terraclean'),
        'not_found_in_trash'    => __('No work items found in Trash.', 'clarkes-terraclean'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-camera',
        'menu_position'      => 23,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
        'show_in_rest'       => true,
    );

    register_post_type('recent_work', $args);
}
}
add_action('init', 'clarkes_register_recent_work_post_type');

/**
 * Get Recent Work Items
 */
if (!function_exists('clarkes_get_recent_work')) {
function clarkes_get_recent_work($limit = -1) {
    $args = array(
        'post_type'      => 'recent_work',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    return new WP_Query($args);
}
}

