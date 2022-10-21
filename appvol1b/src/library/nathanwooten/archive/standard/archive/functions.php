<?php

use nathanwooten\{

  Registry\RegistryAbstract

};

if ( ! defined( 'PROJECT_PATH' ) ) die( __FILE__ );

if ( ! function_exists( 'classRegistry' ) ) {
function classRegistry()
{

  return getClass( PROJECT_PATH, 'Registry', 'Registry' );

}
}

if ( ! function_exists( 'getClass' ) ) {
function getClass( $vendor, $type, $class_name ) {

  $type = 'get' . $type . 'Class';

  return $type( $vendor, $class_name );

}
}

if ( ! function_exists( 'implode_class' ) ) {
function implode_class( $vendor, $type, $class_name )
{

	return implode( '\\', ...func_get_args() );

}
}

if ( ! function_exists( 'getRegistryClass' ) ) {
function getRegistryClass( $vendor = PROJECT_NAME, $class_name = 'Registry' ) {

  return implode_class( $vendor, 'Registry', $class_name );

}
}

if ( ! function_exists( 'getContainerClass' ) ) {
function getContainerClass( $vendor = PROJECT_NAME, $class_name = 'Container' )
{

  return $vendor . '\\' . 'Container' . '\\' . $class_name;

}
}

if ( ! function_exists( 'getResponse' ) ) {
function getResponse( $data, array $calls )
{

  $response = [];
  $response[] = $data;

  foreach ( $calls as $index => $callback ) {

    $result = $callback( $data );
    if ( $result !== $data ) {
      break;
    }
  }

  $callbackName = getCallbackName( $callback );

  $response[ $callbackName ] = $result;

  $calls = array_slice( $calls, 1 + $index );
  if ( ! empty( $calls ) ) {
    $response = array_merge( $response, getResponse( $result, $calls ) );
  }

  return $response;

}
}

if ( ! function_exists( 'getCallbackName' ) ) {
function getCallbackName( callable $callback )
{

  if ( is_array( $callback ) ) {
    return get_class( $callback[0] ) . '::' . $callback[1];
  }

  return (string) $callback;

}
}

if ( ! function_exists( 'getTarget' ) ) {
function getTarget( $uri = null )
{

  $registry = getRegistryClass();
  $containerClass = getContainerClass();

  $container = $registry::get( $containerClass );

  if ( ! isset( $uri ) ) {
    $uri = $container->get( Uri::class );
  }

  if ( is_string( $uri ) ) {
    $uri = $container->get( Uri::class, [ $uri ] );
  }

  $target = $uri->getTarget();

  if ( is_file( PROJECT_PATH . $target ) ) {

    $target = str_replace( basename( $target ), '', $target );
  }
  $target = trim( $target, '/' );

  return $target;

}
}

if ( ! function_exists( 'urlRelative' ) ) {
function urlRelative( $url )
{

  $dir = '';

  $subfolder = trim( $url, '/' );

  if ( false !== strpos( $subfolder, '/' ) ) {
    $explode = explode( '/', $url );
    if ( ! empty( $explode ) ) {
      foreach ( $explode as $exp ) {
        $dir = '../' . $dir;
      }
    }
  }

  return $dir;

}
}

if ( ! function_exists( 'getName' ) ) {
function getName( $class )
{

  $name = str_replace( '\\', '', strtolower( $class ) );
  return $name;

}
}

if ( ! function_exists( 'fsNorm' ) ) {
function fsNorm( $path, $before = '', $after = '', $separator = DIRECTORY_SEPARATOR )
{

	$path = str_replace( [ '\\', '/' ], $separator, $path );

	if ( isset( $before ) ) {
		$path = ltrim( $path, $separator );
		if ( ! empty( $before ) ) {
			$before = $separator;
			$path = $before . $path;
		}
	}

	if ( isset( $after ) ) {
		$path = rtrim( $path, $separator );
		if ( ! empty( $after ) ) {
			$after = $separator;
			$path = $path . $after;
		}
	}

	return $path;

}
}

if ( ! function_exists( 'orDefault' ) ) {
function orDefault( $object, $property, $value = null, string $getter = null )
{

  if ( isset( $value ) ) {
  } elseif ( isset( $getter ) && method_exists( $object, $getter ) ) {
    $value = $object->$getter();

  } else {
    $rProperty = new ReflectionProperty( $object, $property );
    if ( $rProperty->isPublic() ) {
      if ( isset( $object->$property ) ) {
        return $object->$property;
      }
    }
  }

}
}
