<?php

namespace nathanwooten\Loader;

interface CallbackInterface
{

  public function __invoke( $args = null );
  public function setCallback( $callback );
  public function getCallback();

}
