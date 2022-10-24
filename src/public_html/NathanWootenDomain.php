<?php

namespace nathanwooten;

use nathanwooten\{
  AbstractDomain
};

class NathanWootenDomain extends AbstractDomain
{

  public function __construct()
  {

    parent::__construct( dirname( __FILE__ ), [ 'nathanwooten' );

  }

}
