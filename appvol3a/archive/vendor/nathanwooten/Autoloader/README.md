
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

Autoloader::configure( 'Awesome\Package', 'path/to/awesome', '/vendor' )

// or something like

foreach ( $vendor_path => [ $namespace => $directory ] ) ) {
  Autoloader::configure( $namespace, $directory, $vendor_path );
}

// or really...

foreach ( $vendor_path => [ $namespace => $directory ] ) ) {
  Autoloader::configure( $namespace, $directory, $vendor_path );
}

$dependencies = Autoloader::load( 'Awesome\Package\dependencies' );
foreach ( $dependencies as $package ) {
  Autoloader::configure( ...array_values( $package ) );
}
```
