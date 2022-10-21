<?php

namespace nathanwooten\View;

use nathanwooten\{

  View\ViewManager,

};

class ViewManagerService extends LoaderService
{

  protected static $id = ViewManager::class;

  public $requests = [
    'setTemplate'
  ];

  public function __construct( Loader $loader )
  {

    $this->loader = $loader;

  }

}
