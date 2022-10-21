<?php

namespace nathanwooten\View\Views;

class BaseView extends View
{

  public $source = 'template.php';
  public $tags = [ 'view', 'base', 'instance' ];

  public function __construct()
  {

    parent::__construct( $this->source, $this->tags );

  }

}
