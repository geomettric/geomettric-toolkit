<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}
/*
 * Plugin Name: Geomettric Toolkit
 * Description: The plugin accompanying all Geomettric Themes. This plugin, by itself, does nothing, the themes and other add-ons/plugins are responsible for integrating/extending/overriding its functionality.
 * Author: kos
 * Text Domain: geomettric-toolkit
 * Domain Path: /languages
 */
define( 'GMT_THEME_PLUGIN', true );

define( 'GMT_PLUGIN_DIR', trailingslashit( wp_normalize_path( plugin_dir_path( __FILE__ ) ) ) );
define( 'GMT_PLUGIN_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

//#! Register the text domain
function gmt_loadPluginTextDomain()
{
	load_plugin_textdomain( 'geomettric-toolkit', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'gmt_loadPluginTextDomain' );


//#! Load core files so others can use them
require_once( GMT_PLUGIN_DIR . 'core/GmtUtil.php');
require_once( GMT_PLUGIN_DIR . 'core/GmtAddonAbstract.php');
require_once( GMT_PLUGIN_DIR . 'core/GmtShortcodeAbstract.php');
require_once( GMT_PLUGIN_DIR . 'core/GmtShortcodesManager.php');
require_once( GMT_PLUGIN_DIR . 'core/addons/scripts-combine/GmtScriptsCombine.php');
