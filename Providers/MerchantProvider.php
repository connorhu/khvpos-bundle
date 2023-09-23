<?php

namespace KHTools\VPosBundle\Providers;

use KHTools\VPos\Entities\Merchant;
use KHTools\VPos\Exceptions\InvalidArgumentException;

class MerchantProvider implements MerchantProviderInterface
{
    /**
     * @param array<string, string> $merchantIds
     */
    public function __construct(private readonly array $merchantIds)
    {
    }

    /**
     * @param string $currency ISO 4217 code of the currency
     * @return Merchant
     */
    public function getMerchant(string $currency): Merchant
    {
        $merchant = new Merchant();
        $merchant->merchantId = $this->merchantIds[$currency] ?? throw new InvalidArgumentException(sprintf('Unsupported currency: "%s"', $currency));
        return $merchant;
    }
}