<?php

namespace nathanwooten\Standard;

class StandardService
{

  protected static $registry;

  public function __construct()
  {

    $this->registry = $registry;

  }

  public static getRegistry()
  {

    return $this->registry;

  }

  public function getInstance()
  {

    $fga = func_get_args();

    if ( ! isset( static::$instance ) || is_a( static::$instance, static::class ) {

      if ( empty( $fga ) ) {
        throw new Exception( 'This object requires one parameter, ' . __CLASS__ );
      }

      static::$instance = new static( ...$fga );
    }

    return static::$instance;

  }

}
