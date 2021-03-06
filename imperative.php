<?php
/**
 * Library Registration and Loader for WordPress
 *
 * @package Imperative
 * @version 1.0
 * @filesource https://github.com/getsunrise/imperative
 * @author Mike Schinkel <mike@newclarity.net>
 * @author Micah Wood <micah@newclarity.net>
 * @license http://opensource.org/licenses/gpl-2.0.php
 * @copyright Copyright (c) 2012, NewClarity LLC
 */

if( ! defined( 'IMPERATIVE_LIB' ) ) {

	function require_library( $filepath ) {
	  global $wp_libraries;
	  if ( ! is_array( $wp_libraries ) )
		$wp_libraries = array();
	  preg_match( '#/([-0-9a-zA-Z_]+)-([0-9.]+)\.php$#', $filepath, $match );
	  list( $major, $minor, $bugfix ) = explode( '.', "{$match[2]}.0.0" );
	  $version =
		10000 * intval( $major ) +
		100 * intval( $minor ) +
		intval( $bugfix );
	  $wp_libraries[$match[1]][$version] = $filepath;
	}

	add_action( 'after_setup_theme', 'load_libraries' );

	function load_libraries() {
	  global $wp_libraries;
	  if ( is_array( $wp_libraries ) ) {
		foreach ( $wp_libraries as $library ) {
		  if ( count( $library ) > 1 )
			  krsort( $library, SORT_NUMERIC );
		  $versions = array_values( $library );
		  if ( file_exists( $versions[0] ) )
			require_once( $versions[0] );
		}
	  }
	}

}

define( 'IMPERATIVE_LIB', true );
