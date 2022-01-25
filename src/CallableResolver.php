<?php

namespace nathanwooten\Application;

class CallableResolver
{

	public static function resolve( $callback, $constructorArgs = [] )
	{

		if ( is_array( $callback ) ) {

			if ( empty( $callback ) ) {
				throw new Exception( 'Empty is not a callback' );
			} else {

				$callback = array_values( $callback );

				if ( ! is_object( $callback[0] ) && is_string( $callback[0] ) ) {
					$callback[0] = new $callback[0]( ...$constructorArgs );

				}
			}
		}

		return $callback;

	}


	public static function getName( $callback )
	{

		$name = '';
		if ( is_array( $callback ) ) {

			$callback = array_values( $callback );

			$objectClass = $callback[0];

			if ( is_string( $objectClass ) ) {
				$name = $callback;
	
			} elseif ( is_object( $objectClass ) ) {

				if ( method_exists( $objectClass, 'getName' ) ) {
					$name = $objectClass->getName();

				} elseif ( method_exists( $objectClass, '__toString' ) ) {
					$name = (string) $objectClass;

				} else {
					$name = get_class( $objectClass );

				}
			} else ( 
				throw new Exception( 'getCallbackName' );
			}
		} elseif ( is_object( $callback ) ) {
			$name = get_class( $callback );

		} elseif ( is_string( $callback ) ) {
			$name = $callback;

		} else {
			throw new Exception( 'It appears to be a callback' );
		}

		return $name;

	}

}
