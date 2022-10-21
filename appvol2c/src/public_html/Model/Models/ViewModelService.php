<?php

namespace nathanwooten\Model\Models;

class ViewModelService extends LoaderService {

  public $request = [
    [
      ViewManager::class,
      'setView'
    ]


  ];

}
