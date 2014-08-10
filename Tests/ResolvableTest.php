<?php

/*
 * This file is part of the Resolver library.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Resolver\Tests;

use CL\Resolver\Resolvable;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Cas Leentfaar
 */
class ResolvableMock
{
    use Resolvable;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $this->resolve($parameters);
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
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
            'number_of_doors'     => 'integer',
            'number_of_cilinders' => 'integer',
        ]);
        $resolver->setDefaults([
            'foo' => 'bar'
        ]);
    }
}

/**
 * @author Cas Leentfaar
 */
class ResolvableTest extends \PHPUnit_Framework_TestCase
{
    public function testIsInitiallyEmpty()
    {
        $resolvable = new ResolvableMock();
        $this->assertAttributeEmpty('parameters', $resolvable);

        return $resolvable;
    }

    /**
     * @covers  \CL\Resolver\Resolvable::resolve
     * @depends testIsInitiallyEmpty
     */
    public function testResolveSuccessful(ResolvableMock $resolvable)
    {
        $resolvable->setParameters(['number_of_cilinders' => 6, 'number_of_doors' => 5]);
        $resolved = $resolvable->getParameters();

        $this->assertEquals(6, $resolved['number_of_cilinders']);
        $this->assertEquals(5, $resolved['number_of_doors']);
        $this->assertEquals('bar', $resolved['foo']);
    }

    /**
     * @covers  \CL\Resolver\Resolvable::resolve
     * @depends testIsInitiallyEmpty
     * @expectedException \Symfony\Component\OptionsResolver\Exception\MissingOptionsException
     */
    public function testResolveFailed(ResolvableMock $resolvable)
    {
        $resolvable->setParameters(['foo' => 'bar']);
    }
}
