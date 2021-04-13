<?php

namespace KHTools\VPosBundle\Providers;

use KHTools\VPos\VPosClient;
use KHTools\VPos\VPosClientProviderInterface;
use KHTools\VPos\PaymentRequestArguments;
use KHTools\VPos\SignatureProvider;
use KHTools\VPos\TransactionInterface;
use Psr\Http\Client\ClientInterface;

class VPosClientProvider implements VPosClientProviderInterface
{
    /**
     * @var array
     */
    private array $config;

    /**
     * @var ClientInterface
     */
    private ClientInterface $httpClient;

    /**
     * @var SignatureProvider[]
     */
    private array $signatureProviders;

    /**
     * @var VPosClient[]
     */
    private array $paymentGateways;

    public function __construct(array $config, ClientInterface $httpClient)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;

        if (count($this->config[0]) === 0) {
            throw new \LogicException('VPosBundle is unconfigured. Please configure it!');
        }
    }

    protected function getSignatureProvider(string $privateKeyPath, string $privateKeyPassphrase = ''): SignatureProvider
    {
        $key = md5($privateKeyPath.$privateKeyPassphrase);

        if (!isset($this->signatureProviders[$key])) {
            $this->signatureProviders[$key] = new SignatureProvider($privateKeyPath, $privateKeyPassphrase);
        }

        return $this->signatureProviders[$key];
    }

    protected function getConfigOptionsWithCurrency(string $currency): array
    {
        foreach ($this->config as $config) {
            if ($config['currency'] === $currency) {
                return $config;
            }
        }

        throw new \LogicException(sprintf('VPosClient config not found for currency: "%s"', $currency));
    }

    protected function getPaymentGatewayWithCurrency(string $currency): VPosClient
    {
        if (!isset($this->paymentGateways[$currency])) {
            $config = $this->getConfigOptionsWithCurrency($currency);
            $signatureProvider = $this->getSignatureProvider($config['private_key_path'], $config['private_key_passphrase']);

            $this->paymentGateways[$currency] = new VPosClient($config['version'], $config['merchant_id'], $signatureProvider, $config['test'], $this->httpClient);
        }

        return $this->paymentGateways[$currency];
    }

    public function getPaymentGateway(TransactionInterface $transaction): VPosClient
    {
        $currencyIsoString = PaymentRequestArguments::transactionCurrencyConverter($transaction);

        return $this->getPaymentGatewayWithCurrency($currencyIsoString);
    }
}