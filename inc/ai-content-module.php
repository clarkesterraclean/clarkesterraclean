<?php
/**
 * AI Content Creator Module
 * Professional AI-powered content generation for WordPress
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add AI Content Module Menu
 */
if (!function_exists('clarkes_add_ai_content_menu')) {
function clarkes_add_ai_content_menu() {
    add_menu_page(
        __('AI Content Creator', 'clarkes-terraclean'),
        __('AI Content', 'clarkes-terraclean'),
        'edit_posts',
        'clarkes-ai-content',
        'clarkes_ai_content_page',
        'dashicons-edit',
        31
    );
}
}
add_action('admin_menu', 'clarkes_add_ai_content_menu');

/**
 * Enqueue AI Content Scripts
 */
if (!function_exists('clarkes_ai_content_scripts')) {
function clarkes_ai_content_scripts($hook) {
    // Load on AI Content page and post edit pages
    if (strpos($hook, 'clarkes-ai-content') !== false || in_array($hook, array('post.php', 'post-new.php', 'page.php', 'page-new.php'))) {
        wp_enqueue_script('jquery');
        
        wp_enqueue_script(
            'clarkes-ai-content',
            get_template_directory_uri() . '/inc/ai-content.js',
            array('jquery'),
            filemtime(get_template_directory() . '/inc/ai-content.js'),
            true
        );
        
        wp_localize_script('clarkes-ai-content', 'clarkesAIContent', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('clarkes_ai_content'),
            'ai_api_key' => get_option('clarkes_ai_api_key', ''),
        ));
        
        wp_enqueue_style(
            'clarkes-ai-content-style',
            get_template_directory_uri() . '/inc/ai-content.css',
            array(),
            filemtime(get_template_directory() . '/inc/ai-content.css')
        );
    }
}
}
add_action('admin_enqueue_scripts', 'clarkes_ai_content_scripts');

/**
 * AI Content Creator Page
 */
