<?php

namespace nathanwooten\Standard;

interface StandardFilesInterface extends StandardPackage {

  public function add( $dir, array $files = [] );
  public function get( $dir, $basename );

}
