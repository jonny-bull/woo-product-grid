<?php
/**
 * WooProductGrid class.
 *
 * Handles the PHP side of the plugin - largely, registering JS and CSS assets.
 *
 * @package WooProductGrid
 */

declare( strict_types=1 );

namespace WooProductGrid;

use WC_Product_Query;

/**
 * WooProductGrid class.
 */
class WooProductGrid {
	/**
	 * Set up constants.
	 */
	private const BUILD_FOLDER = 'js/build/';
	private const ASSET_FILE = 'index.asset.php';
	private const BUILD_FILE = 'index.js';
	private const REGISTER_NAME = 'woo-product-grid';
	private const REGISTER_STYLE_NAME = 'woo-product-grid-style';

	/**
	 * Init method to define hooks.
	 *
	 * @return void
	 */
	public function init() {
		// Initialise and load our assets for the block editor.
		add_action( 'init', [ $this, 'register_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_script' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_style' ] );
		// Load our assets for the front end.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_style' ] );
		// Add our custom block category.
		add_filter( 'block_categories_all', [ $this, 'add_custom_block_category' ] );
	}

	/**
	 * Register our block editor and styleguide scripts.
	 *
	 * @return void
	 */
	public function register_assets(): void {
		// Get the URL and path for the build folder for use in registering and getting the filemtime.
		$folder_path = plugin_dir_path( __DIR__ ) . self::BUILD_FOLDER;
		$folder_url = plugin_dir_url( __DIR__ ) . self::BUILD_FOLDER;
		$build_url = $folder_url . self::BUILD_FILE;
		$asset_path = $folder_path . self::ASSET_FILE;

		if ( ! file_exists( $asset_path ) ) {
			return;
		}

		$script_asset = require_once $asset_path;
		$editor_ver = strval( filemtime( $folder_path . self::BUILD_FILE ) );

		// Register the built JS file for use in Block Editor.
		wp_register_script(
			self::REGISTER_NAME,
			$build_url,
			$script_asset['dependencies'],
			$editor_ver,
			true
		);

		// Register the layout block here so we can hijack render_callback.
		// Attributes also need to be registered here so they are passed through to the callback.
		register_block_type(
			'woo-product-grid/product-grid-layout',
			[
				'render_callback' => [ $this, 'render_block' ],
				'attributes' => [
					'productsPerRow' => [
						'type' => 'number',
						'default' => 3,
					],
					'productsToShow' => [
						'type' => 'number',
						'default' => 9,
					],
					'productOffset' => [
						'type' => 'number',
						'default' => 0,
					],
					'productOrder' => [
						'type' => 'string',
						'default' => 'date',
					],
					'productOrderDirection' => [
						'type' => 'string',
						'default' => 'DESC',
					],
				],
			]
		);
	}

	/**
	 * Load our block editor script.
	 *
	 * @return void
	 */
	public function enqueue_script(): void {
		wp_enqueue_script( self::REGISTER_NAME );
	}

	/**
	 * Load our block CSS.
	 * This applies to the front end and Block Editor.
	 *
	 * @return void
	 */
	public function enqueue_style(): void {
		$style_url = plugin_dir_url( __DIR__ ) . 'css/woo-product-grid.css';
		$style_path = plugin_dir_path( __DIR__ ) . 'css/woo-product-grid.css';

		wp_enqueue_style(
			self::REGISTER_STYLE_NAME,
			$style_url,
			[],
			strval( filemtime( $style_path ) ),
			'all'
		);
	}

	/**
	 * Adds a custom 'WooCommerce product grid' category for our custom block.
	 *
	 * @param mixed[] $categories The registered block categories.
	 * @return mixed[] The amended categories.
	 */
	public function add_custom_block_category( array $categories ): array {
		$categories[] = [
			'slug' => 'woo-product-grid',
			'title' => 'WooCommerce product grid',
		];

		return $categories;
	}

	/**
	 * Renders a single product given the data passed as parameters.
	 *
	 * @param string $product_title Title of the product.
	 * @param string $product_price Price of the product.
	 * @param string $product_img Featured image for the product.
	 * @return string The above parameters compiled into a single <div>.
	 */
	public function render_single_product( string $product_title, string $product_price, string $product_img ): string {
		return '<div class="woo-product-grid__item">' . $product_img . '
			<h2 class="woo-product-grid__item-title">' . $product_title . '</h2>
			<p class="woo-product-grid__item-price">' . $product_price . '</p>
		</div>';
	}

	/**
	 * Queries WooCommerce and returns the appropriate product data.
	 *
	 * @param mixed[] $block_attributes Attributes for the block.
	 * @return string Rendered string for the block.
	 */
	public function render_block( array $block_attributes ): string {
		$render_string = '<div class="woo-product-grid__layout woo-product-grid__layout--grid-'
			. $block_attributes['productsPerRow'] . '">';
		$product_count = 0;

		$product_query = new WC_Product_Query(
			[
				'limit' => $block_attributes['productsToShow'],
				'status' => [ 'publish' ],
				'offset' => $block_attributes['productOffset'],
				'orderby' => $block_attributes['productOrder'],
				'order' => $block_attributes['productOrderDirection'],
			],
		);

		$products = $product_query->get_products();

		if ( 0 === count( $products ) ) {
			return '<div class="woo-product-grid__layout"><p>Sorry, no products found.</p></div>';
		}

		foreach ( $products as $product ) {
			$render_string .= $this->render_single_product(
				$product->get_name(),
				$product->get_price_html(),
				$product->get_image()
			);
			$product_count++;
		}

		$render_string .= '</div>';

		return $render_string;
	}
}
