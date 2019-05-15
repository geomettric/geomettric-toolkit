<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Shortcode for Separator
 */
class GmtPluginShortcode_Separator extends GmtShortcodeAbstract
{
	public function getShortcodeName()
	{
		return 'geomettric_separator';
	}

	public function getDisplayName()
	{
		return '[Geomettric] Separator';
	}

	public function getAtts()
	{
		return array(
			// type = dotted / dashed
			'type' => '',
			// text = ''
			'text' => '',
			// icon_class = '' the icon class to use to generate the separator
			'icon_class' => '',
			// align = '' left/right ; where to position the separator (text/icon). Defaults to center
			'align' => '',
		);
	}

	public function html( $_atts, $content = '' )
	{
		$atts = shortcode_atts( $this->getAtts(), $_atts );

		$type = $atts['type'];
		$text = $atts['text'];
		$iconClass = $atts['icon_class'];
		$align = $atts['align'];

		if ( '' != $type ) {
			$type = ' sep-' . $type;
		}
		else {
			$type = '';
		}

		if ( '' != $align ) {
			$align = ' symbol-' . $align;
		}


		$cssClasses = '';
		$content = '<span>';
		$hasSymbol = false;

		if ( ! empty( $text ) ) {
			$content .= $text;
			$hasSymbol = true;
		}
		if ( ! empty( $iconClass ) ) {
			$content .= ' <i class="' . esc_attr( $iconClass ) . '"></i>';
			$hasSymbol = true;
		}

		$content .= '</span>';

		if ( $hasSymbol ) {
			$cssClasses .= 'with-symbol';
		}
		$cssClasses .= $type . $align;

		return '<div class="separator ' . esc_attr( $cssClasses ) . '">' . $content . '</div>';
	}
}
