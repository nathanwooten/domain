<?php

namespace websiteproject\Container\Services\nathanwootenstandardfiles;

use nathanwooten\{

  Container\ContainerInterface,
  Container\ContainerService

};

use websiteproject\{

  Registry\Registry

};

class websiteprojectregistryregistryservice extends ContainerService
{

  public array $autoloads = [
    LIB_PATH . 'websiteproject' => [
      [
        'websiteproject\Application\Application',
        'application' . DS . 'src'
      ]
    ]
  ];
  public ?string $id = Registry::class;

  public function __construct( ContainerInterface $container )
  {

    parent::__construct( $container );

  }

}
