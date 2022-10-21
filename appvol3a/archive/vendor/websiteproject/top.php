<?php

use websiteproject\{

  Container\Container

};

if ( ! defined( 'LIB_PATH' ) ) die( __FILE__ );

$container = Container::class;

$top = require LIB_PATH . DS . 'nathanwooten' . DS . 'website' . DS . 'top.php';
return $top;
