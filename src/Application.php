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

	public Config $config;
	protected $routes = [];
	protected static $calls = [];

	public static $exceptions = [];
	public static $handle = 1;

	public static $allow_false = false;
	public static $allow_null = true;

	public function __construct( Config $config )
	{

		Registry::set( 'Application', $this );

		$this->config = $config;

	}

	public function run( $callback = null, array $args = null )
	{

		if ( is_null( $args ) ) $args = [];

		if ( is_callable( $callback ) ) {
			$callable = $callback;

			$chain = [
				CallableResolver::getName( $callable ) => [
					$callable,
					'out' => 'response'
				]
			];

		} elseif ( is_array( $callback ) ) {
			$chain = $callback;

		} else {

			$chain = [

				'config' => [
					[ $this, 'getConfig' ],
					'out' => [
						'config'
					]
				],
				'router' => [
					[ 'config', 'getRouter' ],
					'out' => [
						'router',
						[ $this, 'router' ]
					]
				],
				'request' => [
					[ 'config', 'getRequest' ],
					'out' => [
						'request',
						[ $this, 'request' ]
					]
				],
				'routes' => [
					[ 'config', 'getRoutes' ],
					'out' => [
						'routes'
					],
					'break' => true
				],

				'find' => [
					[ 'router', 'route' ],
					'in' => [
						'request',
						[ 'appRoutes' => [
							[ $this, 'routes' ],
							'in' => [
								'routes'
							],
							'out' => [
								'result'
							]
						]
					],
					'out' => [
						'route'
					],
					'break' => true
				],


				'call' => [
					[ 'router', 'call' ],
					'in' => $args,
				]

			];

		}

		$chainResult = $this->callChain( $chain, [], $args, null, $chain );
		while ( $chainResult[ 'chain' ] ) {

			$chainResult = static::callChain( ...array_values( static::getDefinedIn( $chainResult ) ) );

			if ( isset( $chainResult[ 'defined' ][ 'break' ] ) && is_string( $chainResult[ 'defined' ][ 'break' ] ) ) {

				$eventName = $chainResult[ 'defined' ][ 'break' ];
				$this->event( $eventName );
			}
		}
var_dump( $chainResult );
		$response = $this->chainResult( $chainResult );
		if ( is_string ( $resonse ) ) {

			print $response;

			return $this;
		}

		throw new Exception( 'Did not get a valid final response, expecting a string ( template ) to print' );

	}

	public function callChain( array $chain, array $definedVars = [], array $vars = [], $result = null, array $thisOldChain = null )
	{

		$defined = $this->getDefinedVars( $definedVars, $vars );
		extract( $defined );

		$thisOldChain = isset( $theOldChain ) ? $thisOldChain : $chain;

		try {

			reset( $chain );
			while ( $chain ) {

				$current = array_shift( $chain );

				$callable = $current[0];

				$in = [];

				if ( isset( $current[ 'in' ] ) ) {

					foreach ( $current[ 'in' ] as $key => $var ) {

						if ( is_string( $var ) ) {
							if ( isset( ${$var} ) ) {
								$in[ $key ] = ${$var};
							} else {
								$in[ $key ] = null;
							}

							continue;
						}

						if ( is_array( $var ) ) {
							$innerChain = $var;

							$in[ $key ] = $this->callChain( ...array_values( static::getDefinedIn( get_defined_vars() ) ) );

							continue;
						}
					}
				}

				$result = static::call( $callable, $in );

				if ( isset( $current[ 'out' ] ) ) {
					foreach ( $current[ 'out' ] as $var ) {

						if ( is_array( $var ) && ( is_object( $var[0] || is_string( $var[0] ) && $var[0] = new $var[0] ) ) && is_string( $var[1] ) ) {
							$var[0]->{$var[1]} = $result;

						} elseif ( is_string( $var ) ) {
							${$var} = $result;

						}
					}

					if ( isset( $callback[ 'break' ] ) ) {

						$return = static::getDefinedOut( get_defined_vars() );

						return $return;
					}
				}
			}

		} catch( Exception $e ) {
			$return = $this->handle( $e );

			return $return;

		} finally {
			$return = static::getDefinedOut( get_defined_vars() );

			return $return;
		}

	}

	public function chainResult( $chainResult )
	{

		if ( isset( $chainResult[ 'result' ] ) ) {
			return $chainResult[ 'result' ];
		}

		throw new Exception( 'Unset result property in chainResult' );

	}

	public static function call( $callable, $args = [] )
	{

		$callback = [ $callable, $args ];

		try {
			$result = $callable( ...array_values( $args ) ) ;

			if ( ! static::callFilter( $result ) ) {
				throw new Exception( 'Call filter fail' );
			}
		} catch ( Exception $e ) {
			$result = $e;

		} finally {

			if ( ! isset( $result ) ) {
				$result = null;
			}

			$callback[] = $result;

			static::$calls[] = $callback;

			return static::handle( $result );
		}

	}

	public static function callFilter( $result )
	{

		if ( $result instanceof Exception ) {
			throw new Exception( 'Unfavorable response from callback result, callback name' );
		}

		if ( ! static::$allow_false && false === $result ) {
			return false;
		}

		if ( ! static::$allow_null && null === $result ) {
			return false;
		}

		return true;

	}

	public function event( $eventName = null )
	{

		if ( ! is_null( $eventName ) && is_string( $eventName ) ) {

			$eventManager = static::getEventManager();

			try {
				if ( $eventManager->has( $eventName ) ) {

					$eventManager->dispatch( $eventName );
				}
			} catch( Exception $e ) {

				return $this->handle( $e );
			}
		}

		return true;

	}

	public static function getDefinedVars( $vars = [], $add = [] )
	{

		foreach ( $add as $name => $value ) {
			$vars[ $name ] = $value;
		}

		return $vars;

	}

	public static function getDefinedIn( $vars = [] ) {

		$ins = [];

		$hasOther = [ 'result' => 'resultInOutOther' ];

		$input = [ 'chain', 'defined', 'vars', 'result', 'thisOldChain' ];

		foreach ( $input as $var ) {

			if ( array_key_exists( $var, $hasOther ) ) {

				static::{$hasOther[ $var ]}( $vars, $var );
				continue;
			}

			if ( ! isset( $vars[ $var ] ) ) {
				throw new Exception( 'Please provide chain, defined and result keys to the input' );
			}

			$ins[ $var ] = $vars[ $var ];
		}

		return $ins;

	}

	public static function getDefinedOut( $vars = [] ) {

		$outs = [];

		$hasOther = [ 'result' => 'resultInOutOther' ];

		$output = [ 'chain', 'defined', 'result', 'thisOldChain' ];

		try {

			foreach ( $output as $var ) {

				if ( array_key_exists( $var, $hasOther ) ) {

					if ( static::{$hasOther[ $var ]}( $vars, $var ) ) {
						$value = $vars[ $var ];
					} else {
						$value = null;
					}

					$outs[ $var ] = $value;

					continue;
				}

				if ( ! isset( $vars[ $var ] ) ) {
					throw new Exception( 'Please provide ' . implode( ', ', $output ) . ' keys to the input' );
				}

				$outs[ $var ] = $vars[ $var ];
			}

			return $outs;

		} catch( Exception $e ) {

			$outs = $e;

		} finally {

			return $outs;
		}

	}

	public static function resultInOutOther( $vars, $var )
	{

		if ( ! isset( $vars[ $var ] ) ) {
			if ( ! static::$allow_null ) {
				throw new Exception( sprintf( 'Missing out, %s', $var ) );
			}
			$result = null;
		} else {
			$result = $vars[ $var ];
		}

		if ( ! static::hasResult( $result ) ) {
			throw new Exception( sprintf( 'Missing out, "result"' ) );
		}

		return null;

	}

	public static function hasResult( $result = null ) {

		if ( ! static::$allow_null && null === $result ) {
			return false;
		}

		return true;

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

	public function getEventManager()
	{

		return static::getConfig()->get( 'getEventManager' );

	}

	public static function getConfig()
	{

		$config = Registry::get( 'Application' )->config;

		return $config;

	}

	public static function getFunctions()
	{

		$functions = Registry::get( 'Functions' );

		return $functionsObject;

	}

	public static function handle( $value )
	{

		if ( $value instanceof Exception ) {

			array_push( static::$exceptions, $value );

			throw $value;
		}

		return $value;

	}

}
