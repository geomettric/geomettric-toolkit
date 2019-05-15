<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Shortcode for Column one_third
 *
 * @package WordPress
 * @subpackage Shortcodes
 * @author Geomettric Themes
 * @author uri http://geomettric.com
 */
class GtkPluginShortcode_OneThird extends GtkShortcodeAbstract
{
	public function getShortcodeName()
	{
		return 'one_third';
	}

	public function getDisplayName()
	{
		return '[Geomettric] One Third';
	}

	public function getAtts()
	{
		return array();
	}

	public function html( $_atts, $content = '' )
	{
		$str = '<div class="col-sm-4">';
		if ( ! empty( $content ) ) {
			$str .= do_shortcode( $content );
		}
		$str .= '</div>';

		return $str;
	}
}
