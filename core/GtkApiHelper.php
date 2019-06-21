<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Class GtkApiHelper
 */
class GtkApiHelper
{
	/**
	 * Retrieve the full name of the option storing the API Token retrieved from API server
	 * @return string
	 */
	public static function getApiTokenOptionName()
	{
		return GTK_TOOLKIT_PLUGIN_SLUG . GtkApi::PARTIAL_API_TOKEN_OPT_NAME;
	}

	/**
	 * Retrieve the full name of the option storing the API EMAIL retrieved from API server
	 * @return string
	 */
	public static function getApiEmailOptionName()
	{
		return GTK_TOOLKIT_PLUGIN_SLUG . GtkApi::PARTIAL_API_EMAIL_OPT_NAME;
	}

	/**
	 * Retrieve the API token (issued by the API server) from database
	 * @return string
	 */
	public static function getApiToken()
	{
		return get_option( self::getApiTokenOptionName(), '' );
	}

	/**
	 * Retrieve the API email (retrieved by the API server) from database
	 * @return string
	 */
	public static function getApiEmail()
	{
		return get_option( self::getApiEmailOptionName(), '' );
	}

	public static function getCurrentUrl() {
		return ( is_ssl() ? 'https:/' : 'http:/' ) . ltrim( getenv( 'REQUEST_URI' ), '/' );
	}
}
