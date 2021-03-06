<?php

namespace Diaborg3Bundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $root = $builder->root('diaborg3');

        $root
            ->children()
                ->scalarNode('databackend')
                    ->defaultValue('JSON')
                    ->validate()->ifNotInArray(array('JSON', 'SQLITE'))->thenInvalid('invalid databackend.')
                ->end()
            ->end();

        return $builder;
    }
}