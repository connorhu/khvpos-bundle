<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use KHTools\VPos\VPosClientProviderInterface;
use KHTools\VPosBundle\Providers\VPosClientProvider;
use Psr\Http\Client\ClientInterface;

return static function (ContainerConfigurator $container) {
    $args = [];

    $args = [
        param('khvpos.vposclient_provider.config'),
        service(ClientInterface::class)
    ];

    $container->services()
        ->set('khvpos.vposclient_provider', VPosClientProvider::class)
            ->args($args)
            ->tag('container.hot_path')
        ->alias(VPosClientProviderInterface::class, 'khvpos.vposclient_provider');
};
