<?php

namespace nathanwooten\Website\Http;

Interface UriInterface
{

  public function getUri();
  public function getTarget();
  public function getComponent( int $phpUrlConstant );

  public function withUri( string $uri ) : UriInterface;
  public function withComponent( int $phpUrlConstant, string $component ) : UriInterface;
  public function withParams( array $params ) : UriInterface;

  public function getParams();
  public function getParam( $name );

}