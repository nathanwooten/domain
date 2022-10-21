<?php

namespace nathanwooten\Website\Filesystem;

interface FilesystemItemInterface extends FilesystemPackage
{

  public function getDirectory();
  public function getBasename();

  public function __toString();

}