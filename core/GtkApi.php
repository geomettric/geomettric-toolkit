<?php

/**
 * Class GtkApi
 *
 * Utility class to interact with the Geomettric API Server
 */
class GtkApi
{
	/**
	 * Check the API server to see if it's up and running
	 */
	public static function healthCheck()
	{
		$cacheExpire = ( 8 * HOUR_IN_SECONDS );

		//#! Check cache first
		$sInfo = get_transient( GTK_API_SERVER_STATUS_OPTION );
		if ( ! empty( $sInfo ) ) {
			return;
		}

		$request = wp_remote_post( GTK_API_SERVER_URL, [
			'body' => json_encode( [
				'action' => 'api/check'
			] )
		] );
		if ( $request ) {
			if ( is_wp_error( $request ) ) {
				set_transient( GTK_API_SERVER_STATUS_OPTION, [
					'status' => GTK_API_SERVER_STATUS_DOWN,
					'error' => $request->get_error_message()
				], $cacheExpire );
				return;
			}

			$body = wp_remote_retrieve_body( $request );
			if ( empty( $body ) ) {
				set_transient( GTK_API_SERVER_STATUS_OPTION, [
					'status' => GTK_API_SERVER_STATUS_DOWN,
					'error' => esc_html__( 'Invalid response from the API Server.', 'geomettric-toolkit' )
				], $cacheExpire );
				return;
			}
			$responseData = json_decode( $body, true );

			//#! All good ?
			if ( $responseData['code'] == 200 ) {
				set_transient( GTK_API_SERVER_STATUS_OPTION, [
					'status' => GTK_API_SERVER_STATUS_UP,
					'error' => ''
				], $cacheExpire );
				return;
			}

			//#! Not good
			set_transient( GTK_API_SERVER_STATUS_OPTION, [
				'status' => GTK_API_SERVER_STATUS_DOWN,
				'error' => sprintf( esc_html__( 'The API Server is down. Error code: %s', 'geomettric-toolkit' ), $responseData['code'] )
			], $cacheExpire );
			return;
		}
		//#! Not good
		set_transient( GTK_API_SERVER_STATUS_OPTION, [
			'status' => GTK_API_SERVER_STATUS_DOWN,
			'error' => esc_html__( 'No response from the API Server.', 'geomettric-toolkit' )
		], $cacheExpire );
		return;
	}

	/**
	 * Retrieve the API server status info
	 * @return array
	 */
	public static function getServerStatusInfo() {
		return array_merge([
			'status' => '',
			'error' => '',
		], get_transient( GTK_API_SERVER_STATUS_OPTION ));
	}

	final public static function isConnected( $ignoreCache = false) {
		// api/is-connected (website url, user email, token)
		//...
	}

	final public static function connect(){
		// api/connect (website url, user email)
		//...
	}

	final public static function disconnect(){
		// api/disconnect (website url, user email, token)
		//...
	}
}
