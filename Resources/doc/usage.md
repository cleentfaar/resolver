# Using the Resolver library

This library merely serves as a wrapper for [Symfony's OptionsResolver](https://github.com/symfony/OptionsResolver)
component. If you do not know it yet (shame on you!), check out it's [documentation](https://github.com/symfony/OptionsResolver/README.md)
to get a better picture of what you will be getting.


## Making your classes configurable

In this example we will pretend to be working on an application for a car-dealer.

The cars for this dealer have their own table (or document) in the database. Besides a 'brand' and 'model' column,
 it may have a separate 'parameters' field, that holds additional parameters for the given car (json-encoded):

```json
{
    "number_of_doors": 2,
    "number_of_cilinders": 6
}
```

To work with the cars the dealer has, you might have a class named `Car` like this:
```php
<?php
// src/Acme/CarDealer/Model/Car.php

namespace Acme\CarDealer\Model;

class Car
{
    /**
     * @var Brand
     */
    private $brand;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @param Brand $brand
     */
    public function setBrand(Brand $brand)
    {
        $this->brand = $brand;
    }

    /**
     * @param Model $model
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters; // hmmm... what goes in here?
    }
}

```

But how would you deal with the values stored in the parameters? Since they are basically just a bunch of key/value
pairs, how could you be sure that the correct values are always set and are of the correct type?

Well this is where the `OptionsResolver` comes in handy, together with the `Resolvable` trait in this library, we can
change the `Car` class mentioned above to allow for configurable options that follow a strict scheme:

```php
<?php
// src/Acme/CarDealer/Model/Car.php

namespace Acme\CarDealer\Model;

use CL\Resolver\Resolvable;

class Car
{
    use Resolvable;

    // ... earlier code repeats here ...

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $this->resolve($parameters); // ahhh... peace at last!
    }

    /**
     * {@inheritdoc}
     */
    public function configureResolver(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired([
            'number_of_doors',
            'number_of_cilinders',
        ]);
        $resolver->setAllowedTypes([
            'number_of_doors' => 'integer',
            'number_of_cilinders' => 'integer',
        ]);
    }
}

```

See? With just a few small changes we have made the parameters field much more robust and consistent. No matter how
crazy the values get you can always be sure they are of the type and value that you have defined them to be beforehand
(otherwise you will receive an exception).

The place where you use the `resolve()`-call is up to you. In some cases you might want to have the parameters resolved
during construction, like so:

```php
<?php
// src/Acme/CarDealer/Model/Car.php

namespace Acme\CarDealer\Model;

use CL\Resolver\Resolvable;

class Car
{
    use Resolvable;

    /**
     * @var array
     */
    private $parameters;

    // ...

    public function __construct(array $parameters)
    {
        $this->parameters = $this->resolve($parameters);
    }

    // ...

    /**
     * {@inheritdoc}
     */
    public function configureResolver(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired([
            'number_of_doors',
            'number_of_cilinders',
        ]);
        $resolver->setAllowedTypes([
            'number_of_doors' => 'integer',
            'number_of_cilinders' => 'integer',
        ]);
    }
}

```

Now your object will resolve the given parameters during construction, rather than during a `setParameters()`-call.
Don't forget to check out the documentation about the [OptionResolver itself](https://github.com/symfony/OptionsResolver/README.md)
to find out more ways to constrain your options with.
