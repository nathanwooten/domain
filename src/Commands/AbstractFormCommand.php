<?php

namespace nathanwooten\Application\Commands;

use Exception;
use ReflectionClass;

use nathanwooten\{

	Registry\Registry

};

abstract class AbstractFormCommand extends AbstractAdminCommand
{

	public $parameters = [];

	public function __construct()
	{

		$route = $this->getRoute();

		$this->parameters = $route->getParameters();

	}

	public function set( $name, $value )
	{

		$this->parameters[ $name ] = $value;

	}

	public function get( $name )
	{

		if ( isset( $this->parameters[ $name ] ) ) {
			return $this->parameters[ $name ]; 
		}

	}

	public function __invoke()
	{

		$route = $this->getRoute();

		if ( $this->get( 'form' ) && $this->submitted( $this->get( 'form' ) ) ) {

			$post = $this->getVars();

			$this->submit( $post );
			$this->redirect();

		} elseif ( $this->submitted() ) {

			$post = $this->getPost( FILTER_SANITIZE_STRING );

			$template = $this->present( $post );
			return $template;

		} else {

			$variables = $this->getContent( $this->get( 'form' ) );

			$template = $this->present( $variables );
			return $template;
		}

	}

	public function present( array $variables = [] )
	{

		$variables = empty( $variables ) ? $_POST : $variables;

		$table = $this->get( 'table' );
		$crud = $this->get( 'crud' );
		$form = $table . '-' . $crud . '-form.php';
		$directory = $this->getDirectory( 'templates.admin' );;

		extract( $variables );

		$template = $this->getTemplate( $form, $directory, $variables );
		if ( ! $template ) {

			return false;
		}

		return $template;

	}

	public function submitted( $formName = null )
	{

		if ( ! isset( $formName ) ) {
			return isset( $_POST[ 'submitted' ] );
		}

		$formName = str_replace( '/', '-', $formName ) . '-form';

		if ( isset( $_POST[ 'submitted' ] ) && $formName === $_POST[ 'submitted' ] ) {
			return true;
		}

		return false;

	}

	public function submit( $variables = [] )
	{

		$sql = $this->getConfig()->sql( $this->get( 'form' ) );
		$crud = $this->get( 'crud' );
		$id = isset( $variables[ 'id' ] ) ? $variables[ 'id' ] : ( isset( $_POST[ 'id' ] ) ? $POST[ 'id' ] : null );

		extract( $variables );

		try {
			$result = $this->getConfig()->getDbal()->$crud( $sql, [ $id ] );
		} catch( Exception $e ) {
			$result = $e;
		} finally {
			return $result;
		}

	}

	public function redirect( $to = null )
	{

		$route = $this->getRoute();

		if ( is_null( $to ) ) {

			if ( $route->hasParameter( 'redirect' ) ) {
				$to = $route->getParameter( 'redirect' );

			} else {
				$to = '/admin/' . $this->get( 'table' ) . '/browse';

			}
		}

		$to = '/' . ltrim( $to, '/' );

		if ( $to !== $this->get( 'url' ) ) {
			header( 'Location: ' . $to );
		} elseif ( 'admin' !== $to ) {
			header( 'Location: ' . '/admin/' );
		} else {
			header( 'Location: /' );
		}

	}

	public function getVars( $phpFilterConstant = FILTER_SANITIZE_STRING )
	{

		$vars = [];

		if ( $this->submitted() ) {
			$vars = $this->getPost( $phpFilterConstant );
			$vars = $this->fromInput( $vars );

		}

		return $vars;

	}

	public function getPath( $phpFilterConstant )
	{

		$path = parse_url( $this->getRequest()->getRequestTarget(), PHP_URL_PATH );
		$path = explode( '/', trim( $path, '/' ) );

		foreach ( $get as $name => $var ) {
			$get[ $name ] = filter_var( $var, $phpFilterConstant );
		}

	}

	public function getGet( $phpFilterConstant )
	{

		$get = $_POST;
		foreach ( $get as $name => $var ) {
			$get[ $name ] = filter_var( $var, $phpFilterConstant );
		}

		return $get;

	}

	public function getPost( $phpFilterConstant )
	{

		$post = $_POST;
		foreach ( $post as $name => $var ) {
			$post[ $name ] = $this->filter( $post;
		}

		return $post;

	}

	public function fromSubmit( $input )
	{

		$submit = $this->fromInput( $input, 'submit' );
		return $submit;

	}

	public function fromHidden( $input )
	{

		$hidden = $this->fromInput( $input, 'hidden' );
		return $hidden;

	}

	public function fromInput( $input, $to )
	{

		if ( is_array( $input ) ) {
			throw new Exception( 'Frist arguments to "fromInput" method must be an array' );
		}

		$inputs = [];

		foreach ( $input as $name => $value ) {
			if ( 0 === strpos( $name, $to ) ) {
				$input = str_replace( "$to-", '', $name );
				$inputs[ $input ] = $value;
			}
		}

		return $inputs;

	}

	public function filter( $value, $phpFilterConstant )
	{

		try {
			$filtered = filter_var( $value, $phpFilterConstant );
			if ( ! $value ) {
				throw new Exception( sprintf( 'Unable to filter, %s', (string) $value ) );
			}
		} catch ( Exception $e ) {
			$filtered = $e;
		} finally {
			return $this->handle( $value );
		}

	}

	public function handle( $value )
	{

		if ( $value instanceof Exception ) {
			throw $value;
		}

		return $value;

	}


}
