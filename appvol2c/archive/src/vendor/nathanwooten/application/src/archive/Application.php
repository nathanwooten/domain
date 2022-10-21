<?php

class ApplicationAbstract extends FilesystemDirectory implements ApplicationInterface
{

  use ServicesProviderTrait;

  protected RequestInterface $request;
  protected $root;
  protected $path;

  protected array $services = [];
  protected ContainerInterface $container;

  public function __construct( ContainerInterface $container, RequestInterface $request, $root )
  {

    $this->container = $container;
    $this->request = $request;

    parent::__construct( $this, $root );

  }

  public function getContainer()
  {

    return $this->container;

  }

  public function getRequest()
  {

    return $this->request;

  }

  public function get( $id )
  {

    return $this->getContainer()->get( $id );

  }

  public function has( $id )
  {

    return $this->getContainer()->has( $id );

  }

}
