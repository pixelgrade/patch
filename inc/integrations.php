<?php
/**
 * Require files that deal with various plugin integrations.
 *
 * @package Patch
 */

/**
 * Load Pixelgrade Care compatibility file.
 */
require_once get_parent_theme_file_path( '/inc/integrations/pixelgrade-care.php' );

/**
 * Load Customify plugin configuration
 */
require_once get_parent_theme_file_path( '/inc/integrations/customify.php' );

/**
 * Load Jetpack compatibility file.
 */
require_once get_parent_theme_file_path( '/inc/integrations/jetpack.php' );

/**
 * Admin Dashboard logic.
 */
require_once trailingslashit( get_template_directory() ) . 'inc/admin/admin.php'; // phpcs:ignore
