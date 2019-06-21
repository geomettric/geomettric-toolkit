<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Button shortcode
 *
 * @package WordPress
 * @subpackage Shortcodes
 * @author Geomettric Themes
 * @author uri http://geomettric.com
 */
class GtkPluginShortcode_Button extends GtkShortcodeAbstract
{
	public function getShortcodeName()
	{
		return 'geomettric_button';
	}

	public function getDisplayName()
	{
		return '[Geomettric] Button';
	}

	public function getAtts()
	{
		return array(
			// style = primary / danger / info / success / warning / special
			'style' => '',
			// shape = '' (default) / round / semi-rounded
			'shape' => '',
			// size = extra-small / small / large
			'size' => '',
			// block = yes
			'block' => '',
			// url = #
			'url' => '#',
			// target = blank
			'target' => '',
		);
	}

	public function html( $_atts, $content = '' )
	{
		$atts = shortcode_atts( $this->getAtts(), $_atts );

		$style = $atts['style'];
		$shape = $atts['shape'];
		$size = $atts['size'];
		$block = $atts['block'];
		$url = $atts['url'];
		$target = $atts['target'];

		if ( '' != $style ) {
			$style = 'btn-' . $style;
		}
		else {
			$style = 'btn-default';
		}

		if ( '' != $shape ) {
			$shape = 'btn-' . $shape;
		}

		if ( 'extra-small' == $size ) {
			$size = 'btn-xs';
		}
		elseif ( 'small' == $size ) {
			$size = 'btn-sm';
		}
		elseif ( 'large' == $size ) {
			$size = 'btn-lg';
		}
		else {
			$size = '';
		}

		if ( 'yes' == $block ) {
			$block = 'btn-block';
		}
		else {
			$block = '';
		}


		if ( ! empty( $target ) ) {
			$target = esc_attr( $target );
		}

		if ( empty( $content ) ) {
			$content = esc_html( __( 'Button', 'geomettric-toolkit' ) );
		}

		$content = $content;
		$cssClass = "{$style} {$shape} {$size} {$block}";


		return '<a href="' . esc_url( $url ) . '" class="btn ' . esc_attr( $cssClass ) . '" title="' . esc_attr( $content ) . '" target="' . esc_attr( $target ) . '">' . $content . '</a>';
	}
}
