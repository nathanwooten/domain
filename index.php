<?php

use Domain\{

  Domain

};

use SomeVendor\{

  SomeType\SomeClass

};

if ( ! defined( 'DEBUG' ) ) define( 'DEBUG', 1 );
ini_set( 'display_errors', DEBUG );

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Domain.php';

$domain = Domain::getInstance();

$domain->add( 'SomeVendor' );

$domain->set( SomeClass::class, [ 'name' => 'Nathan Wooten', 'email' => 'a8gid24akl@gmail.com' ] );
$someobject = $domain->get( SomeClass::class );

$domain->injection( 'email', SomeClass::class, 'getEmail' );
$result = $domain->inject( 'email', SomeClass::class );

var_dump( $someobject );
var_dump( $result );