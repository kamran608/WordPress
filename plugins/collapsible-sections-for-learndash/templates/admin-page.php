<?php
/**
 * Admin page template for Collapsible Sections for LearnDash
 *
 * @package CollapsibleSectionsLearnDash
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get current settings
$settings = $this->get_settings();
?>

<div class="wrap csld-admin-wrap">
    <h1><?php _e('Collapsible Sections for LearnDash', 'collapsible-sections-learndash'); ?></h1>
    
    <div class="csld-admin-header">
        <p class="description">
            <?php _e('Customize the appearance of your collapsible course sections to match your site design.', 'collapsible-sections-learndash'); ?>
        </p>
    </div>

    <div class="csld-admin-content">
        <!-- Message area for save/error feedback -->
        <div id="csld-save-message" class="notice" style="display: none;">
            <p></p>
        </div>
        
        <div class="csld-settings-panel">
            <form id="csld-settings-form" method="post">
                <?php wp_nonce_field('csld_settings_nonce', 'csld_nonce'); ?>
                
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="enable_plugin">
                                    <?php _e('Enable Plugin', 'collapsible-sections-learndash'); ?>
                                </label>
                            </th>
                            <td>
                                <label class="csld-toggle-switch">
                                    <input 
                                        type="checkbox" 
                                        id="enable_plugin" 
                                        name="enable_plugin" 
                                        value="yes"
                                        <?php checked($settings['enable_plugin'], 'yes'); ?>
                                    />
                                    <span class="csld-toggle-slider"></span>
                                </label>
                                <p class="description">
                                    <?php _e('Turn the collapsible sections feature on or off. When disabled, normal LearnDash functionality will be used instead.', 'collapsible-sections-learndash'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="toggler_outer_color">
                                    <?php _e('Toggler Outer Color', 'collapsible-sections-learndash'); ?>
                                </label>
                            </th>
                            <td>
                                <input 
                                    type="text" 
                                    id="toggler_outer_color" 
                                    name="toggler_outer_color" 
                                    value="<?php echo esc_attr($settings['toggler_outer_color']); ?>" 
                                    class="csld-color-picker" 
                                    data-default-color="#093b7d"
                                />
                                <p class="description">
                                    <?php _e('Choose the background color for the section toggle icons.', 'collapsible-sections-learndash'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="toggler_inner_color">
                                    <?php _e('Toggler Inner Color', 'collapsible-sections-learndash'); ?>
                                </label>
                            </th>
                            <td>
                                <input 
                                    type="text" 
                                    id="toggler_inner_color" 
                                    name="toggler_inner_color" 
                                    value="<?php echo esc_attr($settings['toggler_inner_color']); ?>" 
                                    class="csld-color-picker" 
                                    data-default-color="#a3a5a9"
                                />
                                <p class="description">
                                    <?php _e('Choose the inner fill color for the section toggle icons.', 'collapsible-sections-learndash'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="section_background_color">
                                    <?php _e('Section Background Color', 'collapsible-sections-learndash'); ?>
                                </label>
                            </th>
                            <td>
                                <input 
                                    type="text" 
                                    id="section_background_color" 
                                    name="section_background_color" 
                                    value="<?php echo esc_attr($settings['section_background_color']); ?>" 
                                    class="csld-color-picker" 
                                    data-default-color="#ffffff"
                                />
                                <p class="description">
                                    <?php _e('Choose the background color for section toggle buttons.', 'collapsible-sections-learndash'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="section_border_color">
                                    <?php _e('Section Border Color', 'collapsible-sections-learndash'); ?>
                                </label>
                            </th>
                            <td>
                                <input 
                                    type="text" 
                                    id="section_border_color" 
                                    name="section_border_color" 
                                    value="<?php echo esc_attr($settings['section_border_color']); ?>" 
                                    class="csld-color-picker" 
                                    data-default-color="#e2e7ed"
                                />
                                <p class="description">
                                    <?php _e('Choose the border color for section items (.custom-section-item).', 'collapsible-sections-learndash'); ?>
                                </p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="expand_collapse_behavior">
                                    <?php _e('Expand/Collapse Behavior', 'collapsible-sections-learndash'); ?>
                                </label>
                            </th>
                            <td>
                                <select id="expand_collapse_behavior" name="expand_collapse_behavior">
                                    <option value="all_content" <?php selected($settings['expand_collapse_behavior'], 'all_content'); ?>>
                                        <?php _e('Expand/Collapse All Content (Default)', 'collapsible-sections-learndash'); ?>
                                    </option>
                                    <option value="sections_only" <?php selected($settings['expand_collapse_behavior'], 'sections_only'); ?>>
                                        <?php _e('Expand/Collapse Sections Only', 'collapsible-sections-learndash'); ?>
                                    </option>
                                </select>
                                <p class="description">
                                    <?php _e('Choose how the "Expand All" button behaves:<br><strong>All Content:</strong> Expands both sections and lesson content (LearnDash default + sections)<br><strong>Sections Only:</strong> Only expands/collapses section headings, not individual lessons', 'collapsible-sections-learndash'); ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="csld-form-actions">
                    <button type="submit" class="button button-primary" id="csld-save-settings">
                        <?php _e('Save Settings', 'collapsible-sections-learndash'); ?>
                    </button>
                    
                    <button type="button" class="button button-secondary" id="csld-reset-settings">
                        <?php _e('Reset to Defaults', 'collapsible-sections-learndash'); ?>
                    </button>
                </div>
            </form>
        </div>
        
        <div class="csld-info-panel">
            <div class="csld-info-box">
                <h3><?php _e('How It Works', 'collapsible-sections-learndash'); ?></h3>
                <ul>
                    <li><?php _e('Course sections are collapsed by default', 'collapsible-sections-learndash'); ?></li>
                    <li><?php _e('Students can click to expand/collapse sections', 'collapsible-sections-learndash'); ?></li>
                    <li><?php _e('Improves course navigation and reduces clutter', 'collapsible-sections-learndash'); ?></li>
                    <li><?php _e('Works with all LearnDash themes and templates', 'collapsible-sections-learndash'); ?></li>
                </ul>
            </div>
            
            <div class="csld-info-box">
                <h3><?php _e('Plugin Status', 'collapsible-sections-learndash'); ?></h3>
                <div class="csld-status-item">
                    <span class="csld-status-label"><?php _e('LearnDash Active:', 'collapsible-sections-learndash'); ?></span>
                    <span class="csld-status-value csld-status-success">
                        <?php _e('Yes', 'collapsible-sections-learndash'); ?>
                    </span>
                </div>
                <div class="csld-status-item">
                    <span class="csld-status-label"><?php _e('Template Override:', 'collapsible-sections-learndash'); ?></span>
                    <span class="csld-status-value csld-status-success">
                        <?php _e('Active', 'collapsible-sections-learndash'); ?>
                    </span>
                </div>
                <div class="csld-status-item">
                    <span class="csld-status-label"><?php _e('Plugin Version:', 'collapsible-sections-learndash'); ?></span>
                    <span class="csld-status-value"><?php echo CSLD_VERSION; ?></span>
                </div>
            </div>
            
            <div class="csld-info-box">
                <h3><?php _e('Need Help?', 'collapsible-sections-learndash'); ?></h3>
                <p><?php _e('If you encounter any issues or need support, please check the plugin documentation or contact support.', 'collapsible-sections-learndash'); ?></p>
                <p>
                    <a href="https://swrice.com/collapsible-sections-for-learndash/" target="_blank" class="button button-secondary">
                        <?php _e('Documentation', 'collapsible-sections-learndash'); ?>
                    </a>
                    <a href="https://swrice.com/contact-us/" target="_blank" class="button button-secondary" style="margin-left: 10px;">
                        <?php _e('Contact Support', 'collapsible-sections-learndash'); ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
    
    <div id="csld-save-message" class="notice notice-success is-dismissible" style="display: none;">
        <p><?php _e('Settings saved successfully!', 'collapsible-sections-learndash'); ?></p>
    </div>
</div>
