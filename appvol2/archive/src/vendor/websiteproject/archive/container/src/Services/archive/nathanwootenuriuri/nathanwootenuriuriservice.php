<?php

namespace websiteproject\Container\Services\nathanwootenuriuri;

use nathanwooten\{

  Container\Container,
  Container\ServiceContainer

};

use nathanwooten\{

  Uri\Uri

};

class nathanwootenuriuriservice extends ServiceContainer
{

  protected $id = Uri::class;
  protected $args = [];

  protected $load = [ 'nathanwooten\Uri', PROJECT_PATH . 'local' . DS . 'nathanwooten' . DS . 'uri' . DS . 'src' ];

  public function __construct( Container $container )
  {

    parent::__construct( $container );

  }

  public function args( $args )
  {

    $args = parent::args( $args );

    if ( empty( $args ) ) {
      $args[0] = $_SERVER[ 'REQUEST_URI' ];
    }

    return $args;

  }

}
