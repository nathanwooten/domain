<?php

namespace nathanwooten\Domain;

use nathanwooten\{

  Domain\Definition

};

class ServiceDefinition implements Definition
{

  protected Domain $domain;
  protected mixed $service;
  protected array $args;

  protected $properties = [];

  public function __construct( Domain $domain, $id = null, $service = null, $args = null, array $properties = [] )
  {

    $this->domain = $domain;

    if ( $id ) $this->id = $id;
    if ( $service ) $this->service = $service;

    $this->args = $this->convert( $args );

    $this->properties = array_merge( $this->properties, $properties );

  }

  public function getService()
  {

    if ( ! is_object( $this->service ) ) {
      $this->service = $this->domain->factory( $this->service, $this->args );
    }

    return $this->service;

  }

  public function getInterface()
  {

    return $this->service;

  }

  public function convert( $args )
  {

    $args = ! is_array( $args ) ? ( $args ? [ $args ] : [] ) : $args;

    foreach ( $args as $key => $serviceOrResponse ) {
      if ( ! is_numeric( $key ) ) {
        if ( class_exists( $serviceOrResponse ) ) {
          $args[ $key ] = $this->domain->load( $serviceOrResponse, $serviceOrResponse );
        } else {
          $args[ $key ] = $this->domain->get( $serviceOrResponse );
        }
      }
    }

    return $args;

  }

  public function isShared()
  {

    return ! isset( $this->properties[ 'shared' ] ) ? true : (bool) $this->properties[ 'shared' ];

  }

}