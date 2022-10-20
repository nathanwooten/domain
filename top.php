<?php

if ( ! defined( 'LIB_PATH' ) ) {
  require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'public_html' . DIRECTORY_SEPARATOR . 'require.php';
}

if ( ! defined( 'PROJECT_NAME' ) ) define( 'PROJECT_NAME', 'websiteproject' );

$top = LIB_PATH. 'nathanwooten' . DS . 'application' . DS . 'includes' . DS . 'top.php';
return require $top;