if (!function_exists('clarkes_ai_content_page')) {
function clarkes_ai_content_page() {
    $ai_api_key = get_option('clarkes_ai_api_key', '');
    ?>
    <div class="wrap clarkes-ai-content-page">
        <h1><?php _e('AI Content Creator', 'clarkes-terraclean'); ?></h1>
        
        <?php if (empty($ai_api_key)) : ?>
        <div class="notice notice-warning">
            <p><?php _e('AI API Key not configured. Please add your OpenAI API key in the Customizer → Advanced section.', 'clarkes-terraclean'); ?></p>
        </div>
        <?php endif; ?>
        
        <div class="ai-content-generator" style="background: white; padding: 30px; border: 1px solid #ddd; border-radius: 8px; margin: 20px 0;">
            <h2><?php _e('Generate Content', 'clarkes-terraclean'); ?></h2>
            
            <form id="ai-content-form">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="content-type"><?php _e('Content Type', 'clarkes-terraclean'); ?></label>
                        </th>
                        <td>
                            <select id="content-type" name="content_type" class="regular-text">
                                <option value="title"><?php _e('Title Only', 'clarkes-terraclean'); ?></option>
                                <option value="description"><?php _e('Description/Excerpt', 'clarkes-terraclean'); ?></option>
                                <option value="paragraph"><?php _e('Paragraph', 'clarkes-terraclean'); ?></option>
                                <option value="article"><?php _e('Full Article', 'clarkes-terraclean'); ?></option>
                                <option value="meta_description"><?php _e('Meta Description', 'clarkes-terraclean'); ?></option>
                                <option value="case_study"><?php _e('Case Study Content', 'clarkes-terraclean'); ?></option>
                                <option value="gallery_description"><?php _e('Gallery Item Description', 'clarkes-terraclean'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="content-topic"><?php _e('Topic/Subject', 'clarkes-terraclean'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="content-topic" name="topic" class="large-text" placeholder="<?php esc_attr_e('e.g., DPF cleaning service for a 2019 Ford Transit', 'clarkes-terraclean'); ?>" />
                            <p class="description"><?php _e('Describe what you want the content to be about', 'clarkes-terraclean'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="content-tone"><?php _e('Tone', 'clarkes-terraclean'); ?></label>
                        </th>
                        <td>
                            <select id="content-tone" name="tone" class="regular-text">
                                <option value="professional"><?php _e('Professional', 'clarkes-terraclean'); ?></option>
                                <option value="friendly"><?php _e('Friendly', 'clarkes-terraclean'); ?></option>
                                <option value="casual"><?php _e('Casual', 'clarkes-terraclean'); ?></option>
                                <option value="technical"><?php _e('Technical', 'clarkes-terraclean'); ?></option>
                                <option value="conversational"><?php _e('Conversational', 'clarkes-terraclean'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="content-length"><?php _e('Length', 'clarkes-terraclean'); ?></label>
                        </th>
                        <td>
                            <select id="content-length" name="length" class="regular-text">
                                <option value="short"><?php _e('Short (50-100 words)', 'clarkes-terraclean'); ?></option>
                                <option value="medium" selected><?php _e('Medium (200-300 words)', 'clarkes-terraclean'); ?></option>
                                <option value="long"><?php _e('Long (500-800 words)', 'clarkes-terraclean'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="content-keywords"><?php _e('Keywords (optional)', 'clarkes-terraclean'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="content-keywords" name="keywords" class="large-text" placeholder="<?php esc_attr_e('e.g., DPF cleaning, Kent, engine maintenance', 'clarkes-terraclean'); ?>" />
                            <p class="description"><?php _e('Comma-separated keywords to include naturally in the content', 'clarkes-terraclean'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <button type="button" class="button button-primary button-large" id="btn-generate-content">
                        <?php _e('✨ Generate Content', 'clarkes-terraclean'); ?>
                    </button>
                </p>
            </form>
            
            <div id="ai-content-result" style="display: none; margin-top: 30px; padding: 20px; background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb;">
                <h3><?php _e('Generated Content', 'clarkes-terraclean'); ?></h3>
                <div id="ai-content-output" style="background: white; padding: 15px; border-radius: 4px; min-height: 100px; border: 1px solid #d1d5db;"></div>
                <p style="margin-top: 15px;">
                    <button type="button" class="button" id="btn-copy-content"><?php _e('Copy to Clipboard', 'clarkes-terraclean'); ?></button>
                    <button type="button" class="button" id="btn-regenerate-content"><?php _e('Regenerate', 'clarkes-terraclean'); ?></button>
                </p>
            </div>
            
            <div id="ai-content-loading" style="display: none; margin-top: 20px; text-align: center; padding: 20px;">
                <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f4f6; border-top-color: #3b82f6; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <p style="margin-top: 10px; color: #6b7280;"><?php _e('Generating content...', 'clarkes-terraclean'); ?></p>
            </div>
        </div>
    </div>
    
    <style>
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    </style>
    <?php
}
}

/**
 * Add AI Content Button to Post/Page Editors
 */
if (!function_exists('clarkes_add_ai_content_meta_boxes')) {
function clarkes_add_ai_content_meta_boxes() {
    $post_types = array('post', 'page', 'case_study', 'recent_work');
    
    foreach ($post_types as $post_type) {
        add_meta_box(
            'clarkes_ai_content',
            __('AI Content Assistant', 'clarkes-terraclean'),
            'clarkes_ai_content_meta_box',
            $post_type,
            'side',
            'high'
        );
    }
}
}
add_action('add_meta_boxes', 'clarkes_add_ai_content_meta_boxes');

/**
 * AI Content Meta Box
 */
if (!function_exists('clarkes_ai_content_meta_box')) {
function clarkes_ai_content_meta_box($post) {
    ?>
    <div class="clarkes-ai-content-meta-box">
        <p><?php _e('Use AI to generate professional content for this post.', 'clarkes-terraclean'); ?></p>
        
        <div style="margin-bottom: 10px;">
            <label for="ai-content-prompt" style="display: block; margin-bottom: 5px; font-weight: 600;">
                <?php _e('What should the content be about?', 'clarkes-terraclean'); ?>
            </label>
            <textarea id="ai-content-prompt" rows="3" style="width: 100%;" placeholder="<?php esc_attr_e('e.g., A case study about DPF cleaning for a commercial vehicle', 'clarkes-terraclean'); ?>"></textarea>
        </div>
        
        <div style="margin-bottom: 10px;">
            <label for="ai-content-type-editor" style="display: block; margin-bottom: 5px; font-weight: 600;">
                <?php _e('Generate:', 'clarkes-terraclean'); ?>
            </label>
            <select id="ai-content-type-editor" style="width: 100%;">
                <option value="title"><?php _e('Title', 'clarkes-terraclean'); ?></option>
                <option value="excerpt"><?php _e('Excerpt/Description', 'clarkes-terraclean'); ?></option>
                <option value="content"><?php _e('Full Content', 'clarkes-terraclean'); ?></option>
                <option value="meta_description"><?php _e('Meta Description', 'clarkes-terraclean'); ?></option>
            </select>
        </div>
        
        <button type="button" class="button button-primary" id="btn-ai-generate-editor" style="width: 100%;">
            <?php _e('✨ Generate with AI', 'clarkes-terraclean'); ?>
        </button>
        
        <div id="ai-content-result-editor" style="display: none; margin-top: 15px; padding: 10px; background: #f9fafb; border-radius: 4px; border: 1px solid #e5e7eb;">
            <div id="ai-content-output-editor" style="background: white; padding: 10px; border-radius: 4px; margin-bottom: 10px; min-height: 50px; max-height: 200px; overflow-y: auto;"></div>
            <div style="display: flex; gap: 5px;">
                <button type="button" class="button button-small" id="btn-ai-use-content"><?php _e('Use This', 'clarkes-terraclean'); ?></button>
                <button type="button" class="button button-small" id="btn-ai-regenerate-editor"><?php _e('Regenerate', 'clarkes-terraclean'); ?></button>
            </div>
        </div>
        
        <div id="ai-content-loading-editor" style="display: none; text-align: center; padding: 10px;">
            <span class="spinner is-active" style="float: none; margin: 0;"></span>
            <span style="color: #6b7280; font-size: 12px;"><?php _e('Generating...', 'clarkes-terraclean'); ?></span>
        </div>
    </div>
    <?php
}
}

/**
 * AJAX: Generate AI Content
 */
if (!function_exists('clarkes_ajax_generate_ai_content')) {
function clarkes_ajax_generate_ai_content() {
    check_ajax_referer('clarkes_ai_content', 'nonce');
    
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $ai_api_key = get_option('clarkes_ai_api_key', '');
    if (empty($ai_api_key)) {
        wp_send_json_error(array('message' => 'AI API key not configured'));
        return;
    }
    
    $content_type = isset($_POST['content_type']) ? sanitize_text_field($_POST['content_type']) : 'paragraph';
    $topic = isset($_POST['topic']) ? sanitize_text_field($_POST['topic']) : '';
    $tone = isset($_POST['tone']) ? sanitize_text_field($_POST['tone']) : 'professional';
    $length = isset($_POST['length']) ? sanitize_text_field($_POST['length']) : 'medium';
    $keywords = isset($_POST['keywords']) ? sanitize_text_field($_POST['keywords']) : '';
    
    if (empty($topic)) {
        wp_send_json_error(array('message' => 'Topic is required'));
        return;
    }
    
    // Build prompt based on content type
    $prompt = clarkes_build_ai_content_prompt($content_type, $topic, $tone, $length, $keywords);
    
    // Call OpenAI API
    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $ai_api_key,
            'Content-Type' => 'application/json',
        ),
        'body' => json_encode(array(
            'model' => 'gpt-4',
            'messages' => array(
                array(
                    'role' => 'system',
                    'content' => 'You are a professional content writer specializing in automotive services, particularly DPF cleaning, engine maintenance, and related services. Write natural, human-sounding content that is SEO-friendly and engaging.'
                ),
                array(
                    'role' => 'user',
                    'content' => $prompt
                )
            ),
            'max_tokens' => $length === 'long' ? 2000 : ($length === 'medium' ? 1000 : 300),
            'temperature' => 0.7,
        )),
        'timeout' => 60,
    ));
    
    if (is_wp_error($response)) {
        wp_send_json_error(array('message' => 'API Error: ' . $response->get_error_message()));
        return;
    }
    
    $body = json_decode(wp_remote_retrieve_body($response), true);
    
    if (isset($body['error'])) {
        wp_send_json_error(array('message' => 'OpenAI Error: ' . $body['error']['message']));
        return;
    }
    
    if (isset($body['choices'][0]['message']['content'])) {
        $content = trim($body['choices'][0]['message']['content']);
        wp_send_json_success(array('content' => $content));
    } else {
        wp_send_json_error(array('message' => 'No content generated'));
    }
}
}
add_action('wp_ajax_clarkes_generate_ai_content', 'clarkes_ajax_generate_ai_content');

/**
 * Build AI Content Prompt
 */
if (!function_exists('clarkes_build_ai_content_prompt')) {
function clarkes_build_ai_content_prompt($content_type, $topic, $tone, $length, $keywords) {
    $prompt = '';
    
    switch ($content_type) {
        case 'title':
            $prompt = "Write a compelling, SEO-optimized title (50-60 characters) about: {$topic}";
            if ($keywords) {
                $prompt .= ". Include these keywords naturally: {$keywords}";
            }
            break;
            
        case 'description':
        case 'excerpt':
            $prompt = "Write a {$tone} {$length} description/excerpt (120-160 characters) about: {$topic}";
            if ($keywords) {
                $prompt .= ". Include these keywords naturally: {$keywords}";
            }
            $prompt .= ". Make it engaging and informative.";
            break;
            
        case 'meta_description':
            $prompt = "Write an SEO-optimized meta description (150-160 characters) about: {$topic}";
            if ($keywords) {
                $prompt .= ". Include these keywords: {$keywords}";
            }
            $prompt .= ". Make it compelling and include a call to action.";
            break;
            
        case 'paragraph':
            $word_count = $length === 'long' ? '500-800' : ($length === 'medium' ? '200-300' : '50-100');
            $prompt = "Write a {$tone} {$word_count}-word paragraph about: {$topic}";
            if ($keywords) {
                $prompt .= ". Naturally incorporate these keywords: {$keywords}";
            }
            $prompt .= ". Write in a natural, human-sounding way that is professional yet engaging.";
            break;
            
        case 'case_study':
            $word_count = $length === 'long' ? '800-1200' : ($length === 'medium' ? '400-600' : '200-300');
            $prompt = "Write a {$tone} {$word_count}-word case study about: {$topic}";
            if ($keywords) {
                $prompt .= ". Include these keywords naturally: {$keywords}";
            }
            $prompt .= ". Structure it with: Problem/Challenge, Solution, Results/Outcome. Make it detailed and professional.";
            break;
            
        case 'gallery_description':
            $prompt = "Write a {$tone} short description (100-150 words) for a gallery item about: {$topic}";
            if ($keywords) {
                $prompt .= ". Include these keywords: {$keywords}";
            }
            $prompt .= ". Focus on what work was carried out and the results.";
            break;
            
        case 'article':
        case 'content':
            $word_count = $length === 'long' ? '1000-1500' : ($length === 'medium' ? '500-800' : '200-400');
            $prompt = "Write a {$tone} {$word_count}-word article about: {$topic}";
            if ($keywords) {
                $prompt .= ". Naturally incorporate these keywords throughout: {$keywords}";
            }
            $prompt .= ". Structure it with an introduction, main content with headings, and a conclusion. Make it informative, engaging, and SEO-friendly.";
            break;
    }
    
    return $prompt;
}
}

