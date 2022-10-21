<?php

use nathanwooten\{

  Website\Application,
  Website\Templater\Template,
  Website\Templater\Template\BaseTemplate,
  Website\Http\Uri

};

$route = [
  [
    'id' => BaseTemplate::class
  ],
  [
    'id' => [
      BaseTemplate::class,
      '__toString'
    ]
  ],
  [
    'id' => [
      BaseTemplate::class
      'compile'
    ],
    'args' => [
      'result' => [
         BaseTemplate::class,
         '__toString'
      ]
    ]
  ]
];

function getBaseTemplate()
{

  $app = Registry::get( Application::class )->get( PROJECT_PATH );
  $app->get( BaseTemplate::class );

}

function parseId( $id, array $args = null )
{

  if ( is_string( $id ) ) {
    return $this->get( $id );
  }

  $class = $id[ 0 ];
  $method = $id[ 1 ];

  $container = $this->get( $id, 1 );
  $container->setMethod( $method, $args );

  $callback = $container->getMethod( $method );
  $result = $this->callback( $callback );

}





function isService( $key )
{

  return class_exists( $key );

}

function isArguments( array $array )
{

  if ( 0 !== array_search( reset( $array ), $array ) ) {
    return false;
  }

  return true;

}
