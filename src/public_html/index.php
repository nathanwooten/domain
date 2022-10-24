<?php

if ( ! defined( 'DEBUG' ) ) define( 'DEBUG', 1 );
ini_set( 'display_errors', DEBUG );

$dir = dirname( __FILE__ );

require_once $dir . DIRECTORY_SEPARATOR . 'AbstractDomain.php';

$domain = new nathanwooten\AbstractDomain( dirname( __FILE__ ) );

var_dump( $domain );