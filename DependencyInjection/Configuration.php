<?php

namespace KHBankTools\PaymentGatewayBundle\DependencyInjection;

use KHBankTools\PaymentGateway\PaymentGateway;
use KHBankTools\PaymentGateway\PaymentRequestArguments;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('khvpos');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('payment_settings')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->enumNode('currency')
                                ->defaultValue(PaymentRequestArguments::CURRENCY_HUF)
                                ->values(PaymentRequestArguments::CURRENCIES)
                            ->end()
                            ->booleanNode('test')
                                ->defaultTrue()
                            ->end()
                            ->scalarNode('private_key_path')->isRequired()->end()
                            ->scalarNode('private_key_passphrase')
                                ->defaultValue('')
                            ->end()
                            ->enumNode('version')
                                ->defaultValue(PaymentGateway::VERSION_V1)
                                ->values(PaymentGateway::VERSIONS)
                            ->end()
                            ->integerNode('merchant_id')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}