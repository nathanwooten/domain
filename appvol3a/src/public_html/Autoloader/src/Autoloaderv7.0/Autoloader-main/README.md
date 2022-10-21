
# nathanwooten/autoloader

## Install

```
php composer require nathanwooten/autoloader
```

or 

```
Download at https://github.com/nathanwooten/Autoloader
```

## Usage

```php

require_once '/path/to/Autoloader.php';

Autoloader::configure( 'Awesome\Package', 'path/to/awesome', '/optional_vendor_path' )

// or something like

foreach ( $by_vendor_path as $vendor_path => $namespaceDirectory ) ) {
  Autoloader::configure( $namespaceDirectory[0], $namespaceDirectory[1], $vendor_path );

// or really...

foreach ( $by_vendor_path as $vendor_path => $namespaceDirectory ) ) {
  $namespace = $namespaceDirectory[0];
  $directory = $namespaceDirectory[1];

  Autoloader::configure( $namespace, $namespace, $vendor_path );

  // load dependencies file and get contents
  $dependencies = Autoloader::load( $namespace . DIRECTORY_SEPARATOR . dependencies' );
  foreach ( $dependencies as $package ) {
    Autoloader::configure( ...array_values( $package ) );
  }
}
```

That's it for now...
