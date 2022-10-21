<?php

use nathanwooten\{

  Registry\RegistryAbstract

};

if ( ! defined( 'PROJECT_PATH' ) ) die( __FILE__ );

if ( ! function_exists( 'includeScanExt' ) ) {
function includeScanExt( string $readable_dir, string $ext )
{

  $result = [];

  $readable_dir = fsNorm( $readable_dir, null, DS, DS );

  if ( ! is_readable( $readable_dir ) ) {
    throw new Exception( 'Unreadable directory ' . __FILE__ . ' ' . __FUNCTION__ );
  }

  $scan = scandir( $readable );
  foreach ( $scan as $item ) {
    if ( is_file( $readable_dir . $item ) && '.lib.php' === substr( $item, strpos( '.' ) ) ) {
      $file = $readable_dir . $item;

      $result[ $item ] = require $file;
    }

  }

  return $result;

}
}

if ( ! function_exists( 'className' ) ) {
function className( $class )
{

  $name = str_replace( '\\', '', strtolower( $class ) );
  return $name;

}
}

if ( ! function_exists( 'classContainer' ) ) {
function classContainer()
{

  return getClass( PROJECT_PATH, 'Container', 'Container' );

}
}

if ( ! function_exists( 'classRegistry' ) ) {
function classRegistry()
{

  return getClass( PROJECT_PATH, 'Registry', 'Registry' );

}
}

if ( ! function_exists( 'getClass' ) ) {
function getClass( $vendor, $type, $class_name ) {

  return implode_class( $vendor, $type, $class_name );

}
}

if ( ! function_exists( 'implode_class' ) ) {
function implode_class( $vendor, $type, $class_name )
{

	return implode( '\\', ...func_get_args() );

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

if ( ! function_exists( 'valueOrProperty' ) ) {
function valueOrProperty( $object, $property, $value = null, string $getter = null )
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

if ( ! function_exists( 'getDirname' ) ) {
function getDirname( $dir, int $count )
{

  while( $count ) {
    --$count;

    $dir = dirname( $dir );
  }

  return $dir;

}
}
