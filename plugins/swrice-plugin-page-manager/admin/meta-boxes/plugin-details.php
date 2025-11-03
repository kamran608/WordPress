<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get meta values
$plugin_price = get_post_meta($post->ID, 'plugin_price', true);
$plugin_original_price = get_post_meta($post->ID, 'plugin_original_price', true);
$plugin_features = get_post_meta($post->ID, 'plugin_features', true);
$plugin_testimonials = get_post_meta($post->ID, 'plugin_testimonials', true);
$plugin_faq = get_post_meta($post->ID, 'plugin_faq', true);
$plugin_bonuses = get_post_meta($post->ID, 'plugin_bonuses', true);
$buy_now_shortcode = get_post_meta($post->ID, 'buy_now_shortcode', true);
$meta_title = get_post_meta($post->ID, 'meta_title', true);
$meta_description = get_post_meta($post->ID, 'meta_description', true);
$meta_keywords = get_post_meta($post->ID, 'meta_keywords', true);
$hero_subtitle = get_post_meta($post->ID, 'hero_subtitle', true);
$problem_section = get_post_meta($post->ID, 'problem_section', true);
$solution_section = get_post_meta($post->ID, 'solution_section', true);
$guarantee_text = get_post_meta($post->ID, 'guarantee_text', true);
$about_section = get_post_meta($post->ID, 'about_section', true);
$demo_link = get_post_meta($post->ID, 'demo_link', true);
?>

