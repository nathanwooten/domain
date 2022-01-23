<?php

namespace nathanwooten\Application;

class Route implements RouteInterface
{

	protected $parameters = [];

	public function setParameters( $parameters )
	{

		foreach ( $parameters as $name => $value ) {

			$this->setParameter( $name, $value );
		}

	}

	public function getParameters()
	{

		return $this->parameters;

	}

	public function setParameter( $name, $value )
	{

		$this->parameters[ $name ] = $value;

	}

	public function getParameter( $name )
	{

		return array_key_exists( $name, $this->parameters ) ? $this->parameters[ $name ] : null;

	}

	public function hasParameter( $name )
	{

		return array_key_exists( $name, $this->parameters );

	}

}
