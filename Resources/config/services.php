<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use KHTools\VPosBundle\Providers\VPosClientProvider;
use KHTools\VPosBundle\VPosClientProviderInterface;
use Psr\Http\Client\ClientInterface;

return static function (ContainerConfigurator $container) {
    $args = [];

    if (function_exists('param')) {
        $args = [
            param('khvpos.vposclient_provider.config'),
            service(ClientInterface::class)
        ];
    }
    else {
        $args = [
            '%khvpos.vposclient_provider.config%',
            ref(ClientInterface::class),
        ];
    }

    $container->services()
        ->set('khvpos.vposclient_provider', VPosClientProvider::class)
            ->args($args)
            ->tag('container.hot_path')
        ->alias(VPosClientProviderInterface::class, 'khvpos.vposclient_provider');
};