<div class="sppm-meta-box">
    <div class="sppm-tabs">
        <ul class="sppm-tab-nav">
            <li><a href="#tab-basic" class="sppm-tab-link active">Basic Info</a></li>
            <li><a href="#tab-content" class="sppm-tab-link">Content</a></li>
            <li><a href="#tab-pricing" class="sppm-tab-link">Pricing</a></li>
        </ul>
        
        <div id="tab-basic" class="sppm-tab-content active">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="hero_subtitle"><?php _e('Hero Subtitle', 'swrice-plugin-manager'); ?></label>
                    </th>
                    <td>
                        <textarea id="hero_subtitle" name="hero_subtitle" rows="3" cols="50" class="large-text"><?php echo esc_textarea($hero_subtitle); ?></textarea>
                        <p class="description"><?php _e('The subtitle that appears below the main title in the hero section.', 'swrice-plugin-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="buy_now_shortcode"><?php _e('Buy Now Shortcode', 'swrice-plugin-manager'); ?></label>
                    </th>
                    <td>
                        <textarea id="buy_now_shortcode" name="buy_now_shortcode" rows="3" cols="50" class="large-text"><?php echo esc_textarea($buy_now_shortcode); ?></textarea>
                        <p class="description"><?php _e('Paste your buy now shortcode here (e.g., from payment processors like Stripe, PayPal, etc.).', 'swrice-plugin-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="demo_link"><?php _e('Live Demo Link', 'swrice-plugin-manager'); ?></label>
                    </th>
                    <td>
                        <input type="url" id="demo_link" name="demo_link" value="<?php echo esc_attr($demo_link); ?>" class="large-text" placeholder="https://demo.yoursite.com" />
                        <p class="description"><?php _e('Enter the URL for your live demo or preview. This will show as a "Live Demo" button on your plugin page.', 'swrice-plugin-manager'); ?></p>
                    </td>
                </tr>
                
            </table>
        </div>
        
        <div id="tab-content" class="sppm-tab-content">
            
            
            <!-- Section Ordering & Control Panel -->
            <div class="sppm-section-manager">
                <h3><?php _e('Section Management', 'swrice-plugin-manager'); ?></h3>
                <p class="description"><?php _e('Drag and drop sections to reorder them. Use the toggle switches to enable/disable sections.', 'swrice-plugin-manager'); ?></p>
                
                <div id="sppm-section-sortable" class="sppm-sortable-sections">
                    <?php
                    // Get current section order and enabled states
                    $section_order = get_post_meta($post->ID, 'section_order', true);
                    $section_enabled = get_post_meta($post->ID, 'section_enabled', true);
                    
                    // Default section order if not set
                    if (!is_array($section_order) || empty($section_order)) {
                        $section_order = array(
                            'problem', 'solution', 'how_it_works', 'features', 
                            'testimonials', 'faq', 'bonuses', 'guarantee', 
                            'why_choose', 'about', 'final_cta'
                        );
                    }
                    
                    // Default enabled states if not set
                    if (!is_array($section_enabled)) {
                        $section_enabled = array();
                        foreach ($section_order as $section) {
                            $section_enabled[$section] = true;
                        }
                    }
                    
                    // Section labels
                    $section_labels = array(
                        'problem' => __('Problem Section', 'swrice-plugin-manager'),
                        'solution' => __('Solution Section', 'swrice-plugin-manager'),
                        'how_it_works' => __('How It Works Section', 'swrice-plugin-manager'),
                        'features' => __('Features Section', 'swrice-plugin-manager'),
                        'testimonials' => __('Testimonials Section', 'swrice-plugin-manager'),
                        'faq' => __('FAQ Section', 'swrice-plugin-manager'),
                        'bonuses' => __('Bonuses Section', 'swrice-plugin-manager'),
                        'guarantee' => __('Guarantee Section', 'swrice-plugin-manager'),
                        'why_choose' => __('Why Choose Section', 'swrice-plugin-manager'),
                        'about' => __('About Section', 'swrice-plugin-manager'),
                        'final_cta' => __('Final CTA Section', 'swrice-plugin-manager')
                    );
                    
                    foreach ($section_order as $section_key):
                        $is_enabled = isset($section_enabled[$section_key]) ? $section_enabled[$section_key] : true;
                    ?>
                    <div class="sppm-section-item" data-section="<?php echo esc_attr($section_key); ?>">
                        <div class="sppm-section-handle">
                            <span class="sppm-drag-handle">⋮⋮</span>
                            <span class="sppm-section-label"><?php echo esc_html($section_labels[$section_key]); ?></span>
                        </div>
                        <div class="sppm-section-controls">
                            <label class="sppm-toggle-switch">
                                <input type="checkbox" name="section_enabled[<?php echo esc_attr($section_key); ?>]" value="1" <?php checked($is_enabled, true); ?> />
                                <span class="sppm-toggle-slider"></span>
                            </label>
                        </div>
                        <input type="hidden" name="section_order[]" value="<?php echo esc_attr($section_key); ?>" />
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <hr style="margin: 30px 0;" />
            <!-- Problem Section -->
            <div class="sppm-section-control">
                <h3><?php _e('Problem Section', 'swrice-plugin-manager'); ?></h3>
                <div class="sppm-control-group">
                    <label><?php _e('Section Heading', 'swrice-plugin-manager'); ?></label>
                    <div class="sppm-heading-control">
                        <input type="text" name="problem_heading" value="<?php echo esc_attr(get_post_meta($post->ID, 'problem_heading', true) ?: 'The Problems Killing Your Success'); ?>" placeholder="Section Heading" class="sppm-heading-input" />
                        <select name="problem_icon" class="sppm-icon-select">
                            <option value="">No Icon</option>
                            <option value="😤" <?php selected(get_post_meta($post->ID, 'problem_icon', true), '😤'); ?>>😤 Frustrated Face</option>
                            <option value="🚫" <?php selected(get_post_meta($post->ID, 'problem_icon', true), '🚫'); ?>>🚫 Prohibited</option>
                            <option value="⚠️" <?php selected(get_post_meta($post->ID, 'problem_icon', true), '⚠️'); ?>>⚠️ Warning</option>
                            <option value="💸" <?php selected(get_post_meta($post->ID, 'problem_icon', true), '💸'); ?>>💸 Money Loss</option>
                            <option value="📉" <?php selected(get_post_meta($post->ID, 'problem_icon', true), '📉'); ?>>📉 Declining</option>
                        </select>
                    </div>
                </div>
                <div class="sppm-control-group">
                    <label><?php _e('Problem Items', 'swrice-plugin-manager'); ?></label>
                    <div id="sppm-problem-items" class="sppm-repeater">
                        <?php
                        $problem_items = get_post_meta($post->ID, 'problem_items', true);
                        if (!is_array($problem_items)) $problem_items = array();
                        
                        if (empty($problem_items)) {
                            $problem_items = array(
                                array('title' => 'Overwhelming Experience', 'description' => 'Long, cluttered content confuses users and hurts completion rates', 'icon' => '🚫'),
                                array('title' => 'Poor Mobile Experience', 'description' => 'Users struggle to navigate on mobile devices, leading to dropouts', 'icon' => '📱'),
                                array('title' => 'Wasted Time', 'description' => 'Users spend more time searching for content than actually using it', 'icon' => '⏰'),
                                array('title' => 'Lost Revenue', 'description' => 'Poor user experience leads to refund requests and negative reviews', 'icon' => '💸')
                            );
                        }
                        
                        foreach ($problem_items as $index => $item):
                        ?>
                        <div class="sppm-repeater-item" data-index="<?php echo $index; ?>">
                            <div class="sppm-repeater-header">
                                <span class="sppm-repeater-title">Problem #<?php echo ($index + 1); ?></span>
                                <div class="sppm-repeater-actions">
                                    <button type="button" class="sppm-toggle-item">▼</button>
                                    <button type="button" class="sppm-remove-item">✕</button>
                                </div>
                            </div>
                            <div class="sppm-repeater-content">
                                <div class="sppm-field">
                                    <label><?php _e('Problem Title', 'swrice-plugin-manager'); ?></label>
                                    <input type="text" name="problem_items[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" placeholder="Problem title..." class="sppm-full-width" />
                                </div>
                                <div class="sppm-field">
                                    <label><?php _e('Problem Icon', 'swrice-plugin-manager'); ?></label>
                                    <select name="problem_items[<?php echo $index; ?>][icon]" class="sppm-icon-select">
                                        <option value="">No Icon</option>
                                        <option value="🚫" <?php selected($item['icon'] ?? '', '🚫'); ?>>🚫 Prohibited</option>
                                        <option value="📱" <?php selected($item['icon'] ?? '', '📱'); ?>>📱 Mobile</option>
                                        <option value="⏰" <?php selected($item['icon'] ?? '', '⏰'); ?>>⏰ Time</option>
                                        <option value="💸" <?php selected($item['icon'] ?? '', '💸'); ?>>💸 Money Loss</option>
                                        <option value="😤" <?php selected($item['icon'] ?? '', '😤'); ?>>😤 Frustrated</option>
                                        <option value="📉" <?php selected($item['icon'] ?? '', '📉'); ?>>📉 Declining</option>
                                    </select>
                                </div>
                                <div class="sppm-field">
                                    <label><?php _e('Problem Description', 'swrice-plugin-manager'); ?></label>
                                    <textarea name="problem_items[<?php echo $index; ?>][description]" rows="3" placeholder="Describe this problem..." class="sppm-full-width"><?php echo esc_textarea($item['description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="sppm-add-problem" class="button button-secondary"><?php _e('Add Problem', 'swrice-plugin-manager'); ?></button>
                </div>
            </div>
            
            <!-- Solution Section -->
            <div class="sppm-section-control">
                <h3><?php _e('Solution Section', 'swrice-plugin-manager'); ?></h3>
                <div class="sppm-control-group">
                    <label><?php _e('Section Heading', 'swrice-plugin-manager'); ?></label>
                    <div class="sppm-heading-control">
                        <input type="text" name="solution_heading" value="<?php echo esc_attr(get_post_meta($post->ID, 'solution_heading', true) ?: 'Introducing Your Perfect Solution'); ?>" placeholder="Section Heading" class="sppm-heading-input" />
                        <select name="solution_icon" class="sppm-icon-select">
                            <option value="">No Icon</option>
                            <option value="✨" <?php selected(get_post_meta($post->ID, 'solution_icon', true), '✨'); ?>>✨ Sparkles</option>
                            <option value="🚀" <?php selected(get_post_meta($post->ID, 'solution_icon', true), '🚀'); ?>>🚀 Rocket</option>
                            <option value="💡" <?php selected(get_post_meta($post->ID, 'solution_icon', true), '💡'); ?>>💡 Light Bulb</option>
                            <option value="🎯" <?php selected(get_post_meta($post->ID, 'solution_icon', true), '🎯'); ?>>🎯 Target</option>
                            <option value="⚡" <?php selected(get_post_meta($post->ID, 'solution_icon', true), '⚡'); ?>>⚡ Lightning</option>
                        </select>
                    </div>
                </div>
                <div class="sppm-control-group">
                    <label><?php _e('Solution Description', 'swrice-plugin-manager'); ?></label>
                    <textarea name="solution_description" rows="6" class="sppm-full-width" placeholder="Describe your solution..."><?php echo esc_textarea(get_post_meta($post->ID, 'solution_description', true) ?: 'Transform chaotic layouts into clean, professional navigation that users love. Our premium plugin creates an elegant, organized environment that increases completion rates and improves user satisfaction.'); ?></textarea>
                </div>
            </div>
            
            <!-- How It Works Section -->
            <div class="sppm-section-control">
                <h3><?php _e('How It Works Section', 'swrice-plugin-manager'); ?></h3>
                <div class="sppm-control-group">
                    <label><?php _e('Section Heading', 'swrice-plugin-manager'); ?></label>
                    <div class="sppm-heading-control">
                        <input type="text" name="how_it_works_heading" value="<?php echo esc_attr(get_post_meta($post->ID, 'how_it_works_heading', true) ?: 'How It Works - Simple 3-Step Setup'); ?>" placeholder="Section Heading" class="sppm-heading-input" />
                        <select name="how_it_works_icon" class="sppm-icon-select">
                            <option value="">No Icon</option>
                            <option value="🛠️" <?php selected(get_post_meta($post->ID, 'how_it_works_icon', true), '🛠️'); ?>>🛠️ Tools</option>
                            <option value="⚙️" <?php selected(get_post_meta($post->ID, 'how_it_works_icon', true), '⚙️'); ?>>⚙️ Gear</option>
                            <option value="📋" <?php selected(get_post_meta($post->ID, 'how_it_works_icon', true), '📋'); ?>>📋 Clipboard</option>
                            <option value="🎯" <?php selected(get_post_meta($post->ID, 'how_it_works_icon', true), '🎯'); ?>>🎯 Target</option>
                        </select>
                    </div>
                </div>
                <div class="sppm-control-group">
                    <label><?php _e('Steps', 'swrice-plugin-manager'); ?></label>
                    <div id="sppm-steps-items" class="sppm-repeater">
                        <?php
                        $steps_items = get_post_meta($post->ID, 'steps_items', true);
                        if (!is_array($steps_items)) $steps_items = array();
                        
                        if (empty($steps_items)) {
                            $steps_items = array(
                                array('title' => 'Install & Activate', 'description' => 'Upload the plugin, activate it, and you\'re 90% done. No complex configuration required.', 'step' => '1'),
                                array('title' => 'Choose Your Settings', 'description' => 'Configure your preferences using the intuitive admin interface.', 'step' => '2'),
                                array('title' => 'Customize & Launch', 'description' => 'Use the modern admin interface to customize and watch your results soar.', 'step' => '3')
                            );
                        }
                        
                        foreach ($steps_items as $index => $item):
                        ?>
                        <div class="sppm-repeater-item" data-index="<?php echo $index; ?>">
                            <div class="sppm-repeater-header">
                                <span class="sppm-repeater-title">Step #<?php echo ($index + 1); ?></span>
                                <div class="sppm-repeater-actions">
                                    <button type="button" class="sppm-toggle-item">▼</button>
                                    <button type="button" class="sppm-remove-item">✕</button>
                                </div>
                            </div>
                            <div class="sppm-repeater-content">
                                <div class="sppm-field">
                                    <label><?php _e('Step Title', 'swrice-plugin-manager'); ?></label>
                                    <input type="text" name="steps_items[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" placeholder="Step title..." class="sppm-full-width" />
                                </div>
                                <div class="sppm-field">
                                    <label><?php _e('Step Description', 'swrice-plugin-manager'); ?></label>
                                    <textarea name="steps_items[<?php echo $index; ?>][description]" rows="3" placeholder="Describe this step..." class="sppm-full-width"><?php echo esc_textarea($item['description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="sppm-add-step" class="button button-secondary"><?php _e('Add Step', 'swrice-plugin-manager'); ?></button>
                </div>
            </div>
            
            <!-- FAQ Section with Full Control -->
            <div class="sppm-section-control">
                <h3><?php _e('FAQ Section', 'swrice-plugin-manager'); ?></h3>
                
                <!-- FAQ Section Header Control -->
                <div class="sppm-control-group">
                    <label><?php _e('Section Heading', 'swrice-plugin-manager'); ?></label>
                    <div class="sppm-heading-control">
                        <input type="text" name="faq_heading" value="<?php echo esc_attr(get_post_meta($post->ID, 'faq_heading', true) ?: 'Frequently Asked Questions'); ?>" placeholder="Section Heading" class="sppm-heading-input" />
                        <select name="faq_icon" class="sppm-icon-select">
                            <option value="">No Icon</option>
                            <option value="❓" <?php selected(get_post_meta($post->ID, 'faq_icon', true), '❓'); ?>>❓ Question Mark</option>
                            <option value="💬" <?php selected(get_post_meta($post->ID, 'faq_icon', true), '💬'); ?>>💬 Speech Bubble</option>
                            <option value="🤔" <?php selected(get_post_meta($post->ID, 'faq_icon', true), '🤔'); ?>>🤔 Thinking Face</option>
                            <option value="📋" <?php selected(get_post_meta($post->ID, 'faq_icon', true), '📋'); ?>>📋 Clipboard</option>
                            <option value="ℹ️" <?php selected(get_post_meta($post->ID, 'faq_icon', true), 'ℹ️'); ?>>ℹ️ Information</option>
                            <option value="🔍" <?php selected(get_post_meta($post->ID, 'faq_icon', true), '🔍'); ?>>🔍 Magnifying Glass</option>
                        </select>
                    </div>
                    <p class="description"><?php _e('Set the heading and icon for the FAQ section.', 'swrice-plugin-manager'); ?></p>
                </div>
                
                <!-- FAQ Items Repeater -->
                <div class="sppm-control-group">
                    <label><?php _e('FAQ Items', 'swrice-plugin-manager'); ?></label>
                    <div id="sppm-faq-items" class="sppm-repeater">
                        <?php
                        $faq_items = get_post_meta($post->ID, 'faq_items', true);
                        if (!is_array($faq_items)) $faq_items = array();
                        
                        if (empty($faq_items)) {
                            $faq_items = array(
                                array('question' => 'Will this plugin conflict with my theme or other plugins?', 'answer' => 'No! Our plugin uses official template systems, ensuring zero conflicts with themes and other plugins. It\'s designed to work seamlessly with any WordPress theme.'),
                                array('question' => 'Do I need coding skills to use this plugin?', 'answer' => 'Absolutely not! The plugin works perfectly out of the box with default settings. The modern admin interface makes customization as simple as clicking options.'),
                                array('question' => 'Will this work on mobile devices?', 'answer' => 'Yes! The plugin is built with a mobile-first approach. All features work perfectly on smartphones and tablets.')
                            );
                        }
                        
                        foreach ($faq_items as $index => $item):
                        ?>
                        <div class="sppm-repeater-item" data-index="<?php echo $index; ?>">
                            <div class="sppm-repeater-header">
                                <span class="sppm-repeater-title">FAQ Item #<?php echo ($index + 1); ?></span>
                                <div class="sppm-repeater-actions">
                                    <button type="button" class="sppm-toggle-item">▼</button>
                                    <button type="button" class="sppm-remove-item">✕</button>
                                </div>
                            </div>
                            <div class="sppm-repeater-content">
                                <div class="sppm-field">
                                    <label><?php _e('Question', 'swrice-plugin-manager'); ?></label>
                                    <input type="text" name="faq_items[<?php echo $index; ?>][question]" value="<?php echo esc_attr($item['question'] ?? ''); ?>" placeholder="Enter your question here..." class="sppm-full-width" />
                                </div>
                                <div class="sppm-field">
                                    <label><?php _e('Answer', 'swrice-plugin-manager'); ?></label>
                                    <textarea name="faq_items[<?php echo $index; ?>][answer]" rows="4" placeholder="Enter the answer here..." class="sppm-full-width"><?php echo esc_textarea($item['answer'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="sppm-add-faq" class="button button-secondary"><?php _e('Add FAQ Item', 'swrice-plugin-manager'); ?></button>
                    <p class="description"><?php _e('Add multiple questions and answers. They will automatically display on the frontend.', 'swrice-plugin-manager'); ?></p>
                </div>
            </div>
            
            <!-- Features Section with Full Control -->
            <div class="sppm-section-control">
                <h3><?php _e('Features Section', 'swrice-plugin-manager'); ?></h3>
                
                <!-- Features Section Header Control -->
                <div class="sppm-control-group">
                    <label><?php _e('Section Heading', 'swrice-plugin-manager'); ?></label>
                    <div class="sppm-heading-control">
                        <input type="text" name="features_heading" value="<?php echo esc_attr(get_post_meta($post->ID, 'features_heading', true) ?: 'Powerful Features'); ?>" placeholder="Section Heading" class="sppm-heading-input" />
                        <select name="features_icon" class="sppm-icon-select">
                            <option value="">No Icon</option>
                            <option value="🔥" <?php selected(get_post_meta($post->ID, 'features_icon', true), '🔥'); ?>>🔥 Fire</option>
                            <option value="⚡" <?php selected(get_post_meta($post->ID, 'features_icon', true), '⚡'); ?>>⚡ Lightning</option>
                            <option value="🚀" <?php selected(get_post_meta($post->ID, 'features_icon', true), '🚀'); ?>>🚀 Rocket</option>
                            <option value="✨" <?php selected(get_post_meta($post->ID, 'features_icon', true), '✨'); ?>>✨ Sparkles</option>
                            <option value="🎯" <?php selected(get_post_meta($post->ID, 'features_icon', true), '🎯'); ?>>🎯 Target</option>
                            <option value="💎" <?php selected(get_post_meta($post->ID, 'features_icon', true), '💎'); ?>>💎 Diamond</option>
                        </select>
                    </div>
                </div>
                
                <!-- Features Items Repeater -->
                <div class="sppm-control-group">
                    <label><?php _e('Feature Items', 'swrice-plugin-manager'); ?></label>
                    <div id="sppm-feature-items" class="sppm-repeater">
                        <?php
                        $feature_items = get_post_meta($post->ID, 'feature_items', true);
                        if (!is_array($feature_items)) $feature_items = array();
                        
                        if (empty($feature_items)) {
                            $feature_items = array(array('title' => '', 'description' => '', 'icon' => ''));
                        }
                        
                        foreach ($feature_items as $index => $item):
                        ?>
                        <div class="sppm-repeater-item" data-index="<?php echo $index; ?>">
                            <div class="sppm-repeater-header">
                                <span class="sppm-repeater-title">Feature #<?php echo ($index + 1); ?></span>
                                <div class="sppm-repeater-actions">
                                    <button type="button" class="sppm-toggle-item">▼</button>
                                    <button type="button" class="sppm-remove-item">✕</button>
                                </div>
                            </div>
                            <div class="sppm-repeater-content">
                                <div class="sppm-field">
                                    <label><?php _e('Feature Title', 'swrice-plugin-manager'); ?></label>
                                    <input type="text" name="feature_items[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" placeholder="Feature title..." class="sppm-full-width" />
                                </div>
                                <div class="sppm-field">
                                    <label><?php _e('Feature Icon', 'swrice-plugin-manager'); ?></label>
                                    <select name="feature_items[<?php echo $index; ?>][icon]" class="sppm-icon-select">
                                        <option value="">No Icon</option>
                                        <option value="✅" <?php selected($item['icon'] ?? '', '✅'); ?>>✅ Check Mark</option>
                                        <option value="🎯" <?php selected($item['icon'] ?? '', '🎯'); ?>>🎯 Target</option>
                                        <option value="⚡" <?php selected($item['icon'] ?? '', '⚡'); ?>>⚡ Lightning</option>
                                        <option value="🚀" <?php selected($item['icon'] ?? '', '🚀'); ?>>🚀 Rocket</option>
                                        <option value="💎" <?php selected($item['icon'] ?? '', '💎'); ?>>💎 Diamond</option>
                                        <option value="🔧" <?php selected($item['icon'] ?? '', '🔧'); ?>>🔧 Wrench</option>
                                        <option value="📱" <?php selected($item['icon'] ?? '', '📱'); ?>>📱 Mobile</option>
                                        <option value="🎨" <?php selected($item['icon'] ?? '', '🎨'); ?>>🎨 Art</option>
                                    </select>
                                </div>
                                <div class="sppm-field">
                                    <label><?php _e('Feature Description', 'swrice-plugin-manager'); ?></label>
                                    <textarea name="feature_items[<?php echo $index; ?>][description]" rows="3" placeholder="Describe this feature..." class="sppm-full-width"><?php echo esc_textarea($item['description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="sppm-add-feature" class="button button-secondary"><?php _e('Add Feature', 'swrice-plugin-manager'); ?></button>
                </div>
            </div>
            
            <!-- Testimonials Section with Full Control -->
            <div class="sppm-section-control">
                <h3><?php _e('Testimonials Section', 'swrice-plugin-manager'); ?></h3>
                
                <!-- Testimonials Section Header Control -->
                <div class="sppm-control-group">
                    <label><?php _e('Section Heading', 'swrice-plugin-manager'); ?></label>
                    <div class="sppm-heading-control">
                        <input type="text" name="testimonials_heading" value="<?php echo esc_attr(get_post_meta($post->ID, 'testimonials_heading', true) ?: 'What Our Customers Say'); ?>" placeholder="Section Heading" class="sppm-heading-input" />
                        <select name="testimonials_icon" class="sppm-icon-select">
                            <option value="">No Icon</option>
                            <option value="💬" <?php selected(get_post_meta($post->ID, 'testimonials_icon', true), '💬'); ?>>💬 Speech Bubble</option>
                            <option value="⭐" <?php selected(get_post_meta($post->ID, 'testimonials_icon', true), '⭐'); ?>>⭐ Star</option>
                            <option value="👥" <?php selected(get_post_meta($post->ID, 'testimonials_icon', true), '👥'); ?>>👥 People</option>
                            <option value="💝" <?php selected(get_post_meta($post->ID, 'testimonials_icon', true), '💝'); ?>>💝 Heart Gift</option>
                            <option value="🎉" <?php selected(get_post_meta($post->ID, 'testimonials_icon', true), '🎉'); ?>>🎉 Party</option>
                        </select>
                    </div>
                </div>
                
                <!-- Testimonials Items Repeater -->
                <div class="sppm-control-group">
                    <label><?php _e('Testimonial Items', 'swrice-plugin-manager'); ?></label>
                    <div id="sppm-testimonial-items" class="sppm-repeater">
                        <?php
                        $testimonial_items = get_post_meta($post->ID, 'testimonial_items', true);
                        if (!is_array($testimonial_items)) $testimonial_items = array();
                        
                        if (empty($testimonial_items)) {
                            $testimonial_items = array(
                                array('name' => 'Sarah Johnson', 'title' => 'Corporate Training Manager', 'content' => 'This plugin transformed our corporate training platform. Course completion rates increased by 35% within the first month. The dual-mode system is genius!', 'rating' => '5'),
                                array('name' => 'Mike Chen', 'title' => 'Online Course Creator', 'content' => 'Finally, a plugin that makes courses look professional on mobile. Our students love the clean navigation, and we\'ve seen fewer support tickets.', 'rating' => '5'),
                                array('name' => 'Lisa Rodriguez', 'title' => 'Educational Director', 'content' => 'The modern admin interface is beautiful and so easy to use. We customized the colors to match our brand in minutes. Best plugin investment we\'ve made.', 'rating' => '5')
                            );
                        }
                        
                        foreach ($testimonial_items as $index => $item):
                        ?>
                        <div class="sppm-repeater-item" data-index="<?php echo $index; ?>">
                            <div class="sppm-repeater-header">
                                <span class="sppm-repeater-title">Testimonial #<?php echo ($index + 1); ?></span>
                                <div class="sppm-repeater-actions">
                                    <button type="button" class="sppm-toggle-item">▼</button>
                                    <button type="button" class="sppm-remove-item">✕</button>
                                </div>
                            </div>
                            <div class="sppm-repeater-content">
                                <div class="sppm-field-row">
                                    <div class="sppm-field sppm-field-half">
                                        <label><?php _e('Customer Name', 'swrice-plugin-manager'); ?></label>
                                        <input type="text" name="testimonial_items[<?php echo $index; ?>][name]" value="<?php echo esc_attr($item['name'] ?? ''); ?>" placeholder="Customer name..." />
                                    </div>
                                    <div class="sppm-field sppm-field-half">
                                        <label><?php _e('Customer Title/Company', 'swrice-plugin-manager'); ?></label>
                                        <input type="text" name="testimonial_items[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" placeholder="Title or company..." />
                                    </div>
                                </div>
                                <div class="sppm-field">
                                    <label><?php _e('Rating', 'swrice-plugin-manager'); ?></label>
                                    <select name="testimonial_items[<?php echo $index; ?>][rating]">
                                        <option value="5" <?php selected($item['rating'] ?? '5', '5'); ?>>⭐⭐⭐⭐⭐ (5 stars)</option>
                                        <option value="4" <?php selected($item['rating'] ?? '5', '4'); ?>>⭐⭐⭐⭐ (4 stars)</option>
                                        <option value="3" <?php selected($item['rating'] ?? '5', '3'); ?>>⭐⭐⭐ (3 stars)</option>
                                    </select>
                                </div>
                                <div class="sppm-field">
                                    <label><?php _e('Testimonial Content', 'swrice-plugin-manager'); ?></label>
                                    <textarea name="testimonial_items[<?php echo $index; ?>][content]" rows="4" placeholder="What did they say about your plugin..." class="sppm-full-width"><?php echo esc_textarea($item['content'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="sppm-add-testimonial" class="button button-secondary"><?php _e('Add Testimonial', 'swrice-plugin-manager'); ?></button>
                </div>
            </div>
            
            <!-- Bonuses Section -->
            <div class="sppm-section-control">
                <h3><?php _e('Bonuses Section', 'swrice-plugin-manager'); ?></h3>
                <div class="sppm-control-group">
                    <label><?php _e('Section Heading', 'swrice-plugin-manager'); ?></label>
                    <div class="sppm-heading-control">
                        <input type="text" name="bonuses_heading" value="<?php echo esc_attr(get_post_meta($post->ID, 'bonuses_heading', true) ?: 'Exclusive Launch Bonuses (Limited Time)'); ?>" placeholder="Section Heading" class="sppm-heading-input" />
                        <select name="bonuses_icon" class="sppm-icon-select">
                            <option value="">No Icon</option>
                            <option value="🎁" <?php selected(get_post_meta($post->ID, 'bonuses_icon', true), '🎁'); ?>>🎁 Gift</option>
                            <option value="🎉" <?php selected(get_post_meta($post->ID, 'bonuses_icon', true), '🎉'); ?>>🎉 Party</option>
                            <option value="💎" <?php selected(get_post_meta($post->ID, 'bonuses_icon', true), '💎'); ?>>💎 Diamond</option>
                            <option value="⭐" <?php selected(get_post_meta($post->ID, 'bonuses_icon', true), '⭐'); ?>>⭐ Star</option>
                            <option value="🏆" <?php selected(get_post_meta($post->ID, 'bonuses_icon', true), '🏆'); ?>>🏆 Trophy</option>
                        </select>
                    </div>
                </div>
                <div class="sppm-control-group">
                    <label><?php _e('Bonus Items', 'swrice-plugin-manager'); ?></label>
                    <div id="sppm-bonus-items" class="sppm-repeater">
                        <?php
                        $bonus_items = get_post_meta($post->ID, 'bonus_items', true);
                        if (!is_array($bonus_items)) $bonus_items = array();
                        
                        if (empty($bonus_items)) {
                            $bonus_items = array(
                                array('title' => 'Optimization Guide', 'description' => 'Complete PDF guide with 25+ tips to optimize your setup for maximum engagement and completion rates.', 'value' => '$47', 'icon' => '📚'),
                                array('title' => 'Custom CSS Snippets Collection', 'description' => '20+ ready-to-use CSS snippets to further customize your setup, including hover effects, animations, and styling options.', 'value' => '$37', 'icon' => '🎨'),
                                array('title' => 'Priority Email Support', 'description' => 'Get your questions answered within 24 hours with priority email support for the first 90 days after purchase.', 'value' => '$97', 'icon' => '⚡')
                            );
                        }
                        
                        foreach ($bonus_items as $index => $item):
                        ?>
                        <div class="sppm-repeater-item" data-index="<?php echo $index; ?>">
                            <div class="sppm-repeater-header">
                                <span class="sppm-repeater-title">Bonus #<?php echo ($index + 1); ?></span>
                                <div class="sppm-repeater-actions">
                                    <button type="button" class="sppm-toggle-item">▼</button>
                                    <button type="button" class="sppm-remove-item">✕</button>
                                </div>
                            </div>
                            <div class="sppm-repeater-content">
                                <div class="sppm-field-row">
                                    <div class="sppm-field sppm-field-half">
                                        <label><?php _e('Bonus Title', 'swrice-plugin-manager'); ?></label>
                                        <input type="text" name="bonus_items[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" placeholder="Bonus title..." />
                                    </div>
                                    <div class="sppm-field sppm-field-half">
                                        <label><?php _e('Bonus Value', 'swrice-plugin-manager'); ?></label>
                                        <input type="text" name="bonus_items[<?php echo $index; ?>][value]" value="<?php echo esc_attr($item['value'] ?? ''); ?>" placeholder="$47" />
                                    </div>
                                </div>
                                <div class="sppm-field">
                                    <label><?php _e('Bonus Icon', 'swrice-plugin-manager'); ?></label>
                                    <select name="bonus_items[<?php echo $index; ?>][icon]" class="sppm-icon-select">
                                        <option value="">No Icon</option>
                                        <option value="📚" <?php selected($item['icon'] ?? '', '📚'); ?>>📚 Books</option>
                                        <option value="🎨" <?php selected($item['icon'] ?? '', '🎨'); ?>>🎨 Art</option>
                                        <option value="⚡" <?php selected($item['icon'] ?? '', '⚡'); ?>>⚡ Lightning</option>
                                        <option value="🎁" <?php selected($item['icon'] ?? '', '🎁'); ?>>🎁 Gift</option>
                                        <option value="💎" <?php selected($item['icon'] ?? '', '💎'); ?>>💎 Diamond</option>
                                        <option value="🏆" <?php selected($item['icon'] ?? '', '🏆'); ?>>🏆 Trophy</option>
                                    </select>
                                </div>
                                <div class="sppm-field">
                                    <label><?php _e('Bonus Description', 'swrice-plugin-manager'); ?></label>
                                    <textarea name="bonus_items[<?php echo $index; ?>][description]" rows="3" placeholder="Describe this bonus..." class="sppm-full-width"><?php echo esc_textarea($item['description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="sppm-add-bonus" class="button button-secondary"><?php _e('Add Bonus', 'swrice-plugin-manager'); ?></button>
                </div>
            </div>
            
            <!-- Guarantee Section -->
            <div class="sppm-section-control">
                <h3><?php _e('Guarantee Section', 'swrice-plugin-manager'); ?></h3>
                <div class="sppm-control-group">
                    <label><?php _e('Section Heading', 'swrice-plugin-manager'); ?></label>
                    <div class="sppm-heading-control">
                        <input type="text" name="guarantee_heading" value="<?php echo esc_attr(get_post_meta($post->ID, 'guarantee_heading', true) ?: 'Risk-Free 30-Day Money-Back Guarantee'); ?>" placeholder="Section Heading" class="sppm-heading-input" />
                        <select name="guarantee_icon" class="sppm-icon-select">
                            <option value="">No Icon</option>
                            <option value="🛡️" <?php selected(get_post_meta($post->ID, 'guarantee_icon', true), '🛡️'); ?>>🛡️ Shield</option>
                            <option value="💯" <?php selected(get_post_meta($post->ID, 'guarantee_icon', true), '💯'); ?>>💯 Hundred</option>
                            <option value="✅" <?php selected(get_post_meta($post->ID, 'guarantee_icon', true), '✅'); ?>>✅ Check Mark</option>
                            <option value="🔒" <?php selected(get_post_meta($post->ID, 'guarantee_icon', true), '🔒'); ?>>🔒 Lock</option>
                            <option value="💎" <?php selected(get_post_meta($post->ID, 'guarantee_icon', true), '💎'); ?>>💎 Diamond</option>
                        </select>
                    </div>
                </div>
                <div class="sppm-control-group">
                    <label><?php _e('Guarantee Text', 'swrice-plugin-manager'); ?></label>
                    <textarea name="guarantee_text" rows="6" class="sppm-full-width" placeholder="Describe your guarantee..."><?php echo esc_textarea(get_post_meta($post->ID, 'guarantee_text', true) ?: 'We\'re so confident that this plugin will transform your experience and boost engagement that we offer a complete 30-day money-back guarantee. If you\'re not completely satisfied for any reason, simply contact us within 30 days for a full refund. No questions asked.'); ?></textarea>
                </div>
                <div class="sppm-control-group">
                    <label><?php _e('Guarantee Points', 'swrice-plugin-manager'); ?></label>
                    <div id="sppm-guarantee-points" class="sppm-repeater">
                        <?php
                        $guarantee_points = get_post_meta($post->ID, 'guarantee_points', true);
                        if (!is_array($guarantee_points)) $guarantee_points = array();
                        
                        if (empty($guarantee_points)) {
                            $guarantee_points = array(
                                array('point' => 'Try the plugin risk-free for 30 full days'),
                                array('point' => 'Test all features and customization options'),
                                array('point' => 'See the impact on your completion rates'),
                                array('point' => 'Full refund if not completely satisfied')
                            );
                        }
                        
                        foreach ($guarantee_points as $index => $item):
                        ?>
                        <div class="sppm-repeater-item" data-index="<?php echo $index; ?>">
                            <div class="sppm-repeater-header">
                                <span class="sppm-repeater-title">Point #<?php echo ($index + 1); ?></span>
                                <div class="sppm-repeater-actions">
                                    <button type="button" class="sppm-toggle-item">▼</button>
                                    <button type="button" class="sppm-remove-item">✕</button>
                                </div>
                            </div>
                            <div class="sppm-repeater-content">
                                <div class="sppm-field">
                                    <label><?php _e('Guarantee Point', 'swrice-plugin-manager'); ?></label>
                                    <input type="text" name="guarantee_points[<?php echo $index; ?>][point]" value="<?php echo esc_attr($item['point'] ?? ''); ?>" placeholder="Guarantee point..." class="sppm-full-width" />
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="sppm-add-guarantee-point" class="button button-secondary"><?php _e('Add Guarantee Point', 'swrice-plugin-manager'); ?></button>
                </div>
            </div>
            
            
            <!-- Why Choose Section -->
            <div class="sppm-section-control">
                <h3><?php _e('Why Choose Section', 'swrice-plugin-manager'); ?></h3>
                <div class="sppm-control-group">
                    <label><?php _e('Section Heading', 'swrice-plugin-manager'); ?></label>
                    <div class="sppm-heading-control">
                        <input type="text" name="why_choose_heading" value="<?php echo esc_attr(get_post_meta($post->ID, 'why_choose_heading', true) ?: 'Why Choose This Plugin?'); ?>" placeholder="Section Heading" class="sppm-heading-input" />
                        <select name="why_choose_icon" class="sppm-icon-select">
                            <option value="">No Icon</option>
                            <option value="🎯" <?php selected(get_post_meta($post->ID, 'why_choose_icon', true), '🎯'); ?>>🎯 Target</option>
                            <option value="⭐" <?php selected(get_post_meta($post->ID, 'why_choose_icon', true), '⭐'); ?>>⭐ Star</option>
                            <option value="🏆" <?php selected(get_post_meta($post->ID, 'why_choose_icon', true), '🏆'); ?>>🏆 Trophy</option>
                            <option value="💎" <?php selected(get_post_meta($post->ID, 'why_choose_icon', true), '💎'); ?>>💎 Diamond</option>
                            <option value="🚀" <?php selected(get_post_meta($post->ID, 'why_choose_icon', true), '🚀'); ?>>🚀 Rocket</option>
                        </select>
                    </div>
                </div>
                <div class="sppm-control-group">
                    <label><?php _e('Why Choose Items', 'swrice-plugin-manager'); ?></label>
                    <div id="sppm-why-choose-items" class="sppm-repeater">
                        <?php
                        $why_choose_items = get_post_meta($post->ID, 'why_choose_items', true);
                        if (!is_array($why_choose_items)) $why_choose_items = array();
                        
                        if (empty($why_choose_items)) {
                            $why_choose_items = array(
                                array('title' => 'Boost User Engagement', 'description' => 'Reduce cognitive overload and help users focus on one section at a time. Studies show organized content increases completion rates by up to 40%.', 'icon' => '📈'),
                                array('title' => 'Professional Design', 'description' => 'Seamlessly integrates with your existing theme. No design conflicts, no broken layouts - just clean, professional pages that build trust.', 'icon' => '💼'),
                                array('title' => 'Mobile-First Experience', 'description' => 'Perfect responsive design ensures your content looks amazing on every device. Your mobile users will thank you.', 'icon' => '📱'),
                                array('title' => 'Instant Organization', 'description' => 'Transform chaotic layouts into clean, professional navigation that users love. Show only what matters with smooth expandable content.', 'icon' => '⚡')
                            );
                        }
                        
                        foreach ($why_choose_items as $index => $item):
                        ?>
                        <div class="sppm-repeater-item" data-index="<?php echo $index; ?>">
                            <div class="sppm-repeater-header">
                                <span class="sppm-repeater-title">Benefit #<?php echo ($index + 1); ?></span>
                                <div class="sppm-repeater-actions">
                                    <button type="button" class="sppm-toggle-item">▼</button>
                                    <button type="button" class="sppm-remove-item">✕</button>
                                </div>
                            </div>
                            <div class="sppm-repeater-content">
                                <div class="sppm-field">
                                    <label><?php _e('Benefit Title', 'swrice-plugin-manager'); ?></label>
                                    <input type="text" name="why_choose_items[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" placeholder="Benefit title..." class="sppm-full-width" />
                                </div>
                                <div class="sppm-field">
                                    <label><?php _e('Benefit Icon', 'swrice-plugin-manager'); ?></label>
                                    <select name="why_choose_items[<?php echo $index; ?>][icon]" class="sppm-icon-select">
                                        <option value="">No Icon</option>
                                        <option value="📈" <?php selected($item['icon'] ?? '', '📈'); ?>>📈 Chart</option>
                                        <option value="💼" <?php selected($item['icon'] ?? '', '💼'); ?>>💼 Briefcase</option>
                                        <option value="📱" <?php selected($item['icon'] ?? '', '📱'); ?>>📱 Mobile</option>
                                        <option value="⚡" <?php selected($item['icon'] ?? '', '⚡'); ?>>⚡ Lightning</option>
                                        <option value="🎯" <?php selected($item['icon'] ?? '', '🎯'); ?>>🎯 Target</option>
                                        <option value="🚀" <?php selected($item['icon'] ?? '', '🚀'); ?>>🚀 Rocket</option>
                                    </select>
                                </div>
                                <div class="sppm-field">
                                    <label><?php _e('Benefit Description', 'swrice-plugin-manager'); ?></label>
                                    <textarea name="why_choose_items[<?php echo $index; ?>][description]" rows="3" placeholder="Describe this benefit..." class="sppm-full-width"><?php echo esc_textarea($item['description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="sppm-add-why-choose" class="button button-secondary"><?php _e('Add Benefit', 'swrice-plugin-manager'); ?></button>
                </div>
            </div>
            
            <!-- About Section -->
            <div class="sppm-section-control">
                <h3><?php _e('About Section', 'swrice-plugin-manager'); ?></h3>
                <div class="sppm-control-group">
                    <label><?php _e('Section Heading', 'swrice-plugin-manager'); ?></label>
                    <div class="sppm-heading-control">
                        <input type="text" name="about_heading" value="<?php echo esc_attr(get_post_meta($post->ID, 'about_heading', true) ?: 'About Our Company'); ?>" placeholder="Section Heading" class="sppm-heading-input" />
                        <select name="about_icon" class="sppm-icon-select">
                            <option value="">No Icon</option>
                            <option value="👨‍💻" <?php selected(get_post_meta($post->ID, 'about_icon', true), '👨‍💻'); ?>>👨‍💻 Developer</option>
                            <option value="🏢" <?php selected(get_post_meta($post->ID, 'about_icon', true), '🏢'); ?>>🏢 Company</option>
                            <option value="ℹ️" <?php selected(get_post_meta($post->ID, 'about_icon', true), 'ℹ️'); ?>>ℹ️ Information</option>
                            <option value="🎯" <?php selected(get_post_meta($post->ID, 'about_icon', true), '🎯'); ?>>🎯 Mission</option>
                        </select>
                    </div>
                </div>
                <div class="sppm-control-group">
                    <label><?php _e('About Description', 'swrice-plugin-manager'); ?></label>
                    <textarea name="about_description" rows="8" class="sppm-full-width" placeholder="Tell your story..."><?php echo esc_textarea(get_post_meta($post->ID, 'about_description', true) ?: 'We specialize in creating premium WordPress plugins that solve real problems for online educators and course creators. With years of experience in development and a deep understanding of online learning challenges, we build tools that make a real difference in user engagement and success.\n\nOur plugins are used by thousands of educators worldwide, from individual course creators to large educational institutions. We\'re committed to providing high-quality, reliable solutions that help you create better experiences.\n\nNeed Help? Contact us at support@yoursite.com\nWebsite: https://yoursite.com'); ?></textarea>
                </div>
            </div>

            <!-- Final CTA Section -->
            <div class="sppm-section-control">
                <h3><?php _e('Final CTA Section', 'swrice-plugin-manager'); ?></h3>
                <div class="sppm-control-group">
                    <label><?php _e('CTA Title', 'swrice-plugin-manager'); ?></label>
                    <input type="text" name="cta_title" value="<?php echo esc_attr(get_post_meta($post->ID, 'cta_title', true) ?: 'Ready to Get Started?'); ?>" placeholder="CTA Title" class="sppm-full-width" />
                </div>
                <div class="sppm-control-group">
                    <label><?php _e('CTA Subtitle', 'swrice-plugin-manager'); ?></label>
                    <textarea name="cta_subtitle" rows="3" class="sppm-full-width" placeholder="CTA subtitle text..."><?php echo esc_textarea(get_post_meta($post->ID, 'cta_subtitle', true) ?: 'Join thousands of satisfied customers and transform your website today.'); ?></textarea>
                </div>
            </div>

        </div>
        
        <div id="tab-pricing" class="sppm-tab-content">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="plugin_price"><?php _e('Current Price', 'swrice-plugin-manager'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="plugin_price" name="plugin_price" value="<?php echo esc_attr($plugin_price); ?>" step="0.01" min="0" />
                        <p class="description"><?php _e('Current selling price (without currency symbol).', 'swrice-plugin-manager'); ?></p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="plugin_original_price"><?php _e('Original Price', 'swrice-plugin-manager'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="plugin_original_price" name="plugin_original_price" value="<?php echo esc_attr($plugin_original_price); ?>" step="0.01" min="0" />
                        <p class="description"><?php _e('Original price (for showing discounts). Leave empty if no discount.', 'swrice-plugin-manager'); ?></p>
                    </td>
                </tr>
                
            </table>
        </div>
        
    </div>
</div>

<style>
.sppm-meta-box {
    margin-top: 20px;
}

.sppm-tab-nav {
    list-style: none;
    margin: 0;
    padding: 0;
    border-bottom: 1px solid #ccd0d4;
    display: flex;
}

.sppm-tab-nav li {
    margin: 0;
    padding: 0;
}

.sppm-tab-link {
    display: block;
    padding: 12px 20px;
    text-decoration: none;
    color: #646970;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
}

.sppm-tab-link:hover,
.sppm-tab-link.active {
    color: #2271b1;
    border-bottom-color: #2271b1;
}

.sppm-tab-content {
    display: none;
    padding: 20px 0;
}

.sppm-tab-content.active {
    display: block;
}

.sppm-tab-content .form-table th {
    width: 200px;
    vertical-align: top;
    padding-top: 15px;
}

.sppm-tab-content .form-table td {
    padding-top: 10px;
}

.sppm-tab-content textarea,
.sppm-tab-content input[type="text"],
.sppm-tab-content input[type="number"] {
    width: 100%;
    max-width: 600px;
}

/* Section Control Styles */
.sppm-section-control {
    background: #fff;
    border: 1px solid #e1e1e1;
    border-radius: 8px;
    margin-bottom: 20px;
    padding: 20px;
}

.sppm-section-control h3 {
    margin: 0 0 20px 0;
    padding: 0 0 10px 0;
    border-bottom: 2px solid #2271b1;
    color: #2271b1;
    font-size: 18px;
}

.sppm-control-group {
    margin-bottom: 25px;
}

.sppm-control-group > label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #23282d;
}

.sppm-heading-control {
    display: flex;
    gap: 10px;
    align-items: center;
}

.sppm-heading-input {
    flex: 1;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.sppm-icon-select {
    min-width: 200px;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

/* Repeater Styles */
.sppm-repeater {
    border: 1px solid #e1e1e1;
    border-radius: 6px;
    margin-bottom: 15px;
}

.sppm-repeater-item {
    border-bottom: 1px solid #e1e1e1;
}

.sppm-repeater-item:last-child {
    border-bottom: none;
}

.sppm-repeater-header {
    background: #f8f9fa;
    padding: 12px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    border-bottom: 1px solid #e1e1e1;
}

.sppm-repeater-title {
    font-weight: 600;
    color: #2271b1;
}

.sppm-repeater-actions {
    display: flex;
    gap: 5px;
}

.sppm-toggle-item,
.sppm-remove-item {
    background: none;
    border: none;
    padding: 5px 8px;
    cursor: pointer;
    border-radius: 3px;
    font-size: 12px;
    transition: background-color 0.3s ease;
}

.sppm-toggle-item:hover {
    background: #e1e1e1;
}

.sppm-remove-item {
    color: #dc3545;
}

.sppm-remove-item:hover {
    background: #dc3545;
    color: white;
}

.sppm-repeater-content {
    padding: 20px;
    display: block;
}

.sppm-repeater-content.collapsed {
    display: none;
}

.sppm-field {
    margin-bottom: 15px;
}

.sppm-field label {
    display: block;
    font-weight: 500;
    margin-bottom: 5px;
    color: #555;
}

.sppm-field input,
.sppm-field textarea,
.sppm-field select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.sppm-field input:focus,
.sppm-field textarea:focus,
.sppm-field select:focus {
    border-color: #2271b1;
    box-shadow: 0 0 0 1px #2271b1;
    outline: none;
}

.sppm-field-row {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.sppm-field-half {
    flex: 1;
}

.sppm-full-width {
    width: 100%;
}

/* Button Styles */
.button.button-secondary {
    background: #2271b1;
    border-color: #2271b1;
    color: white;
}

.button.button-secondary:hover {
    background: #135e96;
    border-color: #135e96;
}

/* Responsive */
@media (max-width: 782px) {
    .sppm-heading-control {
        flex-direction: column;
        align-items: stretch;
    }
    
    .sppm-field-row {
        flex-direction: column;
    }
    
    .sppm-repeater-header {
        padding: 10px;
    }
    
    .sppm-repeater-content {
        padding: 15px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Tab functionality
    $('.sppm-tab-link').on('click', function(e) {
        e.preventDefault();
        
        var target = $(this).attr('href');
        
        // Remove active class from all tabs and content
        $('.sppm-tab-link').removeClass('active');
        $('.sppm-tab-content').removeClass('active');
        
        // Add active class to clicked tab and corresponding content
        $(this).addClass('active');
        $(target).addClass('active');
    });
    
    // Repeater functionality
    
    // Toggle repeater item
    $(document).on('click', '.sppm-toggle-item', function(e) {
        e.preventDefault();
        var $content = $(this).closest('.sppm-repeater-item').find('.sppm-repeater-content');
        var $icon = $(this);
        
        if ($content.hasClass('collapsed')) {
            $content.removeClass('collapsed').slideDown(200);
            $icon.text('▼');
        } else {
            $content.addClass('collapsed').slideUp(200);
            $icon.text('▶');
        }
    });
    
    // Remove repeater item
    $(document).on('click', '.sppm-remove-item', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to remove this item?')) {
            $(this).closest('.sppm-repeater-item').slideUp(200, function() {
                $(this).remove();
                updateRepeaterIndexes();
            });
        }
    });
    
    // Add FAQ item
    $('#sppm-add-faq').on('click', function(e) {
        e.preventDefault();
        var $container = $('#sppm-faq-items');
        var index = $container.find('.sppm-repeater-item').length;
        
        var template = `
            <div class="sppm-repeater-item" data-index="${index}">
                <div class="sppm-repeater-header">
                    <span class="sppm-repeater-title">FAQ Item #${index + 1}</span>
                    <div class="sppm-repeater-actions">
                        <button type="button" class="sppm-toggle-item">▼</button>
                        <button type="button" class="sppm-remove-item">✕</button>
                    </div>
                </div>
                <div class="sppm-repeater-content">
                    <div class="sppm-field">
                        <label>Question</label>
                        <input type="text" name="faq_items[${index}][question]" value="" placeholder="Enter your question here..." class="sppm-full-width" />
                    </div>
                    <div class="sppm-field">
                        <label>Answer</label>
                        <textarea name="faq_items[${index}][answer]" rows="4" placeholder="Enter the answer here..." class="sppm-full-width"></textarea>
                    </div>
                </div>
            </div>
        `;
        
        $container.append(template);
    });
    
    // Add Feature item
    $('#sppm-add-feature').on('click', function(e) {
        e.preventDefault();
        var $container = $('#sppm-feature-items');
        var index = $container.find('.sppm-repeater-item').length;
        
        var template = `
            <div class="sppm-repeater-item" data-index="${index}">
                <div class="sppm-repeater-header">
                    <span class="sppm-repeater-title">Feature #${index + 1}</span>
                    <div class="sppm-repeater-actions">
                        <button type="button" class="sppm-toggle-item">▼</button>
                        <button type="button" class="sppm-remove-item">✕</button>
                    </div>
                </div>
                <div class="sppm-repeater-content">
                    <div class="sppm-field">
                        <label>Feature Title</label>
                        <input type="text" name="feature_items[${index}][title]" value="" placeholder="Feature title..." class="sppm-full-width" />
                    </div>
                    <div class="sppm-field">
                        <label>Feature Icon</label>
                        <select name="feature_items[${index}][icon]" class="sppm-icon-select">
                            <option value="">No Icon</option>
                            <option value="✅">✅ Check Mark</option>
                            <option value="🎯">🎯 Target</option>
                            <option value="⚡">⚡ Lightning</option>
                            <option value="🚀">🚀 Rocket</option>
                            <option value="💎">💎 Diamond</option>
                            <option value="🔧">🔧 Wrench</option>
                            <option value="📱">📱 Mobile</option>
                            <option value="🎨">🎨 Art</option>
                        </select>
                    </div>
                    <div class="sppm-field">
                        <label>Feature Description</label>
                        <textarea name="feature_items[${index}][description]" rows="3" placeholder="Describe this feature..." class="sppm-full-width"></textarea>
                    </div>
                </div>
            </div>
        `;
        
        $container.append(template);
    });
    
    // Add Testimonial item
    $('#sppm-add-testimonial').on('click', function(e) {
        e.preventDefault();
        var $container = $('#sppm-testimonial-items');
        var index = $container.find('.sppm-repeater-item').length;
        
        var template = `
            <div class="sppm-repeater-item" data-index="${index}">
                <div class="sppm-repeater-header">
                    <span class="sppm-repeater-title">Testimonial #${index + 1}</span>
                    <div class="sppm-repeater-actions">
                        <button type="button" class="sppm-toggle-item">▼</button>
                        <button type="button" class="sppm-remove-item">✕</button>
                    </div>
                </div>
                <div class="sppm-repeater-content">
                    <div class="sppm-field-row">
                        <div class="sppm-field sppm-field-half">
                            <label>Customer Name</label>
                            <input type="text" name="testimonial_items[${index}][name]" value="" placeholder="Customer name..." />
                        </div>
                        <div class="sppm-field sppm-field-half">
                            <label>Customer Title/Company</label>
                            <input type="text" name="testimonial_items[${index}][title]" value="" placeholder="Title or company..." />
                        </div>
                    </div>
                    <div class="sppm-field">
                        <label>Rating</label>
                        <select name="testimonial_items[${index}][rating]">
                            <option value="5">⭐⭐⭐⭐⭐ (5 stars)</option>
                            <option value="4">⭐⭐⭐⭐ (4 stars)</option>
                            <option value="3">⭐⭐⭐ (3 stars)</option>
                        </select>
                    </div>
                    <div class="sppm-field">
                        <label>Testimonial Content</label>
                        <textarea name="testimonial_items[${index}][content]" rows="4" placeholder="What did they say about your plugin..." class="sppm-full-width"></textarea>
                    </div>
                </div>
            </div>
        `;
        
        $container.append(template);
    });
    
    // Update repeater indexes
    function updateRepeaterIndexes() {
        $('.sppm-repeater').each(function() {
            $(this).find('.sppm-repeater-item').each(function(index) {
                $(this).attr('data-index', index);
                $(this).find('.sppm-repeater-title').text(function() {
                    var text = $(this).text();
                    return text.replace(/#\d+/, '#' + (index + 1));
                });
                
                // Update input names
                $(this).find('input, textarea, select').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', newName);
                    }
                });
            });
        });
    }
    
    
    // Add Problem item
    $('#sppm-add-problem').on('click', function(e) {
        e.preventDefault();
        var $container = $('#sppm-problem-items');
        var index = $container.find('.sppm-repeater-item').length;
        
        var template = `
            <div class="sppm-repeater-item" data-index="${index}">
                <div class="sppm-repeater-header">
                    <span class="sppm-repeater-title">Problem #${index + 1}</span>
                    <div class="sppm-repeater-actions">
                        <button type="button" class="sppm-toggle-item">▼</button>
                        <button type="button" class="sppm-remove-item">✕</button>
                    </div>
                </div>
                <div class="sppm-repeater-content">
                    <div class="sppm-field">
                        <label>Problem Title</label>
                        <input type="text" name="problem_items[${index}][title]" value="" placeholder="Problem title..." class="sppm-full-width" />
                    </div>
                    <div class="sppm-field">
                        <label>Problem Icon</label>
                        <select name="problem_items[${index}][icon]" class="sppm-icon-select">
                            <option value="">No Icon</option>
                            <option value="🚫">🚫 Prohibited</option>
                            <option value="📱">📱 Mobile</option>
                            <option value="⏰">⏰ Time</option>
                            <option value="💸">💸 Money Loss</option>
                            <option value="😤">😤 Frustrated</option>
                            <option value="📉">📉 Declining</option>
                        </select>
                    </div>
                    <div class="sppm-field">
                        <label>Problem Description</label>
                        <textarea name="problem_items[${index}][description]" rows="3" placeholder="Describe this problem..." class="sppm-full-width"></textarea>
                    </div>
                </div>
            </div>
        `;
        
        $container.append(template);
    });
    
    // Add Step item
    $('#sppm-add-step').on('click', function(e) {
        e.preventDefault();
        var $container = $('#sppm-steps-items');
        var index = $container.find('.sppm-repeater-item').length;
        
        var template = `
            <div class="sppm-repeater-item" data-index="${index}">
                <div class="sppm-repeater-header">
                    <span class="sppm-repeater-title">Step #${index + 1}</span>
                    <div class="sppm-repeater-actions">
                        <button type="button" class="sppm-toggle-item">▼</button>
                        <button type="button" class="sppm-remove-item">✕</button>
                    </div>
                </div>
                <div class="sppm-repeater-content">
                    <div class="sppm-field">
                        <label>Step Title</label>
                        <input type="text" name="steps_items[${index}][title]" value="" placeholder="Step title..." class="sppm-full-width" />
                    </div>
                    <div class="sppm-field">
                        <label>Step Description</label>
                        <textarea name="steps_items[${index}][description]" rows="3" placeholder="Describe this step..." class="sppm-full-width"></textarea>
                    </div>
                </div>
            </div>
        `;
        
        $container.append(template);
    });
    
    // Add Bonus item
    $('#sppm-add-bonus').on('click', function(e) {
        e.preventDefault();
        var $container = $('#sppm-bonus-items');
        var index = $container.find('.sppm-repeater-item').length;
        
        var template = `
            <div class="sppm-repeater-item" data-index="${index}">
                <div class="sppm-repeater-header">
                    <span class="sppm-repeater-title">Bonus #${index + 1}</span>
                    <div class="sppm-repeater-actions">
                        <button type="button" class="sppm-toggle-item">▼</button>
                        <button type="button" class="sppm-remove-item">✕</button>
                    </div>
                </div>
                <div class="sppm-repeater-content">
                    <div class="sppm-field-row">
                        <div class="sppm-field sppm-field-half">
                            <label>Bonus Title</label>
                            <input type="text" name="bonus_items[${index}][title]" value="" placeholder="Bonus title..." />
                        </div>
                        <div class="sppm-field sppm-field-half">
                            <label>Bonus Value</label>
                            <input type="text" name="bonus_items[${index}][value]" value="" placeholder="$47" />
                        </div>
                    </div>
                    <div class="sppm-field">
                        <label>Bonus Icon</label>
                        <select name="bonus_items[${index}][icon]" class="sppm-icon-select">
                            <option value="">No Icon</option>
                            <option value="📚">📚 Books</option>
                            <option value="🎨">🎨 Art</option>
                            <option value="⚡">⚡ Lightning</option>
                            <option value="🎁">🎁 Gift</option>
                            <option value="💎">💎 Diamond</option>
                            <option value="🏆">🏆 Trophy</option>
                        </select>
                    </div>
                    <div class="sppm-field">
                        <label>Bonus Description</label>
                        <textarea name="bonus_items[${index}][description]" rows="3" placeholder="Describe this bonus..." class="sppm-full-width"></textarea>
                    </div>
                </div>
            </div>
        `;
        
        $container.append(template);
    });
    
    // Add Guarantee Point item
    $('#sppm-add-guarantee-point').on('click', function(e) {
        e.preventDefault();
        var $container = $('#sppm-guarantee-points');
        var index = $container.find('.sppm-repeater-item').length;
        
        var template = `
            <div class="sppm-repeater-item" data-index="${index}">
                <div class="sppm-repeater-header">
                    <span class="sppm-repeater-title">Point #${index + 1}</span>
                    <div class="sppm-repeater-actions">
                        <button type="button" class="sppm-toggle-item">▼</button>
                        <button type="button" class="sppm-remove-item">✕</button>
                    </div>
                </div>
                <div class="sppm-repeater-content">
                    <div class="sppm-field">
                        <label>Guarantee Point</label>
                        <input type="text" name="guarantee_points[${index}][point]" value="" placeholder="Guarantee point..." class="sppm-full-width" />
                    </div>
                </div>
            </div>
        `;
        
        $container.append(template);
    });
    
    // Add Why Choose item
    $('#sppm-add-why-choose').on('click', function(e) {
        e.preventDefault();
        var $container = $('#sppm-why-choose-items');
        var index = $container.find('.sppm-repeater-item').length;
        
        var template = `
            <div class="sppm-repeater-item" data-index="${index}">
                <div class="sppm-repeater-header">
                    <span class="sppm-repeater-title">Benefit #${index + 1}</span>
                    <div class="sppm-repeater-actions">
                        <button type="button" class="sppm-toggle-item">▼</button>
                        <button type="button" class="sppm-remove-item">✕</button>
                    </div>
                </div>
                <div class="sppm-repeater-content">
                    <div class="sppm-field">
                        <label>Benefit Title</label>
                        <input type="text" name="why_choose_items[${index}][title]" value="" placeholder="Benefit title..." class="sppm-full-width" />
                    </div>
                    <div class="sppm-field">
                        <label>Benefit Icon</label>
                        <select name="why_choose_items[${index}][icon]" class="sppm-icon-select">
                            <option value="">No Icon</option>
                            <option value="📈">📈 Chart</option>
                            <option value="💼">💼 Briefcase</option>
                            <option value="📱">📱 Mobile</option>
                            <option value="⚡">⚡ Lightning</option>
                            <option value="🎯">🎯 Target</option>
                            <option value="🚀">🚀 Rocket</option>
                        </select>
                    </div>
                    <div class="sppm-field">
                        <label>Benefit Description</label>
                        <textarea name="why_choose_items[${index}][description]" rows="3" placeholder="Describe this benefit..." class="sppm-full-width"></textarea>
                    </div>
                </div>
            </div>
        `;
        
        $container.append(template);
    });

    // Initialize collapsed state for existing items
    $('.sppm-repeater-content').each(function(index) {
        if (index > 0) { // Keep first item open, collapse others
            $(this).addClass('collapsed').hide();
            $(this).siblings('.sppm-repeater-header').find('.sppm-toggle-item').text('▶');
        }
    });
});
</script>
