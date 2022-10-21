<?php

namespace nathanwooten\Templater\Template;

class BaseTemplate extends Template
{

  protected $properties = [
    'base' => 1
  ];

  protected $directory = '{{PROJECT_PATH}}/public_html/{{path}}';

  protected $template = 'template.php';

}
