<?php

namespace nathanwooten\Application;

use Exception;

use Psr\{

	Container\ContainerInterface,	
	Http\Message\RequestInterface

};


use Site\{

	Config

};

use nathanwooten\{

	Application\Route,
	Application\Router,
	Functions\Functions,
	Registry\Registry

};

if ( ! defined( 'SITE' ) ) require dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'index.php';

class Application
{

	protected $routes = [];

	public static $handle = 1;

	public function __construct( Config $config )
	{

		Registry::set( 'application', $this );

		$this->config = $config;

	}

	public function run()
	{

		$config = $this->getConfig();

		$this->router = $router = $config->getRouter();
		$this->request = $request = $config->getRequest();

		$routes = $config->routes();

		try {

			$route = $router->route( $request, ...$this->routes( $routes ) );

			if ( ! $route ) {
				throw new Exception( 'Could not find a matching route' );
			}

			$result = $router->call( $route );

			if ( ! $result ) {
				throw new Exception( sprintf( 'Unusable result from route call, named \'%s\'', $route->getParameter( 'pattern' ) ) );
			}

		} catch ( Exception $e ) {
			$this->handle( $e, static::$handle );
		}

		if ( is_string( $result ) ) {
			print $result;
		}

	}

	public function routes( array $routes = [] )
	{

		foreach ( $routes as $name => $route ) {
			$routes[ $name ] = $this->setRoute( $route );
		}

		return array_values( $routes );

	}

	public function setRoute( $input )
	{

		if ( $input instanceof Route ) {
			$route = $input;
		}
		else {
			if ( ! is_array( $input ) ) {
				throw new Exception( 'Bad route, bad' );
			}

			$route = new Route;
			foreach ( $input as $property => $value ) {
				$route->setParameter( $property, $value );
			}

		}

		return $this->routes[ $route->getParameter( 'pattern' ) ] = $route;

	}

	public function getRoute( $pattern )
	{

		if ( ! isset( $this->routes[ $pattern ] ) ) {
			return null;
		}

		return $this->routes[ $pattern ];

	}

	public function getRouter()
	{

		return $this->router;

	}

	public function getConfig()
	{

		return $this->config;

	}

	public function getFunctions()
	{

		return $this->getConfig()->getFunctions();

	}

	public function handle( $e, $code )
	{

		return Functions::handle( $e, $code );

	}

}
