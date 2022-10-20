<?php

namespace nathanwooten\Application\Filesystem;

use nathanwooten\{

  Application\Filesystem\FilesystemPackage

};

interface FilesystemDirectoryInterface extends FilesystemPackage
{

  public function __toString();

}
