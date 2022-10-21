<?php

namespace nathanwooten\Website\Filesystem;

use nathanwooten\{

  Website\Filesystem\FilesystemPackage

};

interface FilesystemDirectoryInterface extends FilesystemPackage
{

  public function __toString();

}
