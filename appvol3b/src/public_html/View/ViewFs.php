<?php

namespace nathanwooten\Filesystem;

class Fs
{

  public static $fs = [];

  public function __construct( $root = PUBLIC_HTML . DIRECTORY_SEPARATOR . 'View', $tags = [ 'fs', 'view', 'base' ] )
  {

    $this->root = $root;

    static::$fs[ $root ] = $this;

  }

}
