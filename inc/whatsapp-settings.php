<?php
/**
 * WhatsApp & Live Chat Settings Module
 * Comprehensive settings page for WhatsApp and Live Chat features
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add WhatsApp Settings Menu
 */
if (!function_exists('clarkes_add_whatsapp_settings_menu')) {
function clarkes_add_whatsapp_settings_menu() {
    add_menu_page(
        __('WhatsApp & Live Chat', 'clarkes-terraclean'),
        __('WhatsApp Chat', 'clarkes-terraclean'),
        'manage_options',
        'clarkes-whatsapp-settings',
        'clarkes_whatsapp_settings_page',
        'dashicons-format-chat',
        32
    );
    
    add_submenu_page(
        'clarkes-whatsapp-settings',
        __('Settings', 'clarkes-terraclean'),
        __('Settings', 'clarkes-terraclean'),
        'manage_options',
        'clarkes-whatsapp-settings',
        'clarkes_whatsapp_settings_page'
    );
    
    add_submenu_page(
        'clarkes-whatsapp-settings',
        __('Messages', 'clarkes-terraclean'),
        __('Messages', 'clarkes-terraclean'),
        'manage_options',
        'clarkes-whatsapp-messages',
        'clarkes_whatsapp_messages_page'
    );
    
    add_submenu_page(
        'clarkes-whatsapp-settings',
        __('Customization', 'clarkes-terraclean'),
        __('Customization', 'clarkes-terraclean'),
        'manage_options',
        'clarkes-whatsapp-customize',
        'clarkes_whatsapp_customize_page'
    );
}
}
add_action('admin_menu', 'clarkes_add_whatsapp_settings_menu');

/**
 * Enqueue WhatsApp Settings Scripts
 */
if (!function_exists('clarkes_whatsapp_settings_scripts')) {
function clarkes_whatsapp_settings_scripts($hook) {
    if (strpos($hook, 'clarkes-whatsapp') === false) {
        return;
    }
    
    wp_enqueue_script('jquery');
    wp_enqueue_media();
    
    wp_enqueue_script(
        'clarkes-whatsapp-settings',
        get_template_directory_uri() . '/inc/whatsapp-settings.js',
        array('jquery'),
        filemtime(get_template_directory() . '/inc/whatsapp-settings.js'),
        true
    );
    
    wp_enqueue_style(
        'clarkes-whatsapp-settings-style',
        get_template_directory_uri() . '/inc/whatsapp-settings.css',
        array(),
        filemtime(get_template_directory() . '/inc/whatsapp-settings.css')
    );
    
    wp_localize_script('clarkes-whatsapp-settings', 'clarkesWhatsAppSettings', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('clarkes_whatsapp_settings'),
    ));
}
}
add_action('admin_enqueue_scripts', 'clarkes_whatsapp_settings_scripts');

/**
 * WhatsApp Settings Page
 */
