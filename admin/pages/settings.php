<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}
?>
<div class="wrap gtk-toolkit-page-wrap">
	<h2><?php esc_html_e( 'Settings', 'geomettric-toolkit' ); ?></h2>

	<p>Display the status: connected/not connected to api server</p>
	<p>If not connected: display the form</p>
	<p>If connected: display the "Connected" message of some sort + the disconnect button</p>

	<p>Display the clear cache button to refresh the cached response from api server</p>

	<p>Other settings here...</p>
</div>
