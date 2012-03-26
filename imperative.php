<?php
/**
 * Library Registration and Loader for WordPress
 *
 * @package Imperative
 * @version 0.1
 * @author Mike Schinkel <mike@newclarity.net>
 * @author Micah Wood <micah@newclarity.net>
 * @license http://opensource.org/licenses/gpl-2.0.php
 * @copyright Copyright (c) 2012, NewClarity LLC
 *
 */
define( 'IMPERATIVE_LIB', true );

if( ! function_exists( 'require_library' ) ) {
	function require_library( $filepath ) {
	  global $wp_libraries;
	  if ( ! is_array( $wp_libraries ) ) {
		  $wp_libraries = array();
	  }
	  preg_match( '#/([-0-9a-zA-Z_]+)-([0-9.]+).php$#', $filepath, $match );
	  list( $major, $minor, $bugfix ) = explode( '.', "{$match[2]}" );
	  $version =
		10000 * intval( $minor ) +
		100 * intval( $minor ) +
		intval( $bugfix );
	  $wp_libraries[$match[1]][$version] = $filepath;
	}
}

if( ! function_exists( 'load_libraries' ) ) {
	add_action( 'after_setup_theme', 'load_libraries' );
	function load_libraries() {
	  global $wp_libraries;
	  if ( is_array( $wp_libraries ) ) {
		foreach ( $wp_libraries as $library ) {
		  if ( count( $library ) > 1 )
			krsort( $library, SORT_NUMERIC );
		  if ( file_exists( $library[0] ) )
			require_once( $library[0] );
		}
	  }
	}
}
