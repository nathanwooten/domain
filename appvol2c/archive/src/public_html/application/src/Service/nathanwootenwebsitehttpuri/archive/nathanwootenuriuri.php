<?php

namespace websiteproject\Container\Services;

use nathanwooten\{

  Container\ServiceContainer

};

use nathanwooten\{

  Uri\Uri

};

class nathanwootenuriuri extends ServiceContainer
{

  protected $id = Uri::class;

  protected $args = [];

  public function args( ...$args )
  {

    $args = parent::args( $args );
    if ( empty( $args ) ) {
      $args[0] = $_SERVER[ 'REQUEST_URI' ];
    }

    return $args;

  }

}
