<?php

if ( ! defined( 'PROJECT_PATH' ) ) define( 'PROJECT_PATH', dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR );

$top = require PROJECT_PATH . 'lib' . DS . 'nathanwooten' . DS . 'website' . DS . 'top.php';
return $top;
