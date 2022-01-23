<?php

namespace nathanwooten\Application;

use Exception;

use Psr\{

	Http\Message\RequestInterface

};

use GuzzleHttp\{

	Psr7\Request

};

use nathanwooten\{

	Functions\Functions,
	Registry\Registry

};

class Router {

	protected $route = [];
	protected $result = [];

	public function route( RequestInterface $request, RouteInterface ...$routes )
	{

		$return = null;
		$target = trim( $request->getRequestTarget(), '/' );

		if ( '' === $target ) {
			$target = '/';
		}

		foreach ( $routes as $name => $route ) {

			$pattern = $route->getParameter( 'pattern' );

			if ( $target === $pattern ) {
				$return = $route;
				break;
			}

			if ( $route->hasParameter( 'tags' ) ) {
//				$pattern = @preg_replace( '#' . $this->getFunctions()->delimit( '.*?' ) . '#', '(.*)', $pattern );
				$pattern = @preg_replace( '#' . $this->getFunctions()->delimit( '.*?' ) . '#', '(.*?)', $pattern );
			}

			@preg_match( '#' . $pattern . '#', $target, $matches );

			if ( ! empty( $matches ) ) {
				$return = $route;
				break;
			}

		}

		if ( isset( $return ) ) {

			$route = $return;
			$route->setParameter( 'uri', $target );

			if ( $route->hasParameter( 'tags' ) ) {
				$this->addTags( $route, $target );
			}

			return $this->routes[] = $route;
		}

		throw new Exception( 'No matching routes found' );

	}

	public function addTags( $route, $uri, $part = 'path|query' ) {

		$tags = [];
		$parameters = [];

		$pattern = $route->getParameter( 'pattern' );

		//locate

		foreach ( explode( '|', $part ) as $get ) {

			$methodName = 'actual' . ucfirst( $get );
			if ( ! method_exists( $this, $methodName ) ) {
				continue;
			}

			$actual = $this->$methodName( $uri );
			$actualPattern = $this->$methodName( $pattern );

			$tags = array_merge( $tags, $this->tagMatches( $actual, $actualPattern ) );
		}

		//set

		$parameters = [];
		foreach ( $route->getParameters() as $parameter => $value ) {
			$parameters[ $parameter ] = $value;

			if ( ! is_string( $value ) ) {
				continue;
			}
			if ( 'pattern' === $parameter ) {
				continue;
			}

			foreach ( $tags as $tag => $tagval ) {

				$tag = $this->getFunctions()->delimit( $tag, false );
	
				if ( false !== strpos( $value, $tag ) ) {

					$parameters[ $parameter ] = str_replace( $tag, $tagval, $parameters[ $parameter ] );
				}
			}
		}

		$route->setParameters( $parameters );

	}

	public function tagMatches( $actual, $actualPattern )
	{

		$tags = [];
		$matches = [];

		foreach ( $actualPattern as $key => $part ) {

			$pattern = $this->getFunctions()->delimit( '.*?' );

			@preg_match_all( '#' . $pattern . '#', $part, $matches );

			foreach ( $matches as $match ) {
				if ( empty( $match ) ) continue;

				$match = $match[0];

				if ( ! isset( $actual[ $key ] ) ) {
					continue;
				}
				$tags[ $this->getFunctions()->strip( $match ) ] = $actual[ $key ];
			}
		}

		return $tags;

	}

	public function call( RouteInterface $route, $args = [] ) {

		$callback = $route->getParameter( 'callable' );
		if ( is_array( $callback ) && is_string( $callback[0] ) ) {
			$callback[0] = new $callback[0];
		}

		try {
			if (
				! is_callable( $callback  )
			) {
				throw new Exception( 'Malformed route, route must be an array with a callable "callback" index' );
			}

		} catch ( Exception $e ) {
			Functions::handle( $e, 1 );
		}

		$this->result[] = $response = $callback( ...$args );

		return $response;

	}

	public function actualPath( $uri )
	{

		$actualPath = explode( '/', trim( parse_url( $uri, PHP_URL_PATH ), '/' ) );
		return $actualPath;

	}

	public function actualQuery( $uri )
	{

		$actualQuery = empty( $_GET ) ? [] :
			array_map(
				function( $item ) {
					return explode( '=', $item );
				},
				explode( '&', parse_url( $uri, PHP_URL_QUERY ) ) );

		return $actualQuery;

	}

	public function getRoute()
	{

		return current( $this->routes );

	}

	public function getFunctions()
	{

		return new Functions;

	}

	public function getApplication()
	{

		return Registry::get( 'application' );

	}

}
