<?php

namespace nathanwooten\Registry;

if ( ! class_exists( 'nathanwooten\Registry\RegistryAbstract' ) ) {
abstract class RegistryAbstract
{

  protected static array $registry = [];
  protected static array $properties = [];

  public static function set( $id, $service, array $properties = [] )
  {

    if ( ! isset( static::$registry[ $id ] ) ) {
      static::$registry[ $id ] = [];
    }

    $index = count( static::$registry[ $id ] );

    static::$registry[ $id ][ $index ] = $service;
    static::$properties[ $id ] [ $index ] = $properties;

  }

  public static function get( $id, $properties = [] )
  {

    $registry = static::$registry;

    if ( array_key_exists( $id, $registry ) ) {
      $block = $registry[ $id ];

      if ( ! class_exists( $id ) ) {
        return $block;
      }

      foreach ( $block as $index => $item ) {

        $match = true;
        foreach ( $properties as $property_name => $value ) {
          if ( ! isset( static::$properties[ $index ][ $property_name ] ) || static::$properties[ $index ][ $property_name ] !== $value ) {
            $match = false;
          }
        }
        if ( $match ) {
          return $item;
        }
      }
    }

  }

}
}
