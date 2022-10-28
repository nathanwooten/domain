<?php

namespace SomeVendor\SomeType;

class SomeClass
{

  public $name;
  public $email;

  public function __construct( $name, $email )
  {

    $this->name = $name;
    $this->email = $email;

  }

  public function getName()
  {

    return $this->name;

  }

  public function getEmail()
  {

    return $this->email;

  }

}
