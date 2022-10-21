<?php

function getTemplate( $target )
{

  $public = PROJECT_PATH . 'public_html';
  $directory = $public . str_replace( '/', DIRECTORY_SEPARATOR, $target );

  $file = $directory . DIRECTORY_SEPARATOR . 'index.php';

  $vars = parse_ini_file( $directory . DIRECTORY_SEPARATOR . 'variables.ini' );
  $vars[ 'head' ] = include $public . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'head.html';

  $template = file_get_contents( $file );

  foreach ( $vars as $var_name => $value ) {
    $template = replace( $var_name, $value, $template );
  }

  $template = buffer( $template, $vars );
  return $template;

}

function replace( $name, $value, $string )
{

  $tag = '{{' . $name . '}}';
  $replace = str_replace( $tag, '<?php if ( isset( $' . $name . ' ) ) { print $' . $name . '; } ?>', $string );

  return $replace;

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
