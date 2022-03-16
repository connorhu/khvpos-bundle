<?php

namespace KHTools\VPosBundle;

use KHTools\VPosBundle\DependencyInjection\PaymentGatewayExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class VPosBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new PaymentGatewayExtension();
    }
}