<?php
/**
 * Created by PhpStorm.
 * User: bestform
 * Date: 7/10/14
 * Time: 8:32 PM
 */

namespace Diaborg3Bundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class Diaborg3Extension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $config An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->processConfiguration(new Configuration(), $config);

        switch($configuration['databackend']){
            case('JSON'):
                $definition = $container->getDefinition('repository');
                $definition->setClass('Diaborg3Bundle\Data\DiaborgRepositoryJSON');
                break;
            case('SQLITE'):
                $definition = $container->getDefinition('repository');
                $definition->setClass('Diaborg3Bundle\Data\DiaborgRepositoryDatabase');

                $definition->addArgument(new Reference('doctrine'));
                break;
        }
    }
}