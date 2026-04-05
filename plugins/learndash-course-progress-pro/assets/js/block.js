const { registerBlockType } = wp.blocks;
const { createElement, Fragment, useEffect, useState } = wp.element;
const { TextControl, PanelBody, Spinner } = wp.components;
const { InspectorControls } = wp.blockEditor;

registerBlockType('lpc-course-progress/course-progress', {
    title: 'SWR Course Progress',
    icon: 'chart-bar',
    category: 'widgets',
    attributes: {
        courseId: {
            type: 'number',
            default: 0,
        },
        userId: {
            type: 'number',
            default: 0,
        },
    },
    edit: (props) => {
        const {
            attributes: { courseId, userId },
            setAttributes
        } = props;

        const [previewHtml, setPreviewHtml] = useState('');
        const [loading, setLoading] = useState(false);

        // Watch for changes in courseId and userId
        useEffect(() => {

            const formData = new FormData();
            formData.append('action', 'lpc_render_shortcode');
            formData.append('course_id', courseId);
            formData.append('user_id', userId);

            setLoading(true);

            fetch(window.ajaxurl, {
                method: 'POST',
                body: formData,
            })
                .then((res) => res.text())
                .then((html) => {
                    setPreviewHtml(html);
                    setLoading(false);
                })
                .catch(() => {
                    setPreviewHtml('<strong>Error loading preview.</strong>');
                    setLoading(false);
                });

        }, [courseId, userId]);

        return createElement(Fragment, null,
            createElement(InspectorControls, null,
                createElement(PanelBody, { title: 'Settings', initialOpen: true },
                    createElement(TextControl, {
                        label: 'Course ID',
                        type: 'number',
                        value: courseId || '',
                        onChange: (val) => {
                            const parsed = parseInt(val);
                            setAttributes({ courseId: isNaN(parsed) ? 0 : parsed });
                        }
                    }),
                    createElement(TextControl, {
                        label: 'User ID',
                        type: 'number',
                        value: userId || '',
                        onChange: (val) => {
                            const parsed = parseInt(val);
                            setAttributes({ userId: isNaN(parsed) ? 0 : parsed });
                        }
                    })
                )
            ),
            createElement('div', { className: 'lpc-preview-container' },
                createElement('p', null, 'Live Preview:'),
                loading
                    ? createElement(Spinner, null)
                    : createElement('div', {
                        dangerouslySetInnerHTML: { __html: previewHtml },
                        style: {
                            background: '#fff',
                            padding: '15px',
                            border: '1px solid #ddd',
                            borderRadius: '4px',
                            marginTop: '10px'
                        }
                    })
            )
        );
    },
    save: () => null
});