<?php

$target = getUriProperty( 'Target', isset( $target ) ? $target : null, isset( $uri ) ? $uri : null );
return $target;
