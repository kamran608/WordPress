// This file handles the block's editor-specific JavaScript functionality, such as updating the block preview in the editor.

document.addEventListener('DOMContentLoaded', function() {
    const courseProgressBlock = document.querySelector('.wp-block-gutenberg-course-progress');

    if (courseProgressBlock) {
        // Example of how to update the block preview
        const courseIdInput = courseProgressBlock.querySelector('.course-id-input');
        const userIdInput = courseProgressBlock.querySelector('.user-id-input');
        const previewContainer = courseProgressBlock.querySelector('.preview-container');

        function updatePreview() {
            const courseId = courseIdInput.value;
            const userId = userIdInput.value;

            // Fetch the shortcode output via AJAX or render it directly
            previewContainer.innerHTML = `[display_course_progress course_id="${courseId}" user_id="${userId}"]`;
        }

        courseIdInput.addEventListener('input', updatePreview);
        userIdInput.addEventListener('input', updatePreview);
    }
});