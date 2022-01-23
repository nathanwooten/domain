<?php

namespace nathanwooten\Application\Commands\Concrete;

use Exception;

use nathanwooten\{

	Application\Commands\AbstractAdminCommand

};

use nathanwooten\{

	Registry\Registry

};

class AdminCommand extends AbstractAdminCommand
{

	public function __invoke()
	{

		$template = $this->getTemplate( 'admin.php', $this->getConfig()->getDirectory( 'templates.admin' ) );

		if ( 'admin' === trim( $this->getConfig()->getRequest()->getRequestTarget(), '/' ) ) {

			$template .= 'Welcome to the Admin Area';

		} else {

			$form = new AdminFormCommand;

			$template .= $form();
		}

		$template .= '</div></section></body></html>';

		return $template;

	}

}

/*

*/