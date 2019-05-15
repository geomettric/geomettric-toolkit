<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( '[Geomettric Toolkit] You are not allowed to access this page.' );
}

//#! To minify, min PHP version required to be at least 5.4


/**
 * Class GtkScriptsCombine
 *
 * Provides an easy way to load and combine (and minify using the JShrink class) multiple scripts or stylesheets into a
 * single file that will be automatically included in the website.
 *
 * Author       Geomettric Themes
 * Author URI   http://geomettric.com
 * License      GPL v3
 * @version     1.0
 * @uses        \JSHrink\Minifier
 * @see         https://github.com/tedious/JShrink
 */
class GtkScriptsCombine
{
	/**
	 * Indicates whether or not the '.js' file extension should be used for the output cache file
	 * @type int
	 */
	const TYPE_JS = 0;

	/**
	 * Indicates whether or not the '.css' file extension should be used for the output cache file
	 * @type int
	 */
	const TYPE_CSS = 1;

	/**
	 * Holds the system path to the output directory
	 * @see __construct()
	 * @private
	 * @var string
	 */
	private $_saveDirPath = '';

	/**
	 * Holds the HTTP path to the output directory
	 * @see __construct()
	 * @private
	 * @var string
	 */
	private $_saveDirUrl = '';

	/**
	 * Whether or not to minify the output. Defaults to true.
	 * @see __construct()
	 * @private
	 * @var bool
	 */
	private $_minify = true;

	/**
	 * Constructor
	 *
	 * @param bool|true $minify Whether or not to minify the output
	 *
	 * @throws Exception
	 */
	function __construct( $minify = true )
	{
		$wpUploads = wp_upload_dir();

		$this->_saveDirPath = trailingslashit( $wpUploads['basedir'] ) . 'gm-cache/';
		$this->_saveDirUrl = trailingslashit( $wpUploads['baseurl'] ) . 'gm-cache/';

		if ( ! is_dir( $this->_saveDirPath ) ) {
			// Try to create the directory
			$result = wp_mkdir_p( $this->_saveDirPath );
			if ( ! $result ) {
				throw new Exception( __METHOD__ . "() Error: Directory {$this->_saveDirPath} could not be found." );
			}
		}
		if ( ! is_readable( $this->_saveDirPath ) || ! is_writable( $this->_saveDirPath ) ) {
			throw new Exception( __METHOD__ . "() Error: Directory {$this->_saveDirPath} is not accessible." );
		}

		$this->_minify = $minify;
		if ( $minify ) {
			//#! To minify, we require PHP version to be at least 5.4
			if ( version_compare( phpversion(), '5.4', '=>' ) ) {
				require_once( 'JShrink/Minifier.php' );
			}
			else {
				$this->_minify = false;
			}
		}
	}

	/**
	 * Combine and enqueue the specified list of scripts
	 * @param array $files The list of files to combine
	 * @param string $id The script ID
	 * @param array $dependencies The list of dependencies. Ex: array('jquery', 'jquery-ui');
	 * @param null $baseDirPath The base directory path where to search for scripts.
	 * @param bool $inFooter Whether or not to include the output file in the page footer. Defaults to false.
	 */
	public function combineJS( array $files, $id, array $dependencies = array(), $baseDirPath = null, $inFooter = false )
	{
		if ( ! empty( $files ) && ! empty( $baseDirPath ) ) {
			$scriptFilePath = $this->_cacheGet( $files, self::TYPE_JS, $baseDirPath );
			wp_enqueue_script( $id, $scriptFilePath, $dependencies, false, $inFooter );
		}
	}

	/**
	 * Combine and enqueue the specified list of stylesheets
	 * @param array $files The list of files to combine
	 * @param string $id The script ID
	 * @param array $dependencies The list of dependencies. Ex: array('main_css', 'template_css');
	 * @param null $baseDirPath The base directory path where to search for stylesheets.
	 */
	public function combineCSS( array $files, $id, array $dependencies = array(), $baseDirPath = null )
	{
		if ( ! empty( $files ) && ! empty( $baseDirPath ) ) {
			$scriptFilePath = $this->_cacheGet( $files, self::TYPE_CSS, $baseDirPath );
			wp_enqueue_style( $id, $scriptFilePath, $dependencies );
		}
	}

	/**
	 * Clear the cache directory
	 *
	 * @final
	 * @public
	 */
	final public function clearCache()
	{
		$exclude = array( '.', '..', '.htaccess' );
		foreach ( new DirectoryIterator( $this->_saveDirPath ) as $fileInfo ) {
			if ( ! in_array( $fileInfo->getFilename(), $exclude ) ) {
				@unlink( $fileInfo->getRealPath() );
			}
		}
	}

	/**
	 * Create and retrieve the path to the cache file holding the combined scripts/stylesheets
	 * @param array $files The list of scripts to combine
	 * @param int $type The file extension to use for output file. 0 - js, 1 -css
	 * @param int $baseDirPath The system path to the directory where to search for resources.
	 * @return string
	 */
	private function _cacheGet( $files, $type = self::TYPE_JS, $baseDirPath = null )
	{
		if ( empty( $files ) || empty( $baseDirPath ) || ! is_dir( $baseDirPath ) ) {
			return ''; // 'empty data';
		}

		$baseDirPath = trailingslashit( $baseDirPath );

		// Set the file type
		$fileType = ( $type == self::TYPE_JS ) ? '.js' : '.css';
		$closeScript = ( $type == self::TYPE_JS ) ? ';' : '';

		// Create the name of the cache file
		$cacheFnMD5 = md5( implode( ';', $files ) );

		// Cache found
		$cacheFilePath = $this->_saveDirPath . $cacheFnMD5 . $fileType;
		$cacheFileUrl = $this->_saveDirUrl . $cacheFnMD5 . $fileType;
		if ( is_file( $cacheFilePath ) ) {
			return $cacheFileUrl;
		}

		// Try to create the local cache file
		file_put_contents( $cacheFilePath, '' );
		if ( ! is_file( $cacheFilePath ) ) {
			return ''; // error: could not create cache file:
		}

		$output = '';

		// Collect files
		foreach ( $files as $file ) {
			$filePath = $baseDirPath . $file;
			if ( empty( $file ) || ! is_file( $filePath ) ) {
				// empty file or not a valid file path
				continue;
			}
			// If we have a valid path, get the content of the script file
			$content = file_get_contents( $filePath );
			if ( ! empty( $content ) ) {
				if ( $this->_minify ) {
					try {
						$content = \JShrink\Minifier::minify( $content );
					}
					catch ( Exception $e ) {
						error_log( '[Geomettric Toolkit][jShrink] An error occurred while trying to minify the content: ' . $e->getMessage() );
					}
				}
				$contentLength = strlen( $content );
				// Check to see whether or not the script was closed properly
				if ( ! empty( $closeScript ) ) {
					if ( $content[$contentLength - 1] != ';' ) {
						$content .= $closeScript;
					}
				}
				$output .= '/*' . basename( $filePath ) . '*/' . $content;
			}
		}

		if ( ! empty( $output ) ) {
			file_put_contents( $cacheFilePath, $output );
		}

		return $cacheFileUrl;
	}
}

