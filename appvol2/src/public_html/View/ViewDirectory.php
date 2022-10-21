<?php

namespace nathanwooten\View;

use nathanwooten\{

  Fs\FsDirectory

};

class ViewDirectory extends FsDirectory
{

  public function __construct()
  {

    $set = [ PUBLIC_HTML, 'Templater' ];

    parent::__construct( $set, [ 'directory', 'view', 'base' ] );

  }

}
