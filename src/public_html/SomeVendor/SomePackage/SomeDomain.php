<?php

namespace SomeVendor\SomePackage;

class SomeDomain
{

  protected $path;
  protected $basespace = [ 'SomeDomain' ];
  protected $map = [];

  protected array $container = [];

  public function __construct( $domain, $name = null )
  {

    $this->path = $domain;

    if ( $name ) {
      if ( ! is_array( $name ) ) {
        $name = [ $name ];
      }
      foreach ( $name as $nm ) {
        $this->basespace[] = $nm;
      }
    }

    $this->map( $this->map );

    spl_autoload_register( [ $this, 'autoload' ], true, true );

  }

}
