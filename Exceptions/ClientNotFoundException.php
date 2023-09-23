<?php

namespace KHTools\VPosBundle\Exceptions;

class ClientNotFoundException extends \RuntimeException
{
    public function __construct(string $isoCurrency)
    {
        parent::__construct(sprintf('VPos Client not found with currency: "%s"', $isoCurrency));
    }
}