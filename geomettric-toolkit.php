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
define( 'GTK_TOOLKIT_PLUGIN', true );

define( 'GTK_TOOLKIT_PLUGIN_SLUG', 'geomettric_toolkit' );
define( 'GTK_PLUGIN_DIR', trailingslashit( wp_normalize_path( plugin_dir_path( __FILE__ ) ) ) );
define( 'GTK_PLUGIN_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

//#!
define( 'GTK_NONCE_NAME', 'gtk_security' );
define( 'GTK_NONCE_ACTION', 'gtk_ajax_action' );

//#! API
define( 'GTK_API_SERVER_URL', 'http://localhost/themeforest/api-server' );
define( 'GTK_API_SERVER_STATUS_UP', 'up' );
define( 'GTK_API_SERVER_STATUS_DOWN', 'down' );
// transient
define( 'GTK_API_SERVER_STATUS_OPTION', 'gtk_api_server_status' );
//#! Holds the website's status info: connected/not connected, etc
define( 'GTK_API_WEBSITE_INFO_OPTION', 'gtk_api_website_info' );

//#! Load core files so others(themes|plugins) can use them
require_once( GTK_PLUGIN_DIR . 'core/GtkUtil.php' );
require_once( GTK_PLUGIN_DIR . 'core/GtkApi.php' );
require_once( GTK_PLUGIN_DIR . 'core/GtkApiHelper.php' );
require_once( GTK_PLUGIN_DIR . 'core/GtkAddonAbstract.php' );
require_once( GTK_PLUGIN_DIR . 'core/GtkShortcodeAbstract.php' );
require_once( GTK_PLUGIN_DIR . 'core/GtkShortcodesManager.php' );
require_once( GTK_PLUGIN_DIR . 'core/GtkAjaxSearch.php' );
require_once( GTK_PLUGIN_DIR . 'core/addons/scripts-combine/GtkScriptsCombine.php' );

GtkApi::init();


//#! Register the text domain
add_action( 'plugins_loaded', 'gtk_loadPluginTextDomain' );
function gtk_loadPluginTextDomain()
{
	load_plugin_textdomain( 'geomettric-toolkit', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}


//#! Enqueue styles
add_action( 'admin_enqueue_scripts', 'gtk_enqueueScriptsBackend' );
add_action( 'wp_enqueue_scripts', 'gtk_enqueueScriptsFrontend' );
function gtk_enqueueScriptsFrontend()
{
	wp_enqueue_style( 'gtk-plugin-styles', GTK_PLUGIN_URI . 'res/css/frontend.css' );
	wp_enqueue_style( 'gtk-plugin-styles', GTK_PLUGIN_URI . 'res/css/shared.css' );
}

function gtk_enqueueScriptsBackend()
{
	wp_enqueue_style( 'gtk-plugin-styles', GTK_PLUGIN_URI . 'res/css/backend.css' );
	wp_enqueue_style( 'gtk-plugin-styles', GTK_PLUGIN_URI . 'res/css/shared.css' );
}

add_action( 'admin_init', 'gtk_apiServer_checkStatus' );
function gtk_apiServer_checkStatus()
{
	//#! Check the API server status
	GtkApi::healthCheck();
}

/*
 * Create the admin menu
 */
add_action( 'admin_menu', 'gtk_createAdminMenu' );
function gtk_createAdminMenu()
{
	$title = esc_html__( 'Geomettric Toolkit', 'geomettric-toolkit' );
	add_menu_page( $title, $title, 'manage_options', GTK_TOOLKIT_PLUGIN_SLUG, 'gtk_adminMenu_renderPageDashboard' );
	$title = esc_html__( 'Dashboard', 'geomettric-toolkit' );
	add_submenu_page( GTK_TOOLKIT_PLUGIN_SLUG, $title, $title, 'manage_options', GTK_TOOLKIT_PLUGIN_SLUG, 'gtk_adminMenu_renderPageDashboard' );
	$title = esc_html__( 'Add-ons', 'geomettric-toolkit' );
	add_submenu_page( GTK_TOOLKIT_PLUGIN_SLUG, $title, $title, 'manage_options', GTK_TOOLKIT_PLUGIN_SLUG . '_addons', 'gtk_adminMenu_renderPageAddons' );
	$title = esc_html__( 'Settings', 'geomettric-toolkit' );
	add_submenu_page( GTK_TOOLKIT_PLUGIN_SLUG, $title, $title, 'manage_options', GTK_TOOLKIT_PLUGIN_SLUG . '_settings', 'gtk_adminMenu_renderPageSettings' );
}

function gtk_adminMenu_renderPageDashboard()
{
	require_once( GTK_PLUGIN_DIR . 'admin/pages/index.php' );
}

function gtk_adminMenu_renderPageAddons()
{
	require_once( GTK_PLUGIN_DIR . 'admin/pages/addons.php' );
}

function gtk_adminMenu_renderPageSettings()
{
	require_once( GTK_PLUGIN_DIR . 'admin/pages/settings.php' );
}


/*
 * Add image sizes
 */
add_action( 'after_setup_theme', 'gtk_image_sizes' );
function gtk_image_sizes()
{
	add_image_size( 'gtk-search-post-thumbnail', 300, 300 );
	add_image_size( 'gtk-search-product-thumbnail', 300, 400 );
}

/*
 * TODO: ADD CUSTOMIZER OPTION TO ENABLE/DISABLE/CUSTOMIZE STYLE
 * TODO: ADD OPTION TO SEARCH IN: BLOG, WOOCOMMERCE, BOTH
 */
GtkAjaxSearch::init();

