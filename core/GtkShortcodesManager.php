<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Base class to load all shortcodes
 *
 * Standard Singleton
 *
 * @package WordPress
 * @subpackage Plugins
 * @author Geomettric Themes
 * @author uri http://geomettric.com
 */
class GtkShortcodesManager
{
	private static $_instance = null;

	/**
	 * Stores the list of all instantiated shortcodes. Associated array: array( 'display_name' => 'The display name of the shortcode', 'atts' => [ the list of attributes ] )
	 * @var array
	 */
	private $_shortcodes = array();

	/**
	 * Stores the list of all registered paths
	 * @var array
	 */
	private $_paths = array();

	private function __construct()
	{
	}

	public static function getInstance()
	{
		if ( ! self::$_instance || ! self::$_instance instanceof self ) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	 * Set the path to the directory where to search for shortcodes
	 * @param string $path
	 * @return bool Boolean TRUE if the path was added, FALSE if the path already exists or is not a path
	 */
	public function registerPath( $path )
	{
		if ( ! is_dir( $path ) || in_array( $path, $this->_paths ) ) {
			return false;
		}
		$this->_paths[] = $path;
		return true;
	}

	/**
	 * Load the specified shortcode if not already loaded
	 * @param string $filePath The path where the shortcode exists
	 */
	public function load( $filePath )
	{
		$this->__loadShortcode( $filePath );
	}

	/**
	 * Load all shortcodes found in the registered paths.
	 */
	public function loadAll()
	{
		if ( ! empty( $this->_paths ) ) {
			foreach ( $this->_paths as $path ) {
				$rii = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path ) );
				foreach ( $rii as $file ) {
					if ( $file->isDir() ) {
						continue;
					}
					//#! Load the shortcode
					$this->__loadShortcode( $file->getPathname() );
				}
			}
		}
	}

	/**
	 * Retrieve the list of all registered shortcodes
	 * @return array
	 */
	public function getRegistered()
	{
		return $this->_shortcodes;
	}

	/**
	 * Internal helper method to load a shortcode
	 * @param string $filePath
	 */
	private function __loadShortcode( $filePath )
	{
		$className = basename( $filePath, '.php' );
		if ( ! class_exists( "$className" ) ) {
			require_once( $filePath );
		}

		/**
		 * @var GtkShortcodeAbstract $shClass
		 */
		$shClass = new $className;
		if ( $shClass instanceof GtkShortcodeAbstract ) {
			$shName = $shClass->getShortcodeName();

			//#! Skip, if the shortcode was already loaded
			if ( isset( $this->_shortcodes[$shName] ) ) {
				return;
			}

			//#! Cache to the internal list
			$shDisplayName = $shClass->getDisplayName();
			$shAtts = $shClass->getAtts();

			$this->_shortcodes[$shName] = array(
				'display_name' => $shDisplayName,
				'atts' => $shAtts,
			);

			//#! Register the shortcode
			add_shortcode( $shName, array( $shClass, 'html' ) );
		}
	}

}
