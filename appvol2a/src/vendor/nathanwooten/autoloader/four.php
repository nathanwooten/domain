<?php

if ( ! function_exists( 'getDirname' ) ) {
function getDirname( $dir, int $count )
{

  while( $count ) {
    --$count;

    $dir = dirname( $dir );
  }

  return $dir;

}
}

}

return getDirname( __FILE__, 4 );
