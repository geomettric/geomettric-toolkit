<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Shortcode for Row
 *
 * @package WordPress
 * @subpackage Shortcodes
 * @author Geomettric Themes
 * @author uri http://geomettric.com
 */
class GmtPluginShortcode_Row extends GmtShortcodeAbstract
{
	public function getShortcodeName()
	{
		return 'row';
	}

	public function getDisplayName()
	{
		return '[Geomettric] Row';
	}

	// The shortcode's default attributes
	public function getAtts()
	{
		return array();
	}

	public function html( $_atts = null, $content = '' )
	{
		$str = '';
		if ( ! empty( $content ) ) {
			$str = '<div class="row">';
			$str .= do_shortcode( $content );
			$str .= '</div>';
		}
		return $str;
	}
}
