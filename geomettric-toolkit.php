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
define( 'GTK_THEME_PLUGIN', true );

define( 'GTK_PLUGIN_DIR', trailingslashit( wp_normalize_path( plugin_dir_path( __FILE__ ) ) ) );
define( 'GTK_PLUGIN_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

//#! Register the text domain
function gtk_loadPluginTextDomain()
{
	load_plugin_textdomain( 'geomettric-toolkit', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'gtk_loadPluginTextDomain' );

//#! Enqueue styles
add_action( 'admin_enqueue_scripts', 'gtk_enqueueScripts' );
add_action( 'wp_enqueue_scripts', 'gtk_enqueueScripts' );
function gtk_enqueueScripts()
{
	wp_enqueue_style( 'gtk-plugin-styles', GTK_PLUGIN_URI . 'styles.css' );
}

//#! Load core files so others(themes|plugins) can use them
require_once( GTK_PLUGIN_DIR . 'core/GtkUtil.php' );
require_once( GTK_PLUGIN_DIR . 'core/GtkAddonAbstract.php' );
require_once( GTK_PLUGIN_DIR . 'core/GtkShortcodeAbstract.php' );
require_once( GTK_PLUGIN_DIR . 'core/GtkShortcodesManager.php' );
require_once( GTK_PLUGIN_DIR . 'core/addons/scripts-combine/GtkScriptsCombine.php' );
