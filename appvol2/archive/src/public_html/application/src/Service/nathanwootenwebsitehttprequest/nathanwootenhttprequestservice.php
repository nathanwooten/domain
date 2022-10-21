<?php

namespace nathanwooten\Website\Service\nathanwootenhttprequest;

use nathanwooten\{

  Website\ContainerInterface,
  Website\ApplicationService

};

use nathanwooten\{

  Http\Request,
  Http\Uri

};

class nathanwootenhttprequestservice extends ApplicationService
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
