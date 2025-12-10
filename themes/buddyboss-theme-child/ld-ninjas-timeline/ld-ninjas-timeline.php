<?php
/**
 * LD Ninjas Timeline Block
 * 
 * Custom Gutenberg block for creating beautiful timeline layouts
 * 
 * @package LD_Ninjas_Timeline
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * LD Ninjas Timeline Block Class
 */
class LD_Ninjas_Timeline_Block {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_editor_assets'));
        add_filter('block_categories_all', array($this, 'add_block_category'), 10, 2);
    }
    
    /**
     * Initialize the block
     */
    public function init() {
        $this->register_block();
    }
    
    /**
     * Register the timeline block
     */
    public function register_block() {
        // First register the blocks script (following Swrice plugin pattern)
        wp_register_script(
            'ld-ninjas-timeline-blocks',
            get_stylesheet_directory_uri() . '/ld-ninjas-timeline/assets/js/blocks.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
            '1.0.0',
            true
        );
        
        // Then register the block type
        register_block_type('ld-ninjas/timeline', array(
            'editor_script' => 'ld-ninjas-timeline-blocks',
            'render_callback' => array($this, 'render_timeline_block'),
            'attributes' => array(
                'timelineItems' => array(
                    'type' => 'array',
                    'default' => array(
                        array(
                            'id' => 1,
                            'title' => 'You\'re moving forward — but something feels off',
                            'content' => 'You\'re getting things done — work, family, responsibilities. But deep down, something feels misaligned. Your life is moving, but not in the direction your heart truly longs for.',
                            'position' => 'left',
                            'iconType' => 'svg',
                            'icon' => '<svg viewBox="0 0 24 24"><path d="M12 2a5 5 0 00-5 5v1H6a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2v-6a2 2 0 00-2-2h-1V7a5 5 0 00-5-5z"/></svg>',
                            'iconId' => 0,
                            'iconUrl' => ''
                        ),
                        array(
                            'id' => 2,
                            'title' => 'Feeling "off" is about disconnection',
                            'content' => 'That misalignment starts in one place: disconnection from Allah. Real connection begins by knowing Him — not just knowing about Him — and letting that knowledge shape how you think, work, and live.',
                            'position' => 'right',
                            'iconType' => 'svg',
                            'icon' => '<svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 100 18 9 9 0 000-18zm0 3a6 6 0 110 12 6 6 0 010-12z"/></svg>',
                            'iconId' => 0,
                            'iconUrl' => ''
                        ),
                        array(
                            'id' => 3,
                            'title' => 'Connecting with Allah starts with His Names',
                            'content' => 'If you want to know someone, you start with their name. Allah has revealed over 100 Names for Himself — each one a gateway to understanding Him. Your heart will find peace in knowing Him — and aligning your life around that knowing.',
                            'position' => 'left',
                            'iconType' => 'svg',
                            'icon' => '<svg viewBox="0 0 24 24"><path d="M3 5h18v2H3zM3 11h18v2H3zM3 17h18v2H3z"/></svg>',
                            'iconId' => 0,
                            'iconUrl' => ''
                        ),
                        array(
                            'id' => 4,
                            'title' => 'One Name, One course, One transformation at a time',
                            'content' => 'Each course is logically structured — with pauses and reflections to let you absorb and believe. As you progress, your heart begins to change. Belief becomes transformation. Automatically.',
                            'position' => 'right',
                            'iconType' => 'svg',
                            'icon' => '<svg viewBox="0 0 24 24"><path d="M12 2l3 6 6 .5-4.5 3.75L19 20l-7-4-7 4 1.5-7.75L3 8.5 9 8z"/></svg>',
                            'iconId' => 0,
                            'iconUrl' => ''
                        )
                    )
                )
            )
        ));
    }
    
    /**
     * Render the timeline block
     */
    public function render_timeline_block($attributes) {
        $timeline_items = isset($attributes['timelineItems']) ? $attributes['timelineItems'] : array();
        
        if (empty($timeline_items)) {
            return '';
        }
        
        ob_start();
        ?>
        <div class="timeline-wrapper">
            <div class="center-line"></div>
            <?php foreach ($timeline_items as $item): ?>
                <div class="timeline-item <?php echo esc_attr($item['position']); ?>">
                    <div class="card">
                        <h3><?php echo wp_kses_post($item['title']); ?></h3>
                        <p><?php echo wp_kses_post($item['content']); ?></p>
                    </div>
                    <div class="icon">
                        <div class="icon-inner">
                            <?php if (isset($item['iconType']) && $item['iconType'] === 'image' && !empty($item['iconUrl'])): ?>
                                <img src="<?php echo esc_url($item['iconUrl']); ?>" alt="<?php echo esc_attr($item['title']); ?>" />
                            <?php else: ?>
                                <?php echo wp_kses($item['icon'], array(
                                    'svg' => array(
                                        'viewBox' => array(),
                                        'width' => array(),
                                        'height' => array(),
                                        'fill' => array(),
                                        'xmlns' => array()
                                    ),
                                    'path' => array(
                                        'd' => array(),
                                        'fill' => array()
                                    )
                                )); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        wp_enqueue_style(
            'ld-ninjas-timeline-frontend',
            get_stylesheet_directory_uri() . '/ld-ninjas-timeline/assets/css/frontend.css',
            array(),
            '1.0.0'
        );
    }
    
    /**
     * Enqueue editor assets
     */
    public function enqueue_editor_assets() {
        wp_enqueue_style(
            'ld-ninjas-timeline-editor',
            get_stylesheet_directory_uri() . '/ld-ninjas-timeline/assets/css/editor.css',
            array(),
            '1.0.0'
        );
    }
    
    /**
     * Add custom block category
     */
    public function add_block_category($categories, $post) {
        return array_merge(
            array(
                array(
                    'slug' => 'ld-ninjas',
                    'title' => __('LD Ninjas', 'ld-ninjas-timeline'),
                    'icon' => 'clock'
                )
            ),
            $categories
        );
    }
}

// Initialize the block
new LD_Ninjas_Timeline_Block();
