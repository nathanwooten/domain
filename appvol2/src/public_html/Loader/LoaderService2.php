<?php

namespace nathanwooten\Loader;

use nathanwooten\{

  Loader\Loader,
  Loader\LoaderServiceInterface

};

class LoaderService implements LoaderServiceInterface
{

  protected array $tags = [];

  protected $service;

  public function __construct( $id = null, $args = null, array $tags = [] )
  {

    if ( ! is_null( $id ) ) {
      $this->id = $id;

    }

    if ( ! is_null( $args ) ) {
      $this->args = $args;

    }

    if ( ! $tags ) ) {
      $this->tags = $tags;

    }

  }

  public function __invoke( $args = null, $force = false )
  {

    if ( isset( $this->service ) && ! $force ) {
      return $this->service;
    }

    $id = $this->id;

    if ( ! is_array( $id ) ) {
      $class = $id;
      $args = $this->getArgs( $args );

    } else {
      $class = $id[0];
      $method = $id[1];
      $args = $this->getArgs( $args );

    }

    $service = $this->create( $id, $args ?? [] );

    if ( isset( $method ) ) {
      $service = $service->$method( ...$args );

    }


    return $this->service = $service;

  }

  public function setService( $service )
  {

    $this->service = $service;

  }

  public function getService()
  {

    return $this->service;

  }

  public function tag( $tag = null )
  {

    if ( ! is_null( $tag ) ) {

      if ( is_array( $tag ) ) {
        $this->tags[] = $tag;

      } else {
        $this->tags = array_merge( $this->tags, $tag );

      }
    }

    return $this->tags;

  }

  public function getId()
  {

    return $this->id;

  }

  public function getTagsString()
  {

    return implode( '_', $this->tags );

  }

  public function __toString()
  {

    return $this->getId();

  }  

}
