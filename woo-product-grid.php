<?php
/**
 * WooProductGrid plugin for WordPress
 * PHP version 8.0
 *
 * @category  TechTest
 * @package   WooProductGrid
 * @author    Jonathan Bull <jonathan.bull084@gmail.com>
 * @copyright 2023 Jonathan Bull
 * @license   GPL v3 or later
 * @link      https://github.com/jonny-bull/woo-product-grid
 *
 * Plugin Name:  WooProductGrid
 * Description:  Displays WooCommerce products in the Block Editor.
 * Version:      1.0.0
 * Plugin URI:   https://github.com/jonny-bull/woo-product-grid
 * Author:       Jonathan Bull
 * Author URI:   https://github.com/jonny-bull
 * Text Domain:  woo-product-grid
 * Requires PHP: 8.0+
 */

declare( strict_types=1 );

use WooProductGrid\WooProductGrid;

$autoload_file = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

if ( is_readable( $autoload_file ) ) {
	require_once $autoload_file;
}

add_action(
	'plugins_loaded',
	static function() {
		if ( false === class_exists( 'WooCommerce' ) ) {
			return;
		}

		$product_grid = new WooProductGrid();
		$product_grid->init();
	}
);
