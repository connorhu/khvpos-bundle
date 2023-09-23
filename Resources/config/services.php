<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use KHTools\VPos\VPosClientProviderInterface;
use KHTools\VPosBundle\Providers\VPosClientProvider;
use Psr\Http\Client\ClientInterface;

return static function (ContainerConfigurator $container) {

    $args = [
        param('khvpos.client_provider.config'),
        service(ClientInterface::class)
    ];

    $services = $container->services();

    $services
        ->set('khvpos.vpos_client_provider', VPosClientProvider::class)
            ->args($args)
            ->tag('container.hot_path')
        ->alias(VPosClientProviderInterface::class, 'khvpos.client_provider');
};
