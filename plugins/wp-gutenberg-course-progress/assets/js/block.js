const { registerBlockType } = wp.blocks;

registerBlockType('lpc-course-progress/course-progress', {
    title: 'LPC Course Progress',
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
        // Block editor logic
    },
    save: () => {
        // Save logic (if applicable)
        return null;
    },
});