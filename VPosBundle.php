<?php

namespace KHTools\VPosBundle;

use KHTools\VPosBundle\DependencyInjection\PaymentGatewayExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class VPosBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new PaymentGatewayExtension();
    }
}