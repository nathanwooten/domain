<?php

namespace websiteproject\Container;

if ( ! defined( 'DS', DIRECTORY_SEPARATOR );

class ContainerDependencies
{

  public $autoloads = [
    [
      'nathanwooten\Standard',
      PROJECT_PATH . 'local' . DS . 'nathanwooten' . DS . 'standard' . DS . 'main' . DS . 'src'
    ]
  ];

  public $config_functions = [
    [
      PROJECT_PATH . 'local' . DS . 'nathanwooten' . DS . 'website' . DS . 'functions.php',
      [
        'getTarget',
        'urlRelative'
      ]
    ]
  ];

  public $fsNorm = [ null, DS, DS ]

  public function __construct()
  {

    $this->requireFunctionsFile( dirname( __FILE__ ) . DS . 'functions.php' );

    foreach ( $this->functions as $function ) {
      $this->requireFunctionsFile( ...$function );
    }

  }

  public function requireFunctionsFile( $file, $required = [] )
  {

    // File return
    $false_return = 0;

    $dfns = get_defined_functions();
    $user = $dfns[ 'user' ];

    $result = require_once $file;

    if ( $result ) {

      $new = get_defined_functions();
      $fns = array_diff( $new, $user );

      if ( ! empty( array_diff( $required, $fns ) ) ) {

        return $false_return;

      }

      $index = count( $this->functions );

      $functions = [
        $file,
        $fns
      ];

      $this->functions[ $index ] = $functions;

      return $fns;

    }

    return $false_return;

  }

  public function requireDefinitionsFile()
  {



  }

  public function definition( $define, $definition )
  {

    $define = strtoupper( $define );

    if ( ! defined( $define ) ) define( $define, $definition );

    return $this->defines[ $define ] = $defintion;

  }

  public function pathDefinition( $define, $definition )
  {

    $definition = $this->runUser( 'fnNorm', [ $definition, ...$this->fsNorm ] );

    return $this->definition( $define, $definition );

  }

  public function runUser( $fn_name, array $args = [] )
  {

    if ( ! $this->hasFn( $fn_name ) ) {


    }

    $fn_name

  }

  public function hasFn( $fn_name )
  {

    return in_array( $fn_name, get_defined_functions()[ 'user' ] );

  }

}
