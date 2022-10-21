<?php

namespace websiteproject\Container\Services\nathanwootenhttprequest;

use nathanwooten\{

  Container\ContainerInterface,
  Container\ContainerService

};

use nathanwooten\{

  Http\Request,
  Http\Uri

};

class nathanwootenhttprequestservice extends ContainerService
{

  public array $args = [ 'uri' => Uri::class ];
  public array $autoloads = [
    LIB_PATH . 'nathanwooten' => [
      [
        'nathanwooten\Http',
        'http' . DS . 'src' 
      ]
    ]
  ];
  public ?string $id = Request::class;

  public function __construct( ContainerInterface $container )
  {

    parent::__construct( $container );

  }

}
