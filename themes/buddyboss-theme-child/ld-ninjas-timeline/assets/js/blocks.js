/**
 * LD Ninjas Timeline Block - Gutenberg Editor
 * 
 * Complete Gutenberg block with full control over timeline items
 */

(function() {
    'use strict';

    const { registerBlockType } = wp.blocks;
    const { InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor;
    const { PanelBody, TextControl, TextareaControl, SelectControl, Button } = wp.components;
    const { Fragment, createElement: el } = wp.element;
    const { __ } = wp.i18n;

    // Default timeline items
    const defaultTimelineItems = [
        {
            id: 1,
            title: 'You\'re moving forward — but something feels off',
            content: 'You\'re getting things done — work, family, responsibilities. But deep down, something feels misaligned. Your life is moving, but not in the direction your heart truly longs for.',
            position: 'left',
            iconType: 'svg',
            icon: '<svg viewBox="0 0 24 24"><path d="M12 2a5 5 0 00-5 5v1H6a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2v-6a2 2 0 00-2-2h-1V7a5 5 0 00-5-5z"/></svg>',
            iconId: 0,
            iconUrl: ''
        },
        {
            id: 2,
            title: 'Feeling "off" is about disconnection',
            content: 'That misalignment starts in one place: disconnection from Allah. Real connection begins by knowing Him — not just knowing about Him — and letting that knowledge shape how you think, work, and live.',
            position: 'right',
            iconType: 'svg',
            icon: '<svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 100 18 9 9 0 000-18zm0 3a6 6 0 110 12 6 6 0 010-12z"/></svg>',
            iconId: 0,
            iconUrl: ''
        },
        {
            id: 3,
            title: 'Connecting with Allah starts with His Names',
            content: 'If you want to know someone, you start with their name. Allah has revealed over 100 Names for Himself — each one a gateway to understanding Him. Your heart will find peace in knowing Him — and aligning your life around that knowing.',
            position: 'left',
            iconType: 'svg',
            icon: '<svg viewBox="0 0 24 24"><path d="M3 5h18v2H3zM3 11h18v2H3zM3 17h18v2H3z"/></svg>',
            iconId: 0,
            iconUrl: ''
        },
        {
            id: 4,
            title: 'One Name, One course, One transformation at a time',
            content: 'Each course is logically structured — with pauses and reflections to let you absorb and believe. As you progress, your heart begins to change. Belief becomes transformation. Automatically.',
            position: 'right',
            iconType: 'svg',
            icon: '<svg viewBox="0 0 24 24"><path d="M12 2l3 6 6 .5-4.5 3.75L19 20l-7-4-7 4 1.5-7.75L3 8.5 9 8z"/></svg>',
            iconId: 0,
            iconUrl: ''
        }
    ];

    // Available icons
    const availableIcons = {
        'lock': '<svg viewBox="0 0 24 24"><path d="M12 2a5 5 0 00-5 5v1H6a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2v-6a2 2 0 00-2-2h-1V7a5 5 0 00-5-5z"/></svg>',
        'circle': '<svg viewBox="0 0 24 24"><path d="M12 3a9 9 0 100 18 9 9 0 000-18zm0 3a6 6 0 110 12 6 6 0 010-12z"/></svg>',
        'list': '<svg viewBox="0 0 24 24"><path d="M3 5h18v2H3zM3 11h18v2H3zM3 17h18v2H3z"/></svg>',
        'star': '<svg viewBox="0 0 24 24"><path d="M12 2l3 6 6 .5-4.5 3.75L19 20l-7-4-7 4 1.5-7.75L3 8.5 9 8z"/></svg>',
        'heart': '<svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>',
        'check': '<svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>',
        'arrow': '<svg viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>',
        'lightbulb': '<svg viewBox="0 0 24 24"><path d="M9 21c0 .5.4 1 1 1h4c.6 0 1-.5 1-1v-1H9v1zm3-19C8.1 2 5 5.1 5 9c0 2.4 1.2 4.5 3 5.7V17h8v-2.3c1.8-1.2 3-3.3 3-5.7 0-3.9-3.1-7-7-7z"/></svg>'
    };

    // Register the block
    registerBlockType('ld-ninjas/timeline', {
        title: __('LD ninjas Timeline', 'ld-ninjas-timeline'),
        description: __('Create beautiful timeline layouts with full control', 'ld-ninjas-timeline'),
        icon: 'clock',
        category: 'ld-ninjas',
        keywords: [
            __('timeline', 'ld-ninjas-timeline'),
            __('steps', 'ld-ninjas-timeline'),
            __('process', 'ld-ninjas-timeline')
        ],
        attributes: {
            timelineItems: {
                type: 'array',
                default: defaultTimelineItems
            }
        },
        supports: {
            align: ['wide', 'full'],
            html: false
        },

        edit: function(props) {
            const { attributes, setAttributes } = props;
            const { timelineItems } = attributes;

            // Update timeline item
            const updateTimelineItem = (index, field, value) => {
                const newItems = [...timelineItems];
                newItems[index] = { ...newItems[index], [field]: value };
                setAttributes({ timelineItems: newItems });
            };

            // Add new timeline item
            const addTimelineItem = () => {
                const newId = Math.max(...timelineItems.map(item => item.id)) + 1;
                const newItem = {
                    id: newId,
                    title: 'New Timeline Item',
                    content: 'Add your content here...',
                    position: timelineItems.length % 2 === 0 ? 'left' : 'right',
                    iconType: 'svg',
                    icon: availableIcons.circle,
                    iconId: 0,
                    iconUrl: ''
                };
                setAttributes({ timelineItems: [...timelineItems, newItem] });
            };

            // Remove timeline item
            const removeTimelineItem = (index) => {
                const newItems = timelineItems.filter((_, i) => i !== index);
                setAttributes({ timelineItems: newItems });
            };

            // Move item up
            const moveItemUp = (index) => {
                if (index === 0) return;
                const newItems = [...timelineItems];
                [newItems[index - 1], newItems[index]] = [newItems[index], newItems[index - 1]];
                setAttributes({ timelineItems: newItems });
            };

            // Move item down
            const moveItemDown = (index) => {
                if (index === timelineItems.length - 1) return;
                const newItems = [...timelineItems];
                [newItems[index], newItems[index + 1]] = [newItems[index + 1], newItems[index]];
                setAttributes({ timelineItems: newItems });
            };

            // Render timeline preview
            const renderTimelinePreview = () => {
                return el('div', { className: 'timeline-wrapper' },
                    el('div', { className: 'center-line' }),
                    timelineItems.map((item, index) => 
                        el('div', { 
                            key: item.id, 
                            className: `timeline-item ${item.position}` 
                        },
                            el('div', { className: 'card' },
                                el('h3', {}, item.title),
                                el('p', {}, item.content)
                            ),
                            el('div', { className: 'icon' },
                                el('div', { 
                                    className: 'icon-inner'
                                },
                                    item.iconType === 'image' && item.iconUrl ? 
                                        el('img', { 
                                            src: item.iconUrl, 
                                            alt: item.title,
                                            style: { width: '28px', height: '28px', objectFit: 'contain' }
                                        }) :
                                        el('div', { dangerouslySetInnerHTML: { __html: item.icon } })
                                )
                            )
                        )
                    )
                );
            };

            // Render inspector controls
            const renderInspectorControls = () => {
                return el(InspectorControls, {},
                    el(PanelBody, { 
                        title: __('Timeline Settings', 'ld-ninjas-timeline'),
                        initialOpen: true 
                    },
                        el('div', { className: 'ld-ninjas-timeline-controls' },
                            el('h4', {}, __('Timeline Items', 'ld-ninjas-timeline')),
                            timelineItems.map((item, index) => 
                                el('div', { 
                                    key: item.id, 
                                    className: 'timeline-item-control' 
                                },
                                    el('h5', {}, `${__('Item', 'ld-ninjas-timeline')} ${index + 1}`),
                                    el(TextControl, {
                                        label: __('Title', 'ld-ninjas-timeline'),
                                        value: item.title,
                                        onChange: (value) => updateTimelineItem(index, 'title', value)
                                    }),
                                    el(TextareaControl, {
                                        label: __('Content', 'ld-ninjas-timeline'),
                                        value: item.content,
                                        onChange: (value) => updateTimelineItem(index, 'content', value),
                                        rows: 3
                                    }),
                                    el(SelectControl, {
                                        label: __('Position', 'ld-ninjas-timeline'),
                                        value: item.position,
                                        options: [
                                            { label: __('Left', 'ld-ninjas-timeline'), value: 'left' },
                                            { label: __('Right', 'ld-ninjas-timeline'), value: 'right' }
                                        ],
                                        onChange: (value) => updateTimelineItem(index, 'position', value)
                                    }),
                                    el(SelectControl, {
                                        label: __('Icon Type', 'ld-ninjas-timeline'),
                                        value: item.iconType || 'svg',
                                        options: [
                                            { label: __('SVG Icon', 'ld-ninjas-timeline'), value: 'svg' },
                                            { label: __('Upload Image', 'ld-ninjas-timeline'), value: 'image' }
                                        ],
                                        onChange: (value) => updateTimelineItem(index, 'iconType', value)
                                    }),
                                    item.iconType === 'svg' ? 
                                        el(SelectControl, {
                                            label: __('SVG Icon', 'ld-ninjas-timeline'),
                                            value: Object.keys(availableIcons).find(key => availableIcons[key] === item.icon) || 'circle',
                                            options: Object.keys(availableIcons).map(key => ({
                                                label: key.charAt(0).toUpperCase() + key.slice(1),
                                                value: key
                                            })),
                                            onChange: (value) => updateTimelineItem(index, 'icon', availableIcons[value])
                                        }) :
                                        el(MediaUploadCheck, {},
                                            el(MediaUpload, {
                                                onSelect: (media) => {
                                                    updateTimelineItem(index, 'iconId', media.id);
                                                    updateTimelineItem(index, 'iconUrl', media.url);
                                                },
                                                allowedTypes: ['image'],
                                                value: item.iconId,
                                                render: ({ open }) => (
                                                    el('div', {},
                                                        el('p', {}, __('Icon Image', 'ld-ninjas-timeline')),
                                                        item.iconUrl ? 
                                                            el('div', {},
                                                                el('img', { 
                                                                    src: item.iconUrl, 
                                                                    alt: item.title,
                                                                    style: { width: '50px', height: '50px', objectFit: 'contain', marginBottom: '10px' }
                                                                }),
                                                                el('br'),
                                                                el(Button, {
                                                                    onClick: open,
                                                                    isSecondary: true
                                                                }, __('Change Image', 'ld-ninjas-timeline')),
                                                                el('br'),
                                                                el(Button, {
                                                                    onClick: () => {
                                                                        updateTimelineItem(index, 'iconId', 0);
                                                                        updateTimelineItem(index, 'iconUrl', '');
                                                                    },
                                                                    isDestructive: true,
                                                                    isSmall: true,
                                                                    style: { marginTop: '5px' }
                                                                }, __('Remove Image', 'ld-ninjas-timeline'))
                                                            ) :
                                                            el(Button, {
                                                                onClick: open,
                                                                isPrimary: true
                                                            }, __('Select Image', 'ld-ninjas-timeline'))
                                                    )
                                                )
                                            })
                                        ),
                                    el('div', { className: 'timeline-item-actions' },
                                        el(Button, {
                                            isSmall: true,
                                            onClick: () => moveItemUp(index),
                                            disabled: index === 0
                                        }, __('↑ Up', 'ld-ninjas-timeline')),
                                        el(Button, {
                                            isSmall: true,
                                            onClick: () => moveItemDown(index),
                                            disabled: index === timelineItems.length - 1
                                        }, __('↓ Down', 'ld-ninjas-timeline')),
                                        el(Button, {
                                            isSmall: true,
                                            isDestructive: true,
                                            onClick: () => removeTimelineItem(index),
                                            disabled: timelineItems.length <= 1
                                        }, __('Remove', 'ld-ninjas-timeline'))
                                    )
                                )
                            ),
                            el('div', { className: 'add-timeline-item' },
                                el(Button, {
                                    isPrimary: true,
                                    onClick: addTimelineItem
                                }, __('+ Add Timeline Item', 'ld-ninjas-timeline'))
                            )
                        )
                    )
                );
            };

            return el(Fragment, {},
                renderInspectorControls(),
                el('div', { className: 'wp-block-ld-ninjas-timeline' },
                    renderTimelinePreview()
                )
            );
        },

        save: function() {
            // Return null because we're using PHP render callback
            return null;
        }
    });

})();
