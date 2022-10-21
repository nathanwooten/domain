<?php

namespace nathanwooten\Website;

interface ContainerInterface {

  public function get( $id );
  public function has( $id );

}