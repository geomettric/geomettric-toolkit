<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}


add_action( 'widgets_init', function () {
	register_widget( 'Gtk_WC_SortBy' );
} );

/**
 * Class Gtk_WC_SortBy
 */
class Gtk_WC_SortBy extends WP_Widget
{

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct()
	{
		$widget_ops = [
			'classname' => 'Gtk_WC_SortBy',
			'description' => 'Sort products by various options',
		];

		parent::__construct( 'gtk_wc_sortby', '[GTK] Products Sort By', $widget_ops );
	}


	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance )
	{
		// outputs the content of the widget
		echo \Brixton\Helpers\Util::out( $args['before_widget'] );
		if ( ! empty( $instance['title'] ) ) {
			echo \Brixton\Helpers\Util::out( $args['before_title'] ) . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$shopPageUrl = get_permalink( get_option( 'woocommerce_shop_page_id' ) );
		?>
		<ul>
			<li>
				<a href="<?php echo esc_attr( $shopPageUrl ); ?>"><?php esc_html_e( 'None', 'geomettric-toolkit' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_attr( $shopPageUrl ); ?>?orderby=popularity"><?php esc_html_e( 'Popularity', 'geomettric-toolkit' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_attr( $shopPageUrl ); ?>?orderby=rating"><?php esc_html_e( 'Average rating', 'geomettric-toolkit' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_attr( $shopPageUrl ); ?>?orderby=date"><?php esc_html_e( 'Newness', 'geomettric-toolkit' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_attr( $shopPageUrl ); ?>?orderby=price"><?php esc_html_e( 'Price: low to high', 'geomettric-toolkit' ); ?></a>
			</li>
			<li>
				<a href="<?php echo esc_attr( $shopPageUrl ); ?>?orderby=price-desc"><?php esc_html_e( 'Price: high to low', 'geomettric-toolkit' ); ?></a>
			</li>
		</ul>
		<?php
		echo \Brixton\Helpers\Util::out( $args['after_widget'] );
	}


	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance )
	{
		// outputs the options form on admin
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Sort By', 'geomettric-toolkit' );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}


	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance )
	{
		// processes widget options to be saved
		foreach ( $new_instance as $key => $value ) {
			$updated_instance[$key] = sanitize_text_field( $value );
		}

		return $updated_instance;
	}
}
