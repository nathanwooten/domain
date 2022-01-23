<?php

namespace nathanwooten\Application;

interface RouteInterface
{

	public function setParameters( $parameters );
	public function getParameters();
	public function setParameter( $name, $value );
	public function getParameter( $name );
	public function hasParameter( $name );

}
