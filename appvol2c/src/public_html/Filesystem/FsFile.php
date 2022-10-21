<?php

namespace nathanwooten\Filesystem;



class FsFile
{

  public $file = null;
  public $directory = null;

  public function __construct( $basename, array $tags = [] )
  {

    $this->basename = $basename;
    $this->tags = $tags;

  }

  public function getBasename()
  {

    return $this->basename;

  }

  public function getTags()
  {

    return $this->tags;

  }

}
