<?php

namespace nathanwooten\Fs;

class FsItem
{

  public function with( $item, array $tags = [] ) {
  {

    $id = get_class( $this );
    $item = new $id( $item, $tags );

    return $item;

  }

  public function getItem()
  {

    return $this->item;

  }

  public function getTags()
  {

    return $this->tags;

  }

  public function getPath( $append )
  {

    $fs = static::getFs( $append );
    if ( $fs ) {
      return $fs;

    } 

  }

  public static function getFs( $append )
  {

    return Fs::find( [ PUBLIC_HTML, $append ] );

  }


}
