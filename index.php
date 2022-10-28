<?php

use Domain\{

  Domain

};

if ( ! defined( 'DEBUG' ) ) define( 'DEBUG', 1 );
ini_set( 'display_errors', DEBUG );

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Domain.php';

$domain = Domain::getInstance();

$domain->add( 'SomeVendor' );

$domain->setService( 'SomeVendor\SomeType\SomeClass', [ 'name' => 'Nathan Wooten', 'phone' => '1-555-NOT-REAL', 'email' => 'a8gid24akl@gmail.com' ] );
$someobject = $domain->getService( 'SomeVendor\SomeType\SomeClass' );
var_dump( $someobject );
