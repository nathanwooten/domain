<?php

namespace websiteproject\Application;

use nathanwooten\{

  ApplicationAbstract

};

class Application extends ApplicationAbstract {

  protected array $services = [ Templater::class ];

}
