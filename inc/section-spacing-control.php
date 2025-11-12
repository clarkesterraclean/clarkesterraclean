<?php
/**
 * Enhanced Section Spacing Custom Control
 * Provides a better UI for managing section spacing
 *
 * @package ClarkesTerraClean
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Clarkes_Section_Spacing_Control')) {
class Clarkes_Section_Spacing_Control extends WP_Customize_Control {
    
    public $type = 'section_spacing';
    public $sections = array();
    
    public function enqueue() {
        wp_enqueue_script(
            'clarkes-section-spacing-control',
            get_template_directory_uri() . '/inc/section-spacing-control.js',
            array('jquery', 'customize-controls'),
            filemtime(get_template_directory() . '/inc/section-spacing-control.js'),
            true
        );
        
        wp_enqueue_style(
            'clarkes-section-spacing-control',
            get_template_directory_uri() . '/inc/section-spacing-control.css',
            array(),
            filemtime(get_template_directory() . '/inc/section-spacing-control.css')
        );
    }
    
    public function render_content() {
        if (empty($this->sections)) {
            return;
        }
        
        $global_value = get_theme_mod('section_padding_vertical_global', 64);
        ?>
        <div class="clarkes-section-spacing-control">
            <div class="spacing-header">
                <h3><?php echo esc_html($this->label); ?></h3>
                <p class="description"><?php echo esc_html($this->description); ?></p>
            </div>
            
            <!-- Global Setting -->
            <div class="spacing-global-card">
                <div class="spacing-card-header">
                    <span class="spacing-icon">🌐</span>
                    <div class="spacing-card-title">
                        <strong>Global Default</strong>
                        <span class="spacing-card-subtitle">Applied to all sections unless overridden</span>
                    </div>
                </div>
                <div class="spacing-card-controls">
                    <label>
                        <span class="control-label">Top & Bottom Padding</span>
                        <input 
                            type="number" 
                            class="spacing-input" 
                            data-setting="section_padding_vertical_global"
                            value="<?php echo esc_attr($global_value); ?>"
                            min="0"
                            max="300"
                            step="4"
                        />
                        <span class="unit">px</span>
                    </label>
                    <button type="button" class="button-link reset-global" data-reset="section_padding_vertical_global">Reset to 64px</button>
                </div>
            </div>
            
            <!-- Section Controls -->
            <div class="spacing-sections-grid">
                <?php foreach ($this->sections as $section_key => $section_data) : 
                    $top_setting = $section_key . '_section_padding_top';
                    $bottom_setting = $section_key . '_section_padding_bottom';
                    $top_value = get_theme_mod($top_setting, '');
                    $bottom_value = get_theme_mod($bottom_setting, '');
                    $top_display = $top_value !== '' ? $top_value : $global_value;
                    $bottom_display = $bottom_value !== '' ? $bottom_value : $global_value;
                ?>
                <div class="spacing-section-card" data-section="<?php echo esc_attr($section_key); ?>">
                    <div class="spacing-card-header">
                        <span class="spacing-icon"><?php echo esc_html($section_data['icon']); ?></span>
                        <div class="spacing-card-title">
                            <strong><?php echo esc_html($section_data['title']); ?></strong>
                            <span class="spacing-card-subtitle"><?php echo esc_html($section_data['subtitle']); ?></span>
                        </div>
                    </div>
                    
                    <div class="spacing-card-controls">
                        <div class="spacing-control-group">
                            <label>
                                <span class="control-label">Top Padding</span>
                                <div class="spacing-input-wrapper">
                                    <input 
                                        type="number" 
                                        class="spacing-input" 
                                        data-setting="<?php echo esc_attr($top_setting); ?>"
                                        value="<?php echo esc_attr($top_value); ?>"
                                        placeholder="<?php echo esc_attr($global_value); ?>"
                                        min="0"
                                        max="300"
                                        step="4"
                                    />
                                    <span class="unit">px</span>
                                </div>
                                <?php if ($top_value === '') : ?>
                                    <span class="using-global">Using global (<?php echo esc_html($global_value); ?>px)</span>
                                <?php else : ?>
                                    <span class="custom-value">Custom value</span>
                                <?php endif; ?>
                            </label>
                            
                            <label>
                                <span class="control-label">Bottom Padding</span>
                                <div class="spacing-input-wrapper">
                                    <input 
                                        type="number" 
                                        class="spacing-input" 
                                        data-setting="<?php echo esc_attr($bottom_setting); ?>"
                                        value="<?php echo esc_attr($bottom_value); ?>"
                                        placeholder="<?php echo esc_attr($global_value); ?>"
                                        min="0"
                                        max="300"
                                        step="4"
                                    />
                                    <span class="unit">px</span>
                                </div>
                                <?php if ($bottom_value === '') : ?>
                                    <span class="using-global">Using global (<?php echo esc_html($global_value); ?>px)</span>
                                <?php else : ?>
                                    <span class="custom-value">Custom value</span>
                                <?php endif; ?>
                            </label>
                        </div>
                        
                        <div class="spacing-card-actions">
                            <button type="button" class="button-link reset-section" data-section="<?php echo esc_attr($section_key); ?>">
                                Reset to Global
                            </button>
                            <button type="button" class="button-link link-paddings" data-section="<?php echo esc_attr($section_key); ?>">
                                Link Top & Bottom
                            </button>
                        </div>
                        
                        <!-- Quick Presets -->
                        <div class="spacing-presets">
                            <span class="presets-label">Quick Presets:</span>
                            <button type="button" class="button-link preset-btn" data-section="<?php echo esc_attr($section_key); ?>" data-value="32">Tight (32px)</button>
                            <button type="button" class="button-link preset-btn" data-section="<?php echo esc_attr($section_key); ?>" data-value="64">Normal (64px)</button>
                            <button type="button" class="button-link preset-btn" data-section="<?php echo esc_attr($section_key); ?>" data-value="96">Spacious (96px)</button>
                            <button type="button" class="button-link preset-btn" data-section="<?php echo esc_attr($section_key); ?>" data-value="128">Extra (128px)</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
}

