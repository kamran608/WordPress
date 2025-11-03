# WordPress Gutenberg Course Progress Block

This project integrates a Gutenberg block for displaying course progress using the `display_course_progress` shortcode from the LearnDash Course Progress plugin.

## Installation

1. Clone the repository or download the ZIP file.
2. Extract the files to your WordPress plugins directory: `wp-content/plugins/`.
3. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

1. After activating the plugin, navigate to the Gutenberg editor in WordPress.
2. Add a new block by clicking the "+" icon.
3. Search for "Course Progress" and select the block.
4. Configure the block settings in the sidebar, including the Course ID and User ID.
5. Save or publish your post/page to see the course progress displayed.

## Development

- The block is registered in the `includes/register-block.php` file.
- The block's JavaScript and CSS files are located in the `assets/js/` and `assets/css/` directories, respectively.
- The shortcode functionality is defined in `src/frontend.php`.

## Localization

The plugin is translation-ready. The `.pot` file is located in the `languages/` directory for easy localization.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.

## License

This project is licensed under the GPLv2 or later.