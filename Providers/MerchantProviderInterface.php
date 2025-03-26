<?php

namespace KHTools\VPosBundle\Providers;

use KHTools\VPos\Models\Merchant;

interface MerchantProviderInterface
{
    public function getMerchant(string $currency): Merchant;
}
