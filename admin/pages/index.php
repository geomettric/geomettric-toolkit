<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}
$apiServerInfo = GtkApi::getServerStatusInfo();
$up = ( $apiServerInfo['status'] == GTK_API_SERVER_STATUS_UP );
$err = $apiServerInfo['error'];
?>
<div class="wrap gtk-toolkit-page-wrap">
	<h2><?php esc_html_e( 'Dashboard', 'geomettric-toolkit' ); ?></h2>

	<div class="section server-info">
		<p>
			<strong>API Server status</strong>
			<?php
			if ( $up ) {
				echo '<strong class="apiServer apiServerUp toUpper">' . esc_html__( 'up', 'geomettric-toolkit' ) . '</strong>';
			}
			else {
				echo '<strong class="apiServer apiServerDown toUpper">' . esc_html__( 'down', 'geomettric-toolkit' ) . '</strong>';
				if ( ! empty( $err ) ) {
					echo '<strong class="apiServerDown">' . esc_html( $err ) . '</strong>';
				}
			}
			?>
		</p>
	</div>


	<p>Render statistics like: connected to API server, add button to refresh/check status, how many add-ons installed,
	   if there are any new add-ons (check response from api server), etc..</p>
</div>
