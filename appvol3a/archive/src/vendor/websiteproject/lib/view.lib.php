<?php

if ( ! defined( 'PROJECT_PATH' ) ) define( 'PROJECT_PATH', dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . DIRECTORY_SEPARATOR );

//
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'http.lib.php';

function getTemplate( $target = null )
{

  if ( ! isset( $target ) ) {
    $target = getTarget();
  }

  $directory = getDirectory( $target );


  $file = $directory . DIRECTORY_SEPARATOR . 'index.php';

  $vars = parse_ini_file( $directory . DIRECTORY_SEPARATOR . 'variables.ini' );
  $vars[ 'head' ] = file_get_contents( $public . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'head.html' );

  if ( isset( $vars[ 'has' ] ) ) {
    $has = explode( ',', $vars[ 'has' ] );

    foreach ( $has as $var_name ) {
 
     foreach ( match( $vars[ $var_name ] ) as $tag ) {
        $name = strip( $tag );

        $vars[ $var_name ] = replace( $name, $vars[ $name ], $vars[ $var_name ] );
      }

      $buffer = buffer( $vars[ $var_name ], $vars );

      $vars[ $var_name ] = $buffer;
    }
  }

  $template = file_get_contents( $file );

  foreach ( $vars as $var_name => $value ) {
    $template = replace( $var_name, $value, $template );
  }

  $template = buffer( $template, $vars );
  return $template;

}



function getHas( $target = null )
{

  $directory = $public . str_replace( '/', DIRECTORY_SEPARATOR, $target );


}

function match( $template )
{

  preg_match_all( '/\{\{.*?\}\}/', $template, $matches );

  if ( isset( $matches[0] ) ) {
    return $matches[0];
  }

}

function replace( $template, $vars = [] )
{

  foreach ( match( $template ) as $tag ) {
    $name = strip( $tag );

    $name = strip( $name );
    $tag = tag( $name );
  
    $template = str_replace( $tag, '<?php if ( isset( $' . $name . ' ) ) { print $' . $name . '; } ?>', $template );
  }

  return $template;

}

function replace( $name, $value, $string )
{



  return $replace;

}

function tag( $name )
{

  return '{{' . strip( $name ) . '}}';

}

function strip( $tag )
{

  return str_replace( [ '{{', '}}' ], '', $tag );

}

function buffer( $template, $vars = [] )
{

  extract( $vars );

  $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'tempfile.php';
  file_put_contents( $file, $template );

  ob_start();
  include $file;

  $contents = ob_get_clean();
  return $contents;

}
