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
class GtkPluginShortcode_GalleryGridImage extends GtkShortcodeAbstract
{
	private $_xValidPos = array(
		'left', 'center', 'right'
	);
	private $_yValidPos = array(
		'top', 'center', 'bottom'
	);


	public function getShortcodeName()
	{
		return 'grid_image';
	}

	public function getDisplayName()
	{
		return '[Geomettric] Gallery Grid Image';
	}

	public function getAtts()
	{
		return array(
			// the ID of the thumbnail
			'id' => '',
			// the width for the thumbnail
			'width' => '1',
			// the height for the thumbnail
			'height' => '1',
			// the horizontal background-position for the thumbnail: left, center, right
			'x' => 'center',
			// the vertical background-position for the thumbnail: top, center, bottom
			'y' => 'center',
		);
	}

	public function html( $_atts, $content = '' )
	{
		$atts = shortcode_atts( $this->getAtts(), $_atts );

		$id = (int)$atts['id'];
		$width = (int)$atts['width'];
		$height = (int)$atts['height'];
		$xPos = $atts['x'];
		$yPos = $atts['y'];

		if ( empty( $id ) ) {
			// nothing to do in this case - Image ID not provided
			return '';
		}

		$extraClass = '';
		if ( ! empty( $width ) && ( (int)$width == 2 ) ) {
			$extraClass .= ' grid-item--w2';
		}
		if ( ! empty( $height ) && ( (int)$height == 2 ) ) {
			$extraClass .= ' grid-item--h2';
		}
		if ( ! empty( $xPos ) ) {
			if ( ! in_array( $xPos, $this->_xValidPos ) ) {
				$xPos = 'center';
			}
		}
		if ( ! empty( $yPos ) ) {
			if ( ! in_array( $yPos, $this->_yValidPos ) ) {
				$yPos = 'center';
			}
		}

		$image = wp_get_attachment_image_src( $id, 'full' );
		if ( empty( $image ) || ! isset( $image[0] ) || empty( $image[0] ) ) {
			// nothing to do - the image was not found
			return '';
		}

		$str = '<a href="#" class="grid-item ' . esc_attr( $extraClass ) . '">';
		$str .= '<span class="grid-item-bkg"';
		$str .= ' style="background-image: url(' . esc_url( $image[0] ) . ');';
		$str .= ' background-position: ' . esc_attr( $xPos ) . ' ' . esc_attr( $yPos ) . ';">';
		$str .= '</span>';
		$str .= '</a>';

		return $str;
	}
}
