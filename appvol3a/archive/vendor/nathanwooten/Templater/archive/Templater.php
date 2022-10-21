<?php

namespace nathanwooten\Website\Templater;

use nathanwooten\{

  Website\Templater\TemplateInterface

};

class Templater extends Service implements TemplaterPackage {

  public function setRequest( RequestInterface $request )
  {

    $this->request = $request;
    return $this;

  }

  public function getRequest()
  {

    return $this->request;

  }

  public function setResponse( ResponseInterface $response )
  {

    if ( isset( $this->body ) ) {
      $response->setProperties( [ 'body' => $this->body ] );
    }

    $this->response = $response;

    return $this;

  }

  public function getResponse()
  {

    return $this->response;

  }

}
