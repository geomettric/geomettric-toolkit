<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Class GtkAddonAbstract
 *
 * THis is the base class for all add-ons
 *
 * @abstract
 */
abstract class GtkAddonAbstract
{
	/**
	 * The name of the option storing the lsit of all enabled addons
	 */
	const ENABLED_ADDONS_OPT_NAME = 'geomettric-toolkit-addons-enabled';

	final public function __construct()
	{
		add_action( 'geomettric-toolkit/addons/enable', [ $this, 'register' ], 10, 2 );
	}

	/**
	 * @param string $className
	 * @param GtkAddonAbstract $instance
	 */
	final public function enable( $className, GtkAddonAbstract $instance )
	{
		$addons = self::getEnabledAddons();
		if ( ! isset( $addons[$className] ) ) {
			$addons[$className] = $instance;
		}
		update_option( self::ENABLED_ADDONS_OPT_NAME, $addons );
	}

	/**
	 * Retrieve the list of all enabled addons
	 * @return array
	 */
	final public static function getEnabledAddons()
	{
		return get_option( self::ENABLED_ADDONS_OPT_NAME, [] );
	}
}
