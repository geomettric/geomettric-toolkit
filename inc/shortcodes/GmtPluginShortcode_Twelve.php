<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Shortcode for Column two_third
 *
 * @package WordPress
 * @subpackage Shortcodes
 * @author Geomettric Themes
 * @author uri http://geomettric.com
 */
class GmtPluginShortcode_Twelve extends GmtShortcodeAbstract
{
	public function getShortcodeName()
	{
		return 'twelve';
	}

	public function getDisplayName()
	{
		return '[Geomettric] Twelve';
	}

	public function getAtts()
	{
		return array();
	}

	public function html( $_atts, $content = '' )
	{
		$str = '<div class="col-sm-12">';
		if ( ! empty( $content ) ) {
			$str .= do_shortcode( $content );
		}
		$str .= '</div>';

		return $str;
	}
}
