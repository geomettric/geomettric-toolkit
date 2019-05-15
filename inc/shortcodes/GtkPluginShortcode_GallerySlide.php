<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Shortcode for the Gallery Grid Image
 *
 * @package WordPress
 * @subpackage Shortcodes
 * @author Geomettric Themes
 * @author uri http://geomettric.com
 */
class GtkPluginShortcode_GallerySlide extends GtkShortcodeAbstract
{
	public function getShortcodeName()
	{
		return 'slide_item';
	}

	public function getDisplayName()
	{
		return '[Geomettric] Gallery Slide';
	}

	public function getAtts()
	{
		return array(
			// the ID of the thumbnail
			'id' => '',
			'class' => '',
		);
	}

	public function html( $_atts, $content = '' )
	{
		$atts = shortcode_atts( $this->getAtts(), $_atts );

		$id = (int)$atts['id'];
		$extraClass = esc_attr( $atts['class'] );

		if ( empty( $id ) ) {
			// nothing to do in this case - Image ID not provided
			return '';
		}

		$image = wp_get_attachment_image_src( $id, 'full' );
		if ( empty( $image ) || ! isset( $image[0] ) || empty( $image[0] ) ) {
			// nothing to do - the image was not found
			return '';
		}

		$alt = '';

		if( class_exists('WillowTheme')) {
			$alt = WillowTheme::getAttachmentAltText( $id );
		}
		$title = get_post( $id )->post_title;

		$str = '<div class="hn-slide swiper-slide ' . esc_attr( $extraClass ) . '">';
		$str .= '<img src="' . esc_url( $image[0] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '">';
		$str .= '</div>';

		return $str;
	}
}
