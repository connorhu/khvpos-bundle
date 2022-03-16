<?php

namespace KHTools\VPosBundle\DependencyInjection;

use KHTools\VPos\VPosClient;
use KHTools\VPos\PaymentRequestArguments;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
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
                                ->defaultValue(VPosClient::VERSION_V1)
                                ->values(VPosClient::VERSIONS)
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