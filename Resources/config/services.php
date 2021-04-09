<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use KHBankTools\PaymenyGatewayBundle\Providers\PaymentGatewayProvider;
use KHBankTools\PaymentGateway\PaymentGatewayProviderInterface;
use Psr\Http\Client\ClientInterface;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('khbanktools.payment_gateway_provider', PaymentGatewayProvider::class)
            ->args([
                param('khbanktools.payment_gateway_provider.config'),
                service(ClientInterface::class)
            ])
            ->tag('container.hot_path')
        ->alias(PaymentGatewayProviderInterface::class, 'khbanktools.payment_gateway_provider');


};
