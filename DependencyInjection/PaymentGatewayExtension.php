<?php

namespace KHTools\VPosBundle\DependencyInjection;

use KHTools\VPos\Keys\PrivateKey;
use KHTools\VPos\SignatureProvider;
use KHTools\VPos\SignatureProviderInterface;
use KHTools\VPos\VPosClient;
use KHTools\VPosBundle\Providers\MerchantProvider;
use KHTools\VPosBundle\Providers\MerchantProviderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class PaymentGatewayExtension extends Extension
{
    private function getBundledMipsPublicKeyPath(bool $sandbox): string
    {
        return sprintf('%s/Resources/keys/mips_pay%s.khpos.hu.pub', \dirname(__DIR__), $sandbox ? '.sandbox' : '');
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('khvpos.client_provider.config', $config);

        $this->setupSignatureProvider($config, $container);
        $this->setupVPosClient($config, $container);
        $this->setupMerchantProvider($config, $container);
    }

    protected function setupVPosClient(array $configuration, ContainerBuilder $container): void
    {
        $container->register('khvpos.vpos_client', VPosClient::class)
            ->setArguments([
                $configuration['version'],
                $configuration['test'],
            ])
            ->setAutoconfigured(true)
            ->setAutowired(true)
        ;

        $container->setAlias(VPosClient::class, 'khvpos.vpos_client');
    }

    protected function setupSignatureProvider(array $configuration, ContainerBuilder $container): void
    {
        $mipsPublicKeyPath = $configuration['mips_public_key_path'] ?? $this->getBundledMipsPublicKeyPath($configuration['test']);

        $definition = $container->register('khvpos.signature_provider', SignatureProvider::class)
            ->setArguments([
                [],
                $mipsPublicKeyPath,
            ])
            ->addTag('khvpos.signature_provider')
            ->setLazy(true)
        ;

        foreach ($configuration['merchants'] as $clientConfig) {
            $definition->addMethodCall('addPrivateKey', [$clientConfig['merchant_id'], $clientConfig['private_key_path'], $clientConfig['private_key_passphrase']]);
        }

        $container->setAlias(SignatureProviderInterface::class, 'khvpos.signature_provider');
    }

    protected function setupMerchantProvider(array $configuration, ContainerBuilder $container): void
    {
        $merchantProviderConfig = [];
        foreach ($configuration['merchants'] as $clientConfig) {
            $merchantProviderConfig[$clientConfig['currency']] = $clientConfig['merchant_id'];
        }

        $container->register('khvpos.merchant_provider', MerchantProvider::class)
            ->setArgument(0, $merchantProviderConfig);

        $container->setAlias(MerchantProviderInterface::class, 'khvpos.merchant_provider');
    }

    public function getAlias(): string
    {
        return 'khvpos';
    }
}