<?php

namespace nathanwooten\Http;

use nathanwooten\{

  Http\Uri

};

use Exception;

class Request
{

  protected string $body;
  protected array $data;
  protected string $method;
  protected array $params = [];
  protected UriInterface $uri;

  public function __construct( UriInterface $uri, $method = 'GET', array $params = [] )
  {

    $this->uri = $uri;
    $this->method = $this->methodEnsure( $method );

    $this->body = $this->uri->getTarget();

    $method = '_' . $this->method;
    global ${$method};

    $this->data = ${$method};
    $this->params = $params;

  }

  public function withUri( UriInterface $uri )
  {

    $clone = clone $this;
    $clone->uri = $uri;

    $clone->body = $clone->uri->getTarget();

    return $clone;

  }

  public function withMethod( $method )
  {

    $clone = clone $this;
    $clone->method = $method;

    return $clone;

  }

  public function withParams( array $params )
  {

    $clone = clone $this;
    $clone->params = $params;

    return $clone;

  }

  public function addParam( array $params, $param_name, $value )
  {

    $params[ $param_name ] = $value;
    return $params;

  }

  public function getUri() : UriInterface
  {

    return $this->uri;

  }

  public function getMethod()
  {

    return $this->method;

  }

  public function getParams()
  {

    return $this->params;

  }

  public function methodEnsure( $method )
  {

    $method = strtoupper( $method );
    if ( ! in_array( $method, [ 'GET', 'POST', 'PUT', 'DELETE' ] ) ) {
      throw new Exception( 'Unknown method: ' . $method );
    }

    return $method;

  }

}
