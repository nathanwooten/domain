<?php

namespace nathanwooten\Website\Filesystem;

use nathanwooten\{

  Website\Filesystem\FilesystemItemInterface,
  Website\Filesystem\FilesystemDirectoryInterface

};

class FilesystemItem implements FilesystemItemInterface {

  public function __construct( FilesystemDirectoryInterface $directory, $basename )
  {

    $this->directory = $directory;
    $this->basename = $basename;

  }

  public function getDirectory()
  {

    return $this->directory;

  }

  public function getBasename()
  {

    return $this->basename;

  }

  public function __toString()
  {

    return $this->getDirectory() . $this->getBasename();

  }

}