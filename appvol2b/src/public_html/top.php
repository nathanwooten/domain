<?php

use nathanwooten\{

  Autoloader,

  Website\Application,

  Website\Http\Request,
  Website\Http\Uri

};

if ( ! defined( 'PROJECT_PATH' ) ) require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'require.php';

if ( ! defined( 'PROJECT_NAME' ) ) define( 'PROJECT_NAME', 'websiteproject' );

if ( ! defined( 'DEBUG' ) ) {
  define( 'DEBUG', 1 );
  ini_set( 'display_errors', DEBUG );
}

if ( ! defined( 'NATHANWOOTEN' ) ) define( 'NATHANWOOTEN', PROJECT_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'nathanwooten' );

$autoloader = NATHANWOOTEN . DIRECTORY_SEPARATOR . 'Autoloader' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Autoloader.php';
require_once $autoloader;

$namespace = Autoloader::configure( 'nathanwooten\Loader', 'Loader' . DIRECTORY_SEPARATOR . 'src', NATHANWOOTEN );
if ( 'nathanwooten\Loader' === $namespace ) {
  return 1;
}

return 0;
