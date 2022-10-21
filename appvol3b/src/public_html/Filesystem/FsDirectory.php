<?php

namespace nathanwooten\ApplicationDirectory;

class FsItem
{

  public function __construct( $item )
  {

    $this->item = $item;

  }

}


class FsDirectory
{

  protected $path

  public function __construct( $sort = [], $tags = [] )
  {

    $this->directory = dirname( __FILE__ );

    $this->path = $this->sortDirectory( $sort, $this->directory );

    $properties = $this->args( $args );
    foreach ( $properties as $property => $value ) {
      $this->$property = $value;
    }

    $this->application = Application::getApplication( $this->directory );

  }

  public function sortDirectory( $sort, $directory )
  {



  }

  public static function args( $args )
  {

    $parsed = [];
    $values = array_values( $args );
    $parsed = [
      'tags' => $value[0]
    ];

    return $parsed;

  }

}
