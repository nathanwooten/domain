<?php

if ( ! function_exists( 'fSNorm' ) ) {
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
public function orDefault( $object, $property, $value = null, string $getter = null )
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
