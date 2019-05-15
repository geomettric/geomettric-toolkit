<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Shortcode for Column one_sixth
 *
 * @package WordPress
 * @subpackage Shortcodes
 * @author Geomettric Themes
 * @author uri http://geomettric.com
 */
class GmtPluginShortcode_OneSixth extends GmtShortcodeAbstract
{
	public function getShortcodeName()
	{
		return 'one_sixth';
	}

	public function getDisplayName()
	{
		return '[Geomettric] One Sixth';
	}

	public function getAtts()
	{
		return array();
	}

	public function html( $_atts, $content = '' )
	{
		$str = '<div class="col-sm-2">';
		if ( ! empty( $content ) ) {
			$str .= do_shortcode( $content );
		}
		$str .= '</div>';

		return $str;
	}
}
