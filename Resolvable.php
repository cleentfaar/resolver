<?php

/*
 * This file is part of the Resolver library
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Resolver;

use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
trait Resolvable
{
    /**
     * The OptionsResolver instance that will define the configurable options.
     *
     * @var OptionsResolverInterface|null
     */
    protected $resolver;

    /**
     * @param array $options The options to resolve.
     *
     * @return array The resolved options.
     *
     * @throws OptionDefinitionException If configuring the resolver failed
     * @throws MissingOptionsException If resolving failed due to a missing option
     * @throws InvalidOptionsException If resolving failed due to an option with the wrong type or value
     */
    public function resolve(array $options)
    {
        if ($this->resolver === null) {
            try {
                $resolver = new OptionsResolver();
                $this->configureResolver($resolver);
                $this->resolver = $resolver;
            } catch (OptionDefinitionException $e) {
                throw $e;
            }
        }

        try {
            return $this->resolver->resolve($options);
        } catch (InvalidOptionsException $e) {
            throw $e;
        } catch (MissingOptionsException $e) {
            throw $e;
        }
    }

    /**
     * Configures the OptionsResolver which will constrain any options that it's given.
     *
     * @param OptionsResolverInterface $resolver
     */
    abstract protected function configureResolver(OptionsResolverInterface $resolver);
}
