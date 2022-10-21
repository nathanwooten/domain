<?php

namespace chrislocalmoving\Container\Services\nathanwootenhttpuri;

use nathanwooten\{

  Container\ContainerInterface,
  Container\ContainerService

};

use nathanwooten\{

  Http\Uri

};

class nathanwootenhttpuriservice extends ContainerService
{

  public array $autoloads = [
    LIB_PATH . 'nathanwooten' => [
      [
        'nathanwooten\Http',
        'http' . DS . 'src'
      ]
    ]
  ];
  public ?string $id = Uri::class;  

  public function __construct( ContainerInterface $container )
  {

    parent::__construct( $container );

  }

  public function args( array $args = null )
  {

    if ( ! $args ) {
      $args = [];

      $args[0] = $_SERVER[ 'REQUEST_URI' ];
    }

    return $args;

  }

}
