<?php

namespace nathanwooten;

abstract class ApplicationService
{

  protected $application;
  protected $id;
  protected $args = [];
  protected $injection = []
  protected $tags = [];

  public function __construct()
  {

    $this->path = dirname( __FILE__ );
    $this->parentPath = static::getApplication( $this->path );

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

  public function match( array $tags )
  {

    $has = [];
    foreach ( $this->tags as $pair ) {
      $list = $pair[0];
      $intersect = array_intersect( $tags, $list );

    }


  }

  public function getPath()
  {

    return (string) $this->application . $this->id;

  }

  public function getApplication()
  {

    return $this->application;

  }

}
