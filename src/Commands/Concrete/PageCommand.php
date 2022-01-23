<?php

namespace nathanwooten\Application\Commands\Concrete;

use nathanwooten\{

	Application\Commands\AbstractWebsiteCommand

};

use nathanwooten\{

	Registry\Registry,
	Templater\Templater

};

class PageCommand extends AbstractWebsiteCommand
{

	public function __invoke()
	{

		return $this->standard();

	}

}

/*
$config = Registry::get( 'config' );

$id = $config->target();

$dbal = $config->dbal();
$content = $dbal->select( 'select * from content where id=?', [ $id ] );

if ( ! isset( $content[0] ) ) {
	die();
}
$content = $content[0];

$templater = $config->templater();
$templater->setTemplates( $config->templates() );

$templater->setVariable( 'content', $content );

$page = $templater();
print $page;
die();
*/
