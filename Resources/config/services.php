<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use KHTools\VPosBundle\Providers\VPosClientProvider;
use KHTools\VPosBundle\VPosClientProviderInterface;
use Psr\Http\Client\ClientInterface;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('khvpos.vposclient_provider', VPosClientProvider::class)
            ->args([
                param('khvpos.vposclient_provider.config'),
                service(ClientInterface::class)
            ])
            ->tag('container.hot_path')
        ->alias(VPosClientProviderInterface::class, 'khvpos.vposclient_provider');


};
