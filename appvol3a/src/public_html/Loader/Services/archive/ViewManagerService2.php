<?php

namespace nathanwooten\View;

use nathanwooten\{

  View\ViewManager,
  View\ViewModel

};
/*
class BaseViewService
{

  public $id = BaseView::class;

}

class ViewAddService
{

  public $id = [ ViewManager::class, 'setTemplate' ];
  public $args = [




  ];



}
*/
class ViewManagerService extends ApplicationService
{

  protected static $id = ViewManager::class;

  protected $actions = [
    [
      BaseView::class
    ],
    [
      [
        null
        'addTemplate'
      ],
      [

      ]
    ],
    [
      [
        null
        'setTemplate'
      ],
      [
        [ 'view', 'base', 'instance' ]
      ]
    ],
    $template = $this->getTemplate();
    return $this->template = $this->compiler()->compile( $template, $this->getTemplates() );




    [
      [
        null
        'prepare'
      ]
    ],
    [
      [
        null
        'getBody'
      ]
    ]
  ];

  public function __construct( Application $application )
  {

    parent::__construct( $application, [ 'view', 'instance' ] );

    $this->model = $application->getModel();

  }

}
