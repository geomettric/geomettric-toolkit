<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Class GtkUtil
 *
 * Utility class providing helper methods
 */
class GtkUtil
{
	/**
	 * Add support for shortcodes in the Text widget
	 */
	public static function widgetDoShortcode()
	{
		add_filter( 'widget_text', 'do_shortcode' );
	}

	/**
	 * Improves the jpeg image quality WordPress uses for jpeg images by raising it to 100
	 * @uses __enhanceJpegQuality()
	 */
	public static function enhanceJpegQuality()
	{
		add_filter( 'jpeg_quality', array( __CLASS__, '__enhanceJpegQuality' ) );
	}

	/**
	 * Internal method to set the jpeg quality
	 * @internal
	 * @return int 100
	 */
	public static function __enhanceJpegQuality()
	{
		return 100;
	}

	/**
	 * Remove the query string from static resources for a page load speed boost.
	 * @uses __removeQueryStringFromStaticResources()
	 */
	public static function removeQueryStringFromStaticResources()
	{
		add_filter( 'style_loader_src', array( __CLASS__, '__removeQueryStringFromStaticResources' ), 10, 2 );
		add_filter( 'script_loader_src', array( __CLASS__, '__removeQueryStringFromStaticResources' ), 10, 2 );
	}

	/**
	 * Remove the query string from static resources for a page load speed boost.
	 * @internal
	 * @param string $src
	 * @return string
	 */
	function __removeQueryStringFromStaticResources( $src = '' )
	{
		if ( strpos( $src, '?ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		return $src;
	}
}
