<?php

namespace nathanwooten\View;

use nathanwooten\{

  View\ViewManager,
  View\ViewModel

};

class ViewManagerService extends LoaderService
{

  protected static $id = ViewManager::class;

  public $paths = [
    'View/' . $id


  ];

  public function __construct()
  {



    $this->model = $application->getModel();

  }

}
