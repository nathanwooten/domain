<?php

namespace nathanwooten\Application;

if ( ! defined( 'LIB_PATH' ) ) die( __FILE__ . ' ' . __LINE__ );



class ApplicationFactory extends FactoryAbstract
{

  protected $id;

  protected static $properties = [
    'container' => [
      'path' => null
    ],
    'request' => null
  ];

  public function container( $container = null, $args = [] )
  {

    return $this->property( 'container', $container, $args );

  }

  public function request( $request = null, $args = [] )
  {

    return $this->property( 'request', $request, $args );

  }

}
