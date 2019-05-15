<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Gallery Slider shortcode
 *
 * @package WordPress
 * @subpackage Shortcodes
 * @author Geomettric Themes
 * @author uri http://geomettric.com
 */
class GtkPluginShortcode_GallerySlider extends GtkShortcodeAbstract
{
	public function getShortcodeName()
	{
		return 'gallery_slider';
	}

	public function getDisplayName()
	{
		return '[Geomettric] Gallery Slider';
	}

	// The shortcode's default attributes
	public function getAtts()
	{
		return array(
			// Whether or not to allow autoplay
			'autoplay' => 0,
			// Extra class to apply to the slider
			'class' => '',
		);
	}

	public function html( $_atts = null, $content = '' )
	{
		$atts = shortcode_atts( $this->getAtts(), $_atts );

		$autoplay = (int)$atts['autoplay'];

		$str = '';
		if ( ! empty( $content ) ) {
			$str = '<div class="hn-slider gallery-slider ' . esc_attr( $atts['class'] ) . '" data-swiper-autoplay="'.esc_attr($autoplay).'">';

			$str .= '<div class="hn-sliderWrapper swiper-wrapper">';
			$str .= do_shortcode( $content );
			$str .= '</div>';

			$str .= '<div class="hn-slider__nav hn-slider__nav--sides">';
				$str .= '<div class="hn-slider__navArrow --prev swiper-button-prev">';
						$str .= '<span class="hn-icon hn-icon--arrow-long-left"></span>';
				$str .= '</div>';
				$str .= '<div class="hn-slider__navArrow --next swiper-button-next">';
					$str .= '<span class="hn-icon hn-icon--arrow-long-right"></span>';
				$str .= '</div>';
			$str .= '</div>';

			$str .= '</div>';
		}
		return $str;
	}
}
