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

		add_action( 'wp_body_open', [ __CLASS__, 'injectMarkup' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'loadFrontendScripts' ] );

		if ( 'enabled-from-customizer' ) {
			add_action( 'wp_enqueue_scripts', [ __CLASS__, 'loadAjaxJS' ] );
			add_action( 'wp_ajax_gtk_ajax_search', [ get_class(), 'ajaxSearch' ] );
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
		wp_enqueue_script( 'gtk-ajax-search', GTK_PLUGIN_URI . 'res/js/ajax-search.js', [ 'jquery' ] );
	}

	/**
	 * Load the actual script that enables the ajax search, since this functionality is optional
	 */
	public static function loadAjaxJS()
	{
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
	 * Inject the markup for the search overlay
	 */
	public static function injectMarkup()
	{
		?>
		<!-- Search -->
		<div class="bx-search u-hidden">
			<a href="#" class="bx-search__close ui-btn-icon ui-btn-icon--lg js-close-search" title="<?php esc_attr_e( 'Close', 'geomettric-toolkit' ); ?>">
				<span class="icon-close"></span>
			</a>
			<div class="bx-search__layout">
				<div class="container">

					<?php get_search_form(); ?>

					<!-- min 3 chars -->
					<div class="bx-search-results">
						<div class="bx-search-results__top"><!-- Renders the results number --></div>
						<div class="bx-search-results__wrapper"><!-- Render the results --></div>
						<div class="bx-search-results__notices"><!-- <p>Render notices</p> --></div>
					</div>
				</div>
			</div>
			<?php wp_nonce_field( GTK_NONCE_ACTION, GTK_NONCE_NAME ); ?>
		</div>
		<!-- #end Search -->
		<?php
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

			//#! Query for all post types
			$query = sprintf( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_title LIKE '%%%s%%' OR post_content LIKE '%%%s%%' AND post_status = 'publish' AND post_password = '' LIMIT 0,4", $searchTerm, $searchTerm );
			$results = $wpdb->get_results( $query );
			if ( $results ) {
				ob_start();
				foreach ( $results as $row ) {
					$postID = $row->ID;
					$postTitle = $row->post_title;
					?>
					<div class="item">
						<?php
						//#! Image
						GtkUtil::render_post_thumbnail( 'thumbnail', $postID );

						//#! Title
						echo '<h3><a href="' . esc_attr( get_permalink( $postID ) ) . '">' . $postTitle . '</a></h3>';
						?>
					</div>
					<?php
				}
				$html = ob_get_contents();
				ob_end_clean();
				$output['html'] = '<div class="search-results-wrap">' . $html . '</div><div><a href="' . get_bloginfo( 'url' ) . '?s=' . esc_attr( $searchTerm ) . '">' . esc_html__( 'View all', 'geomettric-toolkit' ) . '</a></div>';
				$output['count'] = count( $results );
				wp_send_json_success( $output );
			}
		}
	}
}
