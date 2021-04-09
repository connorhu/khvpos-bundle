<?php

namespace KHBankTools\PaymentGatewayBundle;

use KHBankTools\PaymentGatewayBundle\DependencyInjection\PaymentGatewayExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PaymentGatewayBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new PaymentGatewayExtension();
    }
}