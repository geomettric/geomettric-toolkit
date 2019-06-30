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
		add_filter( 'jpeg_quality', [ __CLASS__, '__enhanceJpegQuality' ] );
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
		add_filter( 'style_loader_src', [ __CLASS__, '__removeQueryStringFromStaticResources' ], 10, 2 );
		add_filter( 'script_loader_src', [ __CLASS__, '__removeQueryStringFromStaticResources' ], 10, 2 );
	}

	/**
	 * Remove the query string from static resources for a page load speed boost.
	 * @internal
	 *
	 * @param string $src
	 *
	 * @return string
	 */
	public static function __removeQueryStringFromStaticResources( $src = '' )
	{
		if ( strpos( $src, '?ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		return $src;
	}

	/**
	 * Retrieve the IP address
	 * @return string
	 */
	public static function getUserIpAddr()
	{
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			//ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			//ip pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else {
			$ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0' );
		}
		return $ip;
	}

	/**
	 * Render the post thumbnail
	 *
	 * @param string $imageSize
	 * @param int|0 $postID
	 * @param bool $linkImage
	 */
	public static function render_post_thumbnail( $imageSize, $postID = 0, $linkImage = false )
	{
		if ( post_password_required() || is_attachment() ) {
			return;
		}
		// Get the post ID
		if ( empty( $postID ) ) {
			$postID = get_the_ID();
		}
		if ( $postID && has_post_thumbnail( $postID ) ) {
			$thumbID = get_post_thumbnail_id( $postID );
			$post = get_post( $thumbID );
			$thumbTitle = ( $post && isset( $post->post_title ) ? esc_attr( $post->post_title ) : '' );

			if ( $linkImage ) {
				echo '<a href="' . esc_attr( get_permalink( $postID ) ) . '" class="gtk-post-thumbnail" aria-hidden="true">';
			}
			echo wp_get_attachment_image( $thumbID, $imageSize, false, [
				'alt' => self::getAttachmentAltText( $thumbID ),
				'title' => $thumbTitle,
			] );
			if ( $linkImage ) {
				echo '</a>';
			}
		}
	}

	public static function getAttachmentAltText( $imageID )
	{
		return get_post_meta( $imageID, '_wp_attachment_image_alt', true );
	}

}
