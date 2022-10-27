<?php

namespace Domain;

use Domain\{

  DomainHelper

};

class DomainCollection
{

  protected $path;
  protected $pointer = 0;

  public $items = [];

  public function __construct( $pathOrDomain )
  {

    $this->path = $pathOrDomain;

    $this->items = DomainHelper::scan(
      $this->path,
      function ( $item ) use ( $pathOrDomain ) {
        $itemPath = $pathOrDomain . DIRECTORY_SEPARATOR . $item;
        if ( '.' === $item || '..' === $item || ! is_dir( $itemPath ) ) {
          return null;
        }
        return $item;
      }
    );

    $this->items = array_map(
      function ( $item ) use ( $pathOrDomain ) {
        $itemPath = $pathOrDomain . DIRECTORY_SEPARATOR . $item;
        $item = new DomainCollection( $itemPath );
        return $item;
      },
      $this->items
    );

    $this->items = array_values( $this->items );

  }

  public function seek( $name )
  {

    $iterator = $this;
    $iterator->rewind();

    if ( ! is_array( $name ) ) {
      if ( false !== strpos( $name, '\\' ) ) {
        $name = explode( '\\', $name );
      } else {
        $name = [ $name ];
      }
    }

    while ( $iterator->valid() ) {
      while( $path = array_shift( $name ) ) {

        $match = $iterator->match( $path );
        if ( $match ) {
          $iterator = $iterator->current();
        } else {
         $iterator->next();
        }
      }
    }

    return $iterator;

  }

  public function match( $name )
  {

    return $name === $this->current()->getName();

  }

  public function getName()
  {

    $name = basename( $this->path );
    return $name;

  }

  public function getPath()
  {

    return $this->path;

  }

  public function valid()
  {

    $valid = array_key_exists( $this->pointer, $this->items );
    return $valid;

  }

  public function current()
  {

    if ( $this->valid() ) {
      $current = $this->items[ $this->pointer ];
      return $current;
    }

  }

  public function key()
  {

    return $this->pointer;

  }

  public function rewind()
  {

    $this->pointer = 0;

  }

  public function next()
  {

    ++$this->pointer;

  }

  public function prev()
  {

    --$this->pointer;

  }

  public function __toString()
  {

    $name = $this->valid() ? DIRECTORY_SEPARATOR . $this->getName() : '';
    return $this->path . $name;

  }

}