<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Shortcode for Column three_fourth
 *
 * @package WordPress
 * @subpackage Shortcodes
 * @author Geomettric Themes
 * @author uri http://geomettric.com
 */
class GtkPluginShortcode_ThreeFourth extends GtkShortcodeAbstract
{
	public function getShortcodeName()
	{
		return 'three_fourth';
	}

	public function getDisplayName()
	{
		return '[Geomettric] Three Fourth';
	}

	public function getAtts()
	{
		return array();
	}

	public function html( $_atts, $content = '' )
	{
		$str = '<div class="col-sm-9">';
		if ( ! empty( $content ) ) {
			$str .= do_shortcode( $content );
		}
		$str .= '</div>';

		return $str;
	}
}
