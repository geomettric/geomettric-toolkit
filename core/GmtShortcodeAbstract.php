<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

/**
 * Class GmtShortcodeAbstract
 *
 * This is the base class for all shortcodes
 *
 * @abstract
 */
abstract class GmtShortcodeAbstract
{
	abstract function getShortcodeName();
	abstract function getDisplayName();
	abstract function getAtts();
	abstract function html( $_atts, $content = '' );
}
