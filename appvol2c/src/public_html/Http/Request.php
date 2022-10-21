<?php

namespace nathanwooten\Http;

use nathanwooten\{

  Loader\Loader

};

class Request implements RequestInterface
{

  public $body = '';
  public array $params = [];
  public UriInterface $uri;

  public function __construct( ?UriInterface $uri = null, array $params = [] )
  {

    if ( ! $uri || $uri instanceof UriInterface ) {
      $uri = new Uri( $uri );

    }
    $this->uri = $uri;

    $this->body = $this->name();

  }

  public function withUri( $uri = null )
  {

    return new static( $uri, $this->getParams() );

  }

  public function withParams( array $params = [] )
  {

    return new static( $this->getUri(), $params );

  }

  public function getBody()
  {

    return $this->body;

  }

  public function getParams()
  {

    return $this->params;

  }

  public function getData()
  {

    global ${$SERVER[ 'REQUEST_METHOD' ]};
    return ${$SERVER[ 'REQUEST_METHOD' ]};

  }

  public function getTags()
  {

    return $this->getParams();

  }

  protected function name( $path = null )
  {

    return Loader::name( $path ?? $this->uri->getComponent( PHP_URL_PATH ) );

  }

}
