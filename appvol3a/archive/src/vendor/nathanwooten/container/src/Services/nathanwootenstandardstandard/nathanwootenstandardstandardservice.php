<?php

namespace websiteproject\Container\Services\nathanwootenstandardstandard;

use nathanwooten\{

  Container\ContainerInterface,
  Container\ContainerService

};

use nathanwooten\{

  Standard\Standard

};

class nathanwootenstandardstandardservice extends ContainerService
{

  public array $args = [];
  public ?string $id = Standard::class;

  public function __construct( ContainerInterface $container )
  {

    parent::__construct( $container );

  }

}