if (!function_exists('clarkes_whatsapp_settings_page')) {
function clarkes_whatsapp_settings_page() {
    if (isset($_POST['clarkes_whatsapp_settings_save'])) {
        check_admin_referer('clarkes_whatsapp_settings');
        
        // Save all settings
        $settings = array(
            'enable_whatsapp_fab',
            'whatsapp_number',
            'whatsapp_pretext',
            'whatsapp_position',
            'whatsapp_offset_x',
            'whatsapp_offset_y',
            'whatsapp_show_desktop',
            'whatsapp_show_mobile',
            'whatsapp_show_scope',
            'whatsapp_button_size',
            'whatsapp_button_color',
            'whatsapp_button_icon',
            'whatsapp_chat_title',
            'whatsapp_chat_subtitle',
            'whatsapp_auto_open',
            'whatsapp_auto_open_delay',
            'whatsapp_business_hours',
            'whatsapp_offline_message',
            'whatsapp_email_notifications',
            'whatsapp_notification_email',
        );
        
        foreach ($settings as $setting) {
            if (isset($_POST[$setting])) {
                if ($setting === 'whatsapp_pretext') {
                    $value = sanitize_textarea_field($_POST[$setting]);
                    update_option($setting, $value);
                    set_theme_mod($setting, $value); // Sync to theme mod
                } elseif (in_array($setting, array('whatsapp_show_desktop', 'whatsapp_show_mobile', 'whatsapp_auto_open', 'whatsapp_email_notifications', 'enable_whatsapp_fab'))) {
                    $value = isset($_POST[$setting]) ? 1 : 0;
                    update_option($setting, $value);
                    set_theme_mod($setting, $value); // Sync to theme mod
                } else {
                    $value = sanitize_text_field($_POST[$setting]);
                    update_option($setting, $value);
                    set_theme_mod($setting, $value); // Sync to theme mod
                }
            } elseif (in_array($setting, array('whatsapp_show_desktop', 'whatsapp_show_mobile', 'whatsapp_auto_open', 'whatsapp_email_notifications', 'enable_whatsapp_fab'))) {
                // Unchecked checkboxes
                update_option($setting, 0);
                set_theme_mod($setting, 0);
            }
        }
        
        echo '<div class="notice notice-success"><p>Settings saved successfully!</p></div>';
    }
    
    $enable_fab = get_option('enable_whatsapp_fab', get_theme_mod('enable_whatsapp_fab', 1));
    $whatsapp_number = get_option('whatsapp_number', get_theme_mod('whatsapp_number', '07706 230867'));
    $whatsapp_pretext = get_option('whatsapp_pretext', get_theme_mod('whatsapp_pretext', 'Hi, I\'m interested in a DPF/engine service. Vehicle: [make/model], Location: [area].'));
    $whatsapp_position = get_option('whatsapp_position', get_theme_mod('whatsapp_position', 'bottom-right'));
    $whatsapp_offset_x = get_option('whatsapp_offset_x', get_theme_mod('whatsapp_offset_x', 20));
    $whatsapp_offset_y = get_option('whatsapp_offset_y', get_theme_mod('whatsapp_offset_y', 20));
    $whatsapp_show_desktop = get_option('whatsapp_show_desktop', get_theme_mod('whatsapp_show_desktop', 1));
    $whatsapp_show_mobile = get_option('whatsapp_show_mobile', get_theme_mod('whatsapp_show_mobile', 1));
    $whatsapp_show_scope = get_option('whatsapp_show_scope', get_theme_mod('whatsapp_show_scope', 'all'));
    $whatsapp_button_size = get_option('whatsapp_button_size', 'medium');
    $whatsapp_button_color = get_option('whatsapp_button_color', '#25D366');
    $whatsapp_chat_title = get_option('whatsapp_chat_title', 'Mark Clarke');
    $whatsapp_chat_subtitle = get_option('whatsapp_chat_subtitle', 'Usually replies within minutes');
    $whatsapp_auto_open = get_option('whatsapp_auto_open', 0);
    $whatsapp_auto_open_delay = get_option('whatsapp_auto_open_delay', 5);
    $whatsapp_business_hours = get_option('whatsapp_business_hours', '');
    $whatsapp_offline_message = get_option('whatsapp_offline_message', 'We\'re currently offline. Leave a message and we\'ll get back to you soon!');
    $whatsapp_email_notifications = get_option('whatsapp_email_notifications', 1);
    $whatsapp_notification_email = get_option('whatsapp_notification_email', get_option('admin_email'));
    ?>
    <div class="wrap clarkes-whatsapp-settings">
        <h1><?php _e('WhatsApp & Live Chat Settings', 'clarkes-terraclean'); ?></h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('clarkes_whatsapp_settings'); ?>
            
            <div class="whatsapp-settings-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px;">
                
                <!-- Left Column -->
                <div class="settings-column">
                    
                    <!-- Basic Settings -->
                    <div class="settings-section" style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                        <h2 style="margin-top: 0; padding-bottom: 15px; border-bottom: 2px solid #e5e7eb;"><?php _e('Basic Settings', 'clarkes-terraclean'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="enable_whatsapp_fab"><?php _e('Enable Floating Button', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <input type="checkbox" id="enable_whatsapp_fab" name="enable_whatsapp_fab" value="1" <?php checked($enable_fab, 1); ?> />
                                    <p class="description"><?php _e('Show the WhatsApp floating button on your site', 'clarkes-terraclean'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_number"><?php _e('WhatsApp Number', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="whatsapp_number" name="whatsapp_number" value="<?php echo esc_attr($whatsapp_number); ?>" class="regular-text" />
                                    <p class="description"><?php _e('Your WhatsApp business number (e.g., 07706 230867)', 'clarkes-terraclean'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_pretext"><?php _e('Pre-filled Message', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <textarea id="whatsapp_pretext" name="whatsapp_pretext" rows="3" class="large-text"><?php echo esc_textarea($whatsapp_pretext); ?></textarea>
                                    <p class="description"><?php _e('Message that will be pre-filled when customers open WhatsApp', 'clarkes-terraclean'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Display Settings -->
                    <div class="settings-section" style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                        <h2 style="margin-top: 0; padding-bottom: 15px; border-bottom: 2px solid #e5e7eb;"><?php _e('Display Settings', 'clarkes-terraclean'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_show_scope"><?php _e('Show On', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <select id="whatsapp_show_scope" name="whatsapp_show_scope" class="regular-text">
                                        <option value="all" <?php selected($whatsapp_show_scope, 'all'); ?>><?php _e('All Pages', 'clarkes-terraclean'); ?></option>
                                        <option value="front_only" <?php selected($whatsapp_show_scope, 'front_only'); ?>><?php _e('Front Page Only', 'clarkes-terraclean'); ?></option>
                                        <option value="pages_only" <?php selected($whatsapp_show_scope, 'pages_only'); ?>><?php _e('Static Pages Only', 'clarkes-terraclean'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label><?php _e('Device Visibility', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="whatsapp_show_desktop" value="1" <?php checked($whatsapp_show_desktop, 1); ?> />
                                        <?php _e('Show on Desktop', 'clarkes-terraclean'); ?>
                                    </label><br>
                                    <label>
                                        <input type="checkbox" name="whatsapp_show_mobile" value="1" <?php checked($whatsapp_show_mobile, 1); ?> />
                                        <?php _e('Show on Mobile', 'clarkes-terraclean'); ?>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_position"><?php _e('Button Position', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <select id="whatsapp_position" name="whatsapp_position" class="regular-text">
                                        <option value="bottom-right" <?php selected($whatsapp_position, 'bottom-right'); ?>><?php _e('Bottom Right', 'clarkes-terraclean'); ?></option>
                                        <option value="bottom-left" <?php selected($whatsapp_position, 'bottom-left'); ?>><?php _e('Bottom Left', 'clarkes-terraclean'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_offset_x"><?php _e('Horizontal Offset (px)', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <input type="number" id="whatsapp_offset_x" name="whatsapp_offset_x" value="<?php echo esc_attr($whatsapp_offset_x); ?>" class="small-text" min="0" max="200" />
                                    <p class="description"><?php _e('Distance from left/right edge', 'clarkes-terraclean'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_offset_y"><?php _e('Vertical Offset (px)', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <input type="number" id="whatsapp_offset_y" name="whatsapp_offset_y" value="<?php echo esc_attr($whatsapp_offset_y); ?>" class="small-text" min="0" max="200" />
                                    <p class="description"><?php _e('Distance from bottom edge', 'clarkes-terraclean'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Chat Window Settings -->
                    <div class="settings-section" style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                        <h2 style="margin-top: 0; padding-bottom: 15px; border-bottom: 2px solid #e5e7eb;"><?php _e('Chat Window Settings', 'clarkes-terraclean'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_chat_title"><?php _e('Chat Title', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="whatsapp_chat_title" name="whatsapp_chat_title" value="<?php echo esc_attr($whatsapp_chat_title); ?>" class="regular-text" />
                                    <p class="description"><?php _e('Name displayed in chat window header', 'clarkes-terraclean'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_chat_subtitle"><?php _e('Chat Subtitle', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="whatsapp_chat_subtitle" name="whatsapp_chat_subtitle" value="<?php echo esc_attr($whatsapp_chat_subtitle); ?>" class="regular-text" />
                                    <p class="description"><?php _e('Subtitle text (e.g., "Usually replies within minutes")', 'clarkes-terraclean'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_auto_open"><?php _e('Auto-Open Chat', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <input type="checkbox" id="whatsapp_auto_open" name="whatsapp_auto_open" value="1" <?php checked($whatsapp_auto_open, 1); ?> />
                                    <p class="description"><?php _e('Automatically open chat window after a delay', 'clarkes-terraclean'); ?></p>
                                </td>
                            </tr>
                            <tr id="auto-open-delay-row" style="<?php echo $whatsapp_auto_open ? '' : 'display:none;'; ?>">
                                <th scope="row">
                                    <label for="whatsapp_auto_open_delay"><?php _e('Auto-Open Delay (seconds)', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <input type="number" id="whatsapp_auto_open_delay" name="whatsapp_auto_open_delay" value="<?php echo esc_attr($whatsapp_auto_open_delay); ?>" class="small-text" min="1" max="60" />
                                    <p class="description"><?php _e('Seconds before chat window opens automatically', 'clarkes-terraclean'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                </div>
                
                <!-- Right Column -->
                <div class="settings-column">
                    
                    <!-- Button Customization -->
                    <div class="settings-section" style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                        <h2 style="margin-top: 0; padding-bottom: 15px; border-bottom: 2px solid #e5e7eb;"><?php _e('Button Customization', 'clarkes-terraclean'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_button_size"><?php _e('Button Size', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <select id="whatsapp_button_size" name="whatsapp_button_size" class="regular-text">
                                        <option value="small" <?php selected($whatsapp_button_size, 'small'); ?>><?php _e('Small (50px)', 'clarkes-terraclean'); ?></option>
                                        <option value="medium" <?php selected($whatsapp_button_size, 'medium'); ?>><?php _e('Medium (60px)', 'clarkes-terraclean'); ?></option>
                                        <option value="large" <?php selected($whatsapp_button_size, 'large'); ?>><?php _e('Large (70px)', 'clarkes-terraclean'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_button_color"><?php _e('Button Color', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <input type="color" id="whatsapp_button_color" name="whatsapp_button_color" value="<?php echo esc_attr($whatsapp_button_color); ?>" />
                                    <p class="description"><?php _e('Custom color for the WhatsApp button', 'clarkes-terraclean'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Business Hours -->
                    <div class="settings-section" style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                        <h2 style="margin-top: 0; padding-bottom: 15px; border-bottom: 2px solid #e5e7eb;"><?php _e('Business Hours', 'clarkes-terraclean'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_business_hours"><?php _e('Business Hours', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <textarea id="whatsapp_business_hours" name="whatsapp_business_hours" rows="4" class="large-text" placeholder="Monday - Friday: 9:00 AM - 6:00 PM&#10;Saturday: 9:00 AM - 4:00 PM&#10;Sunday: Closed"><?php echo esc_textarea($whatsapp_business_hours); ?></textarea>
                                    <p class="description"><?php _e('Display business hours in chat window', 'clarkes-terraclean'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_offline_message"><?php _e('Offline Message', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <textarea id="whatsapp_offline_message" name="whatsapp_offline_message" rows="2" class="large-text"><?php echo esc_textarea($whatsapp_offline_message); ?></textarea>
                                    <p class="description"><?php _e('Message shown when business is offline', 'clarkes-terraclean'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Notifications -->
                    <div class="settings-section" style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                        <h2 style="margin-top: 0; padding-bottom: 15px; border-bottom: 2px solid #e5e7eb;"><?php _e('Email Notifications', 'clarkes-terraclean'); ?></h2>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_email_notifications"><?php _e('Enable Email Notifications', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <input type="checkbox" id="whatsapp_email_notifications" name="whatsapp_email_notifications" value="1" <?php checked($whatsapp_email_notifications, 1); ?> />
                                    <p class="description"><?php _e('Receive email notifications when customers send messages', 'clarkes-terraclean'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="whatsapp_notification_email"><?php _e('Notification Email', 'clarkes-terraclean'); ?></label>
                                </th>
                                <td>
                                    <input type="email" id="whatsapp_notification_email" name="whatsapp_notification_email" value="<?php echo esc_attr($whatsapp_notification_email); ?>" class="regular-text" />
                                    <p class="description"><?php _e('Email address to receive message notifications', 'clarkes-terraclean'); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                </div>
                
            </div>
            
            <p class="submit">
                <input type="submit" name="clarkes_whatsapp_settings_save" class="button button-primary button-large" value="<?php esc_attr_e('Save Settings', 'clarkes-terraclean'); ?>" />
            </p>
        </form>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        $('#whatsapp_auto_open').on('change', function() {
            if ($(this).is(':checked')) {
                $('#auto-open-delay-row').show();
            } else {
                $('#auto-open-delay-row').hide();
            }
        });
    });
    </script>
    <?php
}
}

/**
 * WhatsApp Messages Page
 */
if (!function_exists('clarkes_whatsapp_messages_page')) {
function clarkes_whatsapp_messages_page() {
    // Get messages from database (if we store them)
    // For now, show a placeholder
    ?>
    <div class="wrap">
        <h1><?php _e('WhatsApp Messages', 'clarkes-terraclean'); ?></h1>
        <p><?php _e('Messages sent through the live chat are delivered via email. Check your email inbox for customer messages.', 'clarkes-terraclean'); ?></p>
        <p><?php _e('To view messages, check the email address configured in Settings → Email Notifications.', 'clarkes-terraclean'); ?></p>
    </div>
    <?php
}
}

/**
 * WhatsApp Customize Page
 */
if (!function_exists('clarkes_whatsapp_customize_page')) {
function clarkes_whatsapp_customize_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('WhatsApp Customization', 'clarkes-terraclean'); ?></h1>
        <p><?php _e('For advanced customization options, please use the WordPress Customizer:', 'clarkes-terraclean'); ?></p>
        <p>
            <a href="<?php echo admin_url('customize.php?autofocus[section]=clarkes_whatsapp'); ?>" class="button button-primary">
                <?php _e('Open Customizer', 'clarkes-terraclean'); ?>
            </a>
        </p>
    </div>
    <?php
}
}

