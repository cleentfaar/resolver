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
     * @param array                         $options  The options to resolve.
     * @param OptionsResolverInterface|null $resolver Optional resolver to use, will be created otherwise.
     *
     * @return array The resolved options.
     *
     * @throws OptionDefinitionException If configuring the resolver failed (i.e. during 'configureResolver()')
     * @throws MissingOptionsException   If resolving failed due to a missing option
     * @throws InvalidOptionsException   If resolving failed due to an option with the wrong type or value
     */
    protected function resolve(array $options, OptionsResolverInterface $resolver = null)
    {
        if ($resolver === null) {
            try {
                $resolver = new OptionsResolver();
                $this->configureResolver($resolver);
            } catch (OptionDefinitionException $e) {
                throw $e;
            }
        }

        try {
            return $resolver->resolve($options);
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
