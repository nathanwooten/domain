<?php

namespace websiteproject\Application;

if ( ! defined( 'LIB_PATH' ) ) die( __FILE__ . ' ' . __LINE__ );

use websiteproject\{

  Container\Container

};

class ApplicationFactory extends FactoryAbstract
{

  protected $id;

  protected static $properties = [
    Container::class
  ];

  public function function __construct()
  {

    parent::__construct();

  }

  public function container( $container = null, $args = [] )
  {

    return $this->property( 'container', $container, $args );

  }

}
