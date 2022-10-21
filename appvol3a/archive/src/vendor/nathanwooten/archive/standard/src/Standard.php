<?php

namespace nathanwooten\Standard;

if ( ! defined( 'LIB_PATH' ) || ! defined( 'PROJECT_NAME' ) ) die( __FILE__ . '::' . __LINE__ );

use nathanwooten\{

  Autoloader

};

if ( ! class_exists( 'nathanwooten\Standard\Standard' ) ) {
class Standard implements StandardInterface
{

  protected $loadName = 'loadnathanwooten';
  protected $container = PROJECT_NAME . '\\' . 'Container' . '\\' . 'Container';

  public $autoloads = [
    LIB_PATH . 'nathanwooten' => [
      [
        'nathanwooten\Standard',
        'standard' . DS . 'src'
      ],
      [
        'nathanwooten\Container',
        'container' . DS . 'src'
      ]
    ],
    LIB_PATH . PROJECT_NAME => [
      [
        PROJECT_NAME . '\\' . 'Container',
        'container' . DS . 'src'
      ]
    ]
  ];

  public function __construct()
  {

    $this->container = $this->load();

  }

  public function getContainer()
  {

    return $this->container;

  }

  protected function load()
  {

    $standardLoad = $this->loadName;

    return $this->$standardLoad();

  }

  protected function loadnathanwooten()
  {

    Autoloader::autoload( $this->autoloads );

    $container = new $this->container;
	return $container;

  }

}
}
