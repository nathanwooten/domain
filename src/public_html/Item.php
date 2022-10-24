<?php

namespace nathanwooten;

use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

class Item
{

  protected $application;
  protected $basename;

  public function __construct( $domain, $basename )
  {

    $this->domain = $domain;
    $this->name = $basename;

    $this->domain->manual( $this->name );

  }

  public function load( Args $args, ...$injection )
  {




  }

  public function withArgs( ...$args )
  {

    $with = [];

    foreach ( $args as $key => $value ) {
      $with[ $this->reflection[ ReflectionParameter::class ][ $key ]->getName() ] = $value;
    }

    return $with;

  }

  public function getArgs( array $args = [] )
  {

    $this->setArgs( $args );

    $args = [];

    foreach ( $this->args as $name => $reference ) {
      $args[ $name ] = $this->application->load( $reference );
    }

    $this->setArgs( $args );

    return $args;

  }

}
