<?php

namespace nathanwooten\Application\Commands;

use Exception;

abstract class AbstractWebsiteCommand extends AbstractCommand
{

	public function standard()
	{

		$content = $this->getContent();
		$directory = $this->getConfig()->getDirectory( 'templates' );

		$variables = [
			'directory' => $directory,
			'content' => $content
		];

		$template = $this->getTemplate( 'page', null, $variables );

		print $template;

	}

	public function getContent( $name = 'page' )
	{

		$config = $this->getConfig();

		$values = $this->getVars();

		$dbal = $config->getDbal( $config->connection() );
		$content = $dbal->select( $config->sql( $name ), array_values( $values ) );

		return $content;

	}

	public function getTemplate( $name = 'page.php', $directory = null, array $variables = [] )
	{

		if ( is_null( $directory ) ) {
			$directory = $this->getConfig()->getDirectory( 'templates' );
		}

		//$directory and $name can also be provided in the variables instead ( this overwrites the otherwise provided )
		extract( $variables );

		$file = $directory . $name;
		if ( ! is_file( $file ) || ! is_readable( $file ) ) {
			throw new Exception( sprintf( 'Unreadable template file, %s', $file ) );
		}

		ob_start();
		require $file;

		$body = ob_get_clean();

		return $body;

	}

	public function getVars( $phpFilterConstant = FILTER_SANITIZE_STRING )
	{

		$var = $this->getPath( $phpFilterConstant );
		return $var;

	}

}
