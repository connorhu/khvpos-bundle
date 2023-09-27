<?php

namespace KHTools\VPosBundle\DependencyInjection;

use KHTools\VPos\Entities\Enums\Currency;
use KHTools\VPos\VPosClient;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('khvpos');

        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('test')
                    ->defaultTrue()
                ->end()
                ->scalarNode('mips_public_key_path')->defaultValue(null)->end()
                ->enumNode('version')
                    ->defaultValue(VPosClient::VERSION_REST_V1)
                    ->values([VPosClient::VERSION_REST_V1,])
                ->end()
                ->arrayNode('merchants')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->enumNode('currency')
                                ->defaultValue('HUF')
                                ->values(Currency::stringValues())
                            ->end()
                            ->scalarNode('private_key_path')->isRequired()->end()
                            ->scalarNode('private_key_passphrase')
                                ->defaultValue('')
                            ->end()
                            ->scalarNode('merchant_id')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}