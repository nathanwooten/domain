<?php

namespace nathanwooten\Loader;

use nathanwooten\{

  Loader\Loader,
  Loader\LoaderServiceInterface

};

class LoaderService implements LoaderServiceInterface
{

  protected array $tags = [];

  protected $id;
  protected array $args = [];

  protected $service = null;

  public function __construct( Loader $loader )
  {

    $this->loader = $loader;

    $this->create();

  }

  public function getLoader()
  {

    return $this->loader;

  }

  protected function create()
  {

    $this->getLoader()->create( $this->id, $this->args );

  }

  public function setService( $service )
  {

    $this->service = $service;

  }

  public function getService()
  {

    return $this->service;

  }

  public function hasMethod( $tags )
  {

    return Application::tag( $container, $tags );

  }

  public function getTags()
  {

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
