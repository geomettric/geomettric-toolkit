<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Class GtkAjaxSearch
 *
 * This class enables the Ajax Search functionality in the theme.
 */
class GtkAjaxSearch
{
	/**
	 * Initialize the search functionality
	 */
	public static function init()
	{
		add_filter( 'brixton/site-header/icons', [ __CLASS__, 'addLabelIcon' ], 20 );
		add_filter( 'brixton/nav-drawer/icons', [ __CLASS__, 'addLabelIcon' ], 20 );

		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'loadFrontendScripts' ] );

		if ( 'enabled-from-customizer' ) {
			add_action( 'wp_enqueue_scripts', [ __CLASS__, 'loadAjaxJS' ] );
			add_action( 'wp_ajax_gtk_ajax_search', [ get_class(), 'ajaxSearch' ] );
			add_action( 'wp_ajax_nopriv_gtk_ajax_search', [ get_class(), 'ajaxSearch' ] );
		}
	}

	/**
	 * Adds the search icon and label for header and drawer
	 *
	 * @param array $icons
	 *
	 * @hooked filter 'brixton/site-header/icons'
	 * @hooked filter 'brixton/nav-drawer/icons'
	 * @return array
	 */
	public static function addLabelIcon( array $icons = [] )
	{
		$icons['search'] = [
			'icon' => apply_filters( 'brixton/site-header/icons/search', sprintf( self::getHeaderIconSearch(), 16, 16 ) ),
			'label' => esc_html__( 'Search', 'brixton' ),
		];
		return $icons;
	}

	/**
	 * Retrieve the icon to display in header
	 * @return string
	 */
	public static function getHeaderIconSearch()
	{
		return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="%s" height="%s"><path fill="currentColor" d="M15.6 14.8l-2.9-3c1.1-1.2 1.8-2.9 1.8-4.7 0-3.9-3.2-7.1-7.1-7.1-3.9 0-7 3.2-7 7.1s3.2 7.1 7 7.1c1.4 0 2.7-.4 3.8-1.1l3 3c.1-.1 1.4-1.3 1.4-1.3zM2.3 7.1c0-2.8 2.3-5.2 5.2-5.2 2.8 0 5.2 2.3 5.2 5.2s-2.3 5.2-5.2 5.2c-2.9-.1-5.2-2.4-5.2-5.2z"/></svg>';
	}

	/**
	 * Load the scripts in frontend
	 */
	public static function loadFrontendScripts()
	{
		wp_enqueue_script( 'jquery' );
	}

	/**
	 * Load the actual script that enables the ajax search, since this functionality is optional
	 */
	public static function loadAjaxJS()
	{
		wp_enqueue_style( 'gtk-ajax-search-styles', GTK_PLUGIN_URI . 'res/css/ajax-search.css' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'gtk-ajax-search-init', GTK_PLUGIN_URI . 'res/js/ajax-search-init.js', [ 'jquery' ] );
		wp_localize_script( 'gtk-ajax-search-init', 'GtkAjaxSearchLocale', [
			'ajax' => [
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce_name' => GTK_NONCE_NAME,
				'nonce_value' => wp_create_nonce( GTK_NONCE_ACTION ),
				//
				'text' => [
					'no_response' => esc_html__( 'No response from server.', 'geomettric-toolkit' ),
					'invalid_response' => esc_html__( 'Invalid response from server.', 'geomettric-toolkit' ),
					'no_results' => esc_html__( 'No results.', 'geomettric-toolkit' ),
					'results_count' => esc_html__( 'Number of results:', 'geomettric-toolkit' ),
				],
			],
		] );
	}


	/**
	 * Respond to ajax request
	 * @hooked action "wp_ajax_gtk_ajax_search"
	 */
	public static function ajaxSearch()
	{
		if ( 'POST' == strtoupper( getenv( 'REQUEST_METHOD' ) ) ) {
			if ( ! isset( $_POST[GTK_NONCE_NAME] ) || ! wp_verify_nonce( $_POST[GTK_NONCE_NAME], GTK_NONCE_ACTION ) ) {
				wp_send_json_error( esc_html__( 'Nonce is missing or not valid.', 'geomettric-toolkit' ) );
			}
			if ( ! isset( $_POST['search'] ) || empty( $_POST['search'] ) ) {
				wp_send_json_error( esc_html__( 'Please search for something', 'geomettric-toolkit' ) );
			}
			$searchTerm = wp_strip_all_tags( wp_kses( $_POST['search'], [] ) );
			if ( empty( $searchTerm ) ) {
				wp_send_json_error( esc_html__( 'Please search for something', 'geomettric-toolkit' ) );
			}

			$output = [
				'html' => '',
				'count' => 0,
			];

			//#! Do search:
			//#! TODO: Check the option where to search: blog, woocommerce, both....
			global $wpdb;

			//#! TODO: SET IN CUSTOMIZER
			$searchIn = 'product'; // 'post', 'product'

			//!#--------------
			//#! Query for all posts
			$query = sprintf( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = '%s' AND post_status = 'publish' AND post_password = '' AND (post_title LIKE '%%%s%%' OR post_content LIKE '%%%s%%') LIMIT 0,4", $searchIn, $searchTerm, $searchTerm );

			$results = $wpdb->get_results( $query );
			if ( $results ) {
				ob_start();
				if ( 'product' == $searchIn ) {
					echo '<ul class="products columns-4">';
				}
				foreach ( $results as $row ) {
					$postID = $row->ID;
					$postTitle = $row->post_title;
					if ( 'post' == $searchIn ) {
						?>
						<div class="gtk-search-item">
							<a href="<?php echo esc_attr( get_permalink( $postID ) ); ?>"></a>
							<?php
							//#! Image
							echo '<div class="gtk-search-item__image">';
							GtkUtil::render_post_thumbnail( 'gtk-search-post-thumbnail', $postID );
							echo '</div>';
							//#! Title
							echo '<h2 class="gtk-search-item__title">' . $postTitle . '</h2>';
							?>
						</div>
						<?php
					}
					elseif ( 'product' == $searchIn ) {
						$product = new WC_Product( $postID );
						$hptc = ( has_post_thumbnail( $postID ) ? 'has-post-thumbnail' : '' );
						$ptc = 'product-type-' . $product->get_type();
						?>
						<li class="product type-product post-<?php echo esc_attr( $postID ); ?> <?php echo esc_attr( $hptc ); ?> <?php echo esc_attr( $ptc ); ?> ">
							<div class="product__layout">
								<a class="product__link" href="<?php echo esc_attr( get_permalink( $postID ) ); ?>"></a>
								<?php
								if ( has_post_thumbnail( $postID ) ) {
									?>
									<div class="product__image">
										<div class="product__image-holder">
											<?php echo get_the_post_thumbnail( $postID, 'gtk-search-product-thumbnail' ); ?>
										</div>
									</div>
									<?php
								}
								?>
								<div class="product__content">
									<h2 class="woocommerce-loop-product__title"><?php echo get_the_title( $postID ); ?></h2>
									<?php echo $product->get_price_html();?>
								</div>
							</div>
						</li>
						<?php
					}
				}
				if ( 'product' == $searchIn ) {
					echo '</ul>';
				}
				$html = ob_get_contents();
				ob_end_clean();

				if ( 'post' == $searchIn ) {
					$output['html'] = '<div class="gtk-ajax-search-results__list">' . $html . '</div><div class="gtk-ajax-search-results__more"><a href="' . get_bloginfo( 'url' ) . '?s=' . esc_attr( $searchTerm ) . '&post_type=post">' . esc_html__( 'View all', 'geomettric-toolkit' ) . '</a></div>';
				}
				elseif ( 'product' == $searchIn ) {
					$output['html'] = '<div class="gtk-ajax-search-results__list">' . $html . '</div><div class="gtk-ajax-search-results__more"><a href="' . get_bloginfo( 'url' ) . '?s=' . esc_attr( $searchTerm ) . '&post_type=product">' . esc_html__( 'View all', 'geomettric-toolkit' ) . '</a></div>';}

				$output['count'] = count( $results );
				wp_send_json_success( $output );
			}
		}
	}
}
