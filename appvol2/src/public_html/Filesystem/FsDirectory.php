<?php

namespace nathanwooten\Filesystem;

class FsDirectory extends FsItem
{

  public function __construct( $directory = null, array $tags = [] )
  {

    if ( ! is_null( $directory ) ) {
      $this->item = $directory;

    }

    if ( ! empty( $tags ) ) {
      $this->tags = $tags;

    }

  }

  public function getDirectory()
  {

    return $this->getItem();

  }

}
