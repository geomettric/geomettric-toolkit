<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Gallery Grid shortcode
 *
 * @package WordPress
 * @subpackage Shortcodes
 * @author Geomettric Themes
 * @author uri http://geomettric.com
 */
class GtkPluginShortcode_GalleryGrid extends GtkShortcodeAbstract
{
	public function getShortcodeName()
	{
		return 'gallery_grid';
	}

	public function getDisplayName()
	{
		return '[Geomettric] Gallery Grid';
	}

	// The shortcode's default attributes
	public function getAtts()
	{
		return array(
			'cols' => 0,
		);
	}

	public function html( $_atts = null, $content = '' )
	{
		$atts = shortcode_atts( $this->getAtts(), $_atts );

		$cols = (int)$atts['cols'];
		if ( ! empty( $cols ) && $cols > 0 && $cols <= 3 ) {
			$cols = 'gallery-grid--' . $cols;
		}
		else {
			$cols = '';
		}

		if ( ! empty( $content ) ) {
			return '<div class="gallery-grid ' . esc_attr( $cols ) . '">' . do_shortcode( $content ) . '</div>';
		}
		return '';
	}
}
