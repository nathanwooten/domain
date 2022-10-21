<?php

if ( ! defined( 'PROJECT_PATH' ) ) die( __FILE__ );

if ( ! isset( $target ) ) {
  $target = getUriProperty( 'target', null );
}

return PROJECT_PATH . 'public_html' . $target;
