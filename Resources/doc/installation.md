# Installation

## Step 1) Get the library

Installation is easy and can be done either by using Composer or as a Git submodule:


### Method a) As a Composer dependency

Add the following to your ``composer.json`` (see http://getcomposer.org/)
```json
"require":  {
    "cleentfaar/resolver": "~0.1"
}
```

### Method b) As a Git submodule

Run the following commands to bring in the library as a submodule.
```
git submodule add https://github.com/cleentfaar/decorator.git vendor/cleentfaar/resolver
```


## Step 2) Register the namespaces

If you installed the bundle by composer, the namespace will be registered automatically (jump to step 3).

Otherwise, add the following two namespace entries to the `registerNamespaces` call in your autoloader:
```php
<?php
// path/to/your/autoload.php
$loader->registerNamespaces(array(
    // ...
    'CL\Resolver' => __DIR__.'/../vendor/cleentfaar/decorator',
    // ...
));
```


## Step 3) Ready?

Check out the [usage documentation](usage.md)!
