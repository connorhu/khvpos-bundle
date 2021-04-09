<?php

namespace KHBankTools\PaymentGatewayBundle\Providers;

use KHBankTools\PaymentGateway\PaymentGateway;
use KHBankTools\PaymentGateway\PaymentGatewayProviderInterface;
use KHBankTools\PaymentGateway\PaymentRequestArguments;
use KHBankTools\PaymentGateway\SignatureProvider;
use KHBankTools\PaymentGateway\TransactionInterface;
use Psr\Http\Client\ClientInterface;

class PaymentGatewayProvider implements PaymentGatewayProviderInterface
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
     * @var PaymentGateway[]
     */
    private array $paymentGateways;

    public function __construct(array $config, ClientInterface $httpClient)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;

        if (count($this->config[0]) === 0) {
            throw new \LogicException('KHPaymentGatewayBundle is unconfigured. Please configure it!');
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

        throw new \LogicException(sprintf('PaymentGateway config not found for currency: "%s"', $currency));
    }

    protected function getPaymentGatewayWithCurrency(string $currency): PaymentGateway
    {
        if (!isset($this->paymentGateways[$currency])) {
            $config = $this->getConfigOptionsWithCurrency($currency);
            $signatureProvider = $this->getSignatureProvider($config['private_key_path'], $config['private_key_passphrase']);

            $this->paymentGateways[$currency] = new PaymentGateway($config['version'], $config['merchant_id'], $signatureProvider, $config['test'], $this->httpClient);
        }

        return $this->paymentGateways[$currency];
    }

    public function getPaymentGateway(TransactionInterface $transaction): PaymentGateway
    {
        $currencyIsoString = PaymentRequestArguments::transactionCurrencyConverter($transaction);

        return $this->getPaymentGatewayWithCurrency($currencyIsoString);
    }
}