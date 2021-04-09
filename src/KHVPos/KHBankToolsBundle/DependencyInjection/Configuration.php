<?php

namespace KHVPos\KHBankToolsBundle\DependencyInjection;

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
                                ->values(PaymentRequestArguments::CURRENCIES)
                            ->end()
                            ->booleanNode('test')
                                ->defaultTrue()
                            ->end()
                            ->scalarNode('private_key_path')->end()
                            ->scalarNode('private_key_passphrase')->end()
                            ->enumNode('version')
                                ->values(PaymentGateway::VERSIONS)
                            ->end()
                            ->integerNode('merchant_id')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}