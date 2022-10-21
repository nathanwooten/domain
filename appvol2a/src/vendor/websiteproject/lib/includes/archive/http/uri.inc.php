<?php

$uri = getUriProperty( 'Uri', isset( $uri ) ? $uri : null, isset( $request ) ? $request : null );
return $uri;
