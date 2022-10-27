<?php

use Domain\{

  Domain

};

if ( ! defined( 'DEBUG' ) ) define( 'DEBUG', 1 );
ini_set( 'display_errors', DEBUG );

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Domain.php';

$domain = Domain::getInstance();	

$domain->setService( 'SomeVendor\SomePackage\SomeClass', [ 'Nathan Wooten', '1-555-NOT-REAL', 'address@example.com' ] );
$domain->getService( 'SomePackage\SomeClass' );
var_dump( $domain );
