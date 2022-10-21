<?php

namespace nathanwooten\Standard;

if ( ! interface_exists( 'nathanwooten\Standard\StandardInterface' ) ) {
interface StandardInterface extends StandardPackage
{

  public function getContainer();

}
}
