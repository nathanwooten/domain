<?php

namespace websiteproject\Container;

use nathanwooten\{

  Container\ContainerAbstract

};

class Container extends ContainerAbstract {

  public function __construct()
  {

    parent::__construct( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Services' );

  }

  public static function getNamespace()
  {

    return __NAMESPACE__;

  }

}
