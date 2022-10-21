<?php

namespace nathanwooten\View;

use nathanwooten\{

  Filesystem\FsFile

};

class ViewFile extends FsFile
{

  public $file = null;
  public $directory = null;

  public function __construct( $basename, array $tags = [] )
  {

    $this->file = $basename;
    $this->tags = $tags;

  }

}
