<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Class GtkApi
 *
 * Utility class to interact with the Geomettric API Server
 */
class GtkApi
{
	/**
	 * Holds the url to the API server
	 * @var string
	 */
	const SERVER_URL = 'https://customers.geomettric.com/';

	/**
	 * Holds the url to the customers dashboard register page
	 * @var string
	 */
	const DASH_REGISTER_URL = 'https://customers.geomettric.com/dashboard/register';


	/**
	 * Holds the list of the default fields that will be sent with the request to the API server
	 * @var array
	 */
	private static $_defaultRequestFields = [
		'website_url' => '',
		'api_token' => '',
		'client_email' => '',
		'referrer' => '',
	];

	/**
	 * Holds the partial name of the option storing the API Token retrieved from API server
	 * @var string
	 * @see getApiTokenOptionName()
	 */
	const PARTIAL_API_TOKEN_OPT_NAME = '_api_token';
	/**
	 * Holds the partial name of the option storing the API EMAIL address retrieved from API server
	 * @var string
	 * @see getApiEmailOptionName()
	 */
	const PARTIAL_API_EMAIL_OPT_NAME = '_api_email';

	/**
	 * Initialize the class' default functionality
	 */
	public static function init()
	{
		self::$_defaultRequestFields = wp_parse_args( [
			'website_url' => get_bloginfo( 'url' ),
			'api_token' => GtkApiHelper::getApiToken(),
			'client_email' => GtkApiHelper::getApiEmail(),
			'referrer' => GtkApiHelper::getCurrentUrl(),
		], self::$_defaultRequestFields );

		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'loadAdminScripts' ] );
		add_action( 'wp_ajax_gtk_toolkit_connect', [ __CLASS__, 'connect' ] );
	}


	public static function loadAdminScripts()
	{
		//#! Addons & plugins that need these scripts can register their SLUG using this filter
		$pageHooks = apply_filters( 'geomettric-toolkit/api/load-admin-scripts/query-slugs', [ GTK_TOOLKIT_PLUGIN_SLUG ] );

		//#! Make sure we'll only load in pages that require these resources
		$filtered = array_filter( $pageHooks, function ( $hk ) {
			return ( false !== stripos( getenv( 'REQUEST_URI' ), $hk ) );
		} );

		if ( empty( $filtered ) ) {
			return;
		}
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'gtk-api-js', GTK_PLUGIN_URI . 'admin/res/js/api.js', [ 'jquery' ] );
		wp_localize_script( 'gtk-api-js', 'GtkApiLocale', [
			'ajax' => [
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce_name' => GTK_NONCE_NAME,
				'nonce_value' => wp_create_nonce( GTK_NONCE_ACTION ),
			],
		] );
	}


	/**
	 * Check the API server to see if it's up and running
	 */
	public static function healthCheck()
	{
		$cacheExpire = ( 8 * HOUR_IN_SECONDS );

		//#! Check cache first
		$sInfo = get_transient( GTK_API_SERVER_STATUS_OPTION );
		if ( ! empty( $sInfo ) ) {
			return;
		}

		$request = wp_remote_post( self::SERVER_URL, [
			'body' => json_encode( [
				'action' => 'api/check'
			] )
		] );
		if ( $request ) {
			if ( is_wp_error( $request ) ) {
				set_transient( GTK_API_SERVER_STATUS_OPTION, [
					'status' => GTK_API_SERVER_STATUS_DOWN,
					'error' => $request->get_error_message()
				], $cacheExpire );
				return;
			}

			$body = wp_remote_retrieve_body( $request );
			if ( empty( $body ) ) {
				set_transient( GTK_API_SERVER_STATUS_OPTION, [
					'status' => GTK_API_SERVER_STATUS_DOWN,
					'error' => esc_html__( 'Invalid response from the API Server.', 'geomettric-toolkit' )
				], $cacheExpire );
				return;
			}
			$responseData = json_decode( $body, true );

			//#! All good ?
			if ( $responseData['code'] == 200 ) {
				set_transient( GTK_API_SERVER_STATUS_OPTION, [
					'status' => GTK_API_SERVER_STATUS_UP,
					'error' => ''
				], $cacheExpire );
				return;
			}

			//#! Not good
			set_transient( GTK_API_SERVER_STATUS_OPTION, [
				'status' => GTK_API_SERVER_STATUS_DOWN,
				'error' => sprintf( esc_html__( 'The API Server is down. Error code: %s', 'geomettric-toolkit' ), $responseData['code'] )
			], $cacheExpire );
			return;
		}
		//#! Not good
		set_transient( GTK_API_SERVER_STATUS_OPTION, [
			'status' => GTK_API_SERVER_STATUS_DOWN,
			'error' => esc_html__( 'No response from the API Server.', 'geomettric-toolkit' )
		], $cacheExpire );
		return;
	}

	/**
	 * Retrieve the API server status info
	 * @return array
	 */
	public static function getServerStatusInfo()
	{
		$t = get_transient( GTK_API_SERVER_STATUS_OPTION );
		if ( empty( $t ) || ! is_array( $t ) ) {
			$t = [];
		}
		return array_merge( [
			'status' => '',
			'error' => '',
		], $t );
	}

	//#! TODO
	public static function isConnected( $ignoreCache = false )
	{
		// api/is-connected (website url, user email, token)
		//...
	}

	//#! TODO
	public static function connect()
	{
		if ( 'POST' == strtoupper( getenv( 'REQUEST_METHOD' ) ) ) {
			if ( ! isset( $_POST[GTK_NONCE_NAME] ) || ! wp_verify_nonce( $_POST[GTK_NONCE_NAME], GTK_NONCE_ACTION ) ) {
				wp_send_json_error( esc_html__( 'The nonce is missing or not valid. Please refresh the page and try again.', 'geomettric-toolkit' ) );
			}

		}
	}

	//#! TODO
	private static function __connect()
	{
		// api/connect (website url, user email)
		//...
	}

	//#! TODO
	public static function disconnect()
	{
		// api/disconnect (website url, user email, token)
		//...
	}


	public static function getApiRegisterUrl()
	{
		return self::DASH_REGISTER_URL . '?info=' . base64_encode( json_encode( self::$_defaultRequestFields ) );
	}
}
