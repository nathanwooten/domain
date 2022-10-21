<?php

namespace nathanwooten\Loader;

class LoaderMethod
{

  protected LoaderService $container;
  protected $method;

  public function __construct( LoaderService $container )
  {

    $this->container = $container;
    $this->method();

  }

  protected function method()
  {

    if ( ! isset( $this->method ) ) {
      $this->method = lcfirst( str_replace( get_class( $this->container ), '', get_class( $this ) ) );

    }

    return $this->method;

  }

}
