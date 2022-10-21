<?php

namespace nathanwooten\Website\Filesystem;

use nathanwooten\{

  Website\Filesystem\FilesystemDirectoryInterface

};

class FilesystemDirectory implements FilesystemDirectoryInterface
{

  protected ?FilesystemDirectory $parent = null;
  protected $directory;

  public function __construct( $directory = null, $parent = null )
  {

    $this->setDirectory( $directory );
    $this->setParent( $parent );

  }

  public function prepare( $directory = null )
  {

    $directories = [];

    if ( ! $directory ) {
      $directories[0] = '';

    } else {

      if ( ! is_array( $directory ) ) {
        $directory = (string) $directory;
        $directory = trim( str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $directory ), DIRECTORY_SEPARATOR );
        $directories[0] = $directory;

      } else {

        foreach ( $directory as $i => $d ) {
          if ( ! $d instanceof FilesystemDirectoryInterface ) {
            $dir = new FilesystemDirectory( $d );
            $directories[ $i ] = $dir;

          }
        }
      }
    }
    $directory = $directories;

    return $directory;

  }

  public function setDirectory( $directory = null )
  {

    $this->directory = $this->prepare( $directory );

  }

  public function getDirectory()
  {

    return $this->directory;

  }

  public function setParent( DirectoryInterface $parent = null )
  {

    $this->parent = $parent;

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

    $directory = $this->getDirectory();

    $directory = is_array( $directory ) ? implode( DIRECTORY_SEPARATOR, $directory ) : (string) $directory;
    return $directory;

  }

}
