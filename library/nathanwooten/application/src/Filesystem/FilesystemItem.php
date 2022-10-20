<?php

namespace nathanwooten\Application\Filesystem;

use nathanwooten\{

  Application\Filesystem\FilesystemItemInterface,
  Application\Filesystem\FilesystemDirectoryInterface

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