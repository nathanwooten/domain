<?php

namespace nathanwooten\Application\Filesystem;

class FilesystemDirectory implements FilesystemDirectoryInterface
{

  public ?FilesystemDirectory $parent = null;
  public $directory;

  public function __construct( $directory = null, FilesystemDirectory $parent = null )
  {

    $this->setDirectory( $directory );
    $this->parent = $parent;

  }

  public function setDirectory( $directory = null )
  {

    if ( ! $directory ) {
      $this->directory = '';
      return;
    }

    if ( ! is_array( $directory ) ) {
      $directory = (string) $directory;

      $this->directory = trim( str_replace( [ '\\', '/' ], DS, $directory ), DS );

    } else {

      foreach ( $directory as $i => $d ) {
        if ( ! $d instanceof FilesystemDirectoryInterface ) {
          $dir = new FilesystemDirectory( $d );
          $directories[ $i ] = $dir;
        }
      }

      $this->directory = $directories;
    }

  }

  public function getParent()
  {

    return $this->parent;

  }

  public function getPath()
  {

    $parent = $this->getParent();
    if ( ! $parent ) {
      $parent = '';
    } else {
      $parent = $parent->getPath();
    }

    return $parent . $this->__toString();

  }

  public function __toString()
  {

    return is_array( $this->directory ) ? implode( DS, $this->directory ) : (string) $this->directory;

  }

}
