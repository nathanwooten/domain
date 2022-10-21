<?php

$directory = getUriProperty( 'Directory', isset( $directory ) ? $directory : null, isset( $target ) ? $target : null );
return $directory;
