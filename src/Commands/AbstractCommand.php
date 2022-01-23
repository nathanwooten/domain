<?php

namespace nathanwooten\Application\Commands;

use nathanwooten\{

	Registry\Registry

};

abstract class AbstractCommand {

	/**
	 * All concrete commands must implement
	 * this method with no parameters or typing
	 */

	abstract function __invoke();

	public function getRequest()
	{

		$request = $this->getConfig()->getRequest();

		return $request;

	}

	public function getRoute()
	{

		$route = $this->getApplication()->getRouter()->getRoute();
		return $route;

	}

	public function getDirectory( $name )
	{

		return $this->getConfig()->getDirectory( $name );

	}

	public function getSql( $name )
	{

		try {
			$sql = $this->getConfig()->sql( $name );
			if ( ! $sql ) {
				throw new Exception( 'Unknown SQL name, "%s" queried', $name );
			}
		} catch ( Exception $e ) {


		}

	}

	public function getConfig()
	{

		return Registry::get( 'config' );

	}

	public function getApplication()
	{

		return Registry::get( 'application' );

	}

}
