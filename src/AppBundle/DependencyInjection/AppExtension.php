<?php
/**
 * Created by PhpStorm.
 * User: carlosagudobelloso
 * Date: 23/10/16
 * Time: 17:29
 */

namespace AppBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\VarDumper\VarDumper;

class AppExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $configs An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $fileLocator = new FileLocator(__DIR__.'/../Resources/config/');
        $loader = new YamlFileLoader(
            $container,
            $fileLocator
        );
        $loader->load('services.yml');

    }
}