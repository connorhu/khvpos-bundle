<?php

namespace KHTools\VPosBundle\Providers;

use KHTools\VPos\Entities\Merchant;
use KHTools\VPos\Exceptions\InvalidArgumentException;

interface MerchantProviderInterface
{
    public function getMerchant(string $currency): Merchant;
}