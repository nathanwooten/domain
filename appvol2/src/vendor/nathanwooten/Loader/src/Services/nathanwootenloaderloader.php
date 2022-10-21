<?php

$load = '{
  "loads": [
    'id' => Autoloader::class,
    'properties' => [
      'autoloader',
      'loader'
    ]
    'callback' => 'load',
    'args' => [
      'string' => 'nathanwooten\Loader',
      'string' => 'Loader' . DIRECTORY_SEPARATOR . 'src',
      'string' => LIB_PATH . 'nathanwooten'
    ]
  ],
  [
    Loader::class,
    [
      'loader',
      'autoloader'
    ],
    'load',
    [
      'string' =>     

];
