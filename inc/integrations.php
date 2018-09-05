<?php
/**
 * Require files that deal with various plugin integrations.
 *
 * @package Patch
 */

/**
 * Load Pixelgrade Care compatibility file.
 */
require get_parent_theme_file_path( '/inc/integrations/pixcare.php' );

/**
 * Load Customify plugin configuration
 */
require get_parent_theme_file_path( '/inc/integrations/customify.php' );

/**
 * Load Jetpack compatibility file.
 */
require get_parent_theme_file_path( '/inc/integrations/jetpack.php' );