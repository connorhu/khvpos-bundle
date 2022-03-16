<?php

namespace KHTools\VPosBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class PaymentGatewayExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->setParameter('khvpos.vposclient_provider.config', $configs);

        $phpLoader = new PhpFileLoader($container, new FileLocator(\dirname(__DIR__).'/Resources/config'));

        $phpLoader->load('services.php');
    }

    public function getAlias(): string
    {
        return 'khvpos';
    }
}