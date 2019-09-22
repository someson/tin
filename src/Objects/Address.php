<?php

namespace Someson\TIN\Objects;

use Someson\TIN\Resource\Item;

final class Address
{
    /** @var Item */
    private $_postalCode, $_locality, $_street;

    public function __construct(Item $postalCode, Item $locality, Item $street)
    {
        $this->_postalCode = $postalCode;
        $this->_locality = $locality;
        $this->_street = $street;
    }

    public function getPostalCode(): ?string
    {
        return $this->_postalCode->value;
    }

    public function getLocality(): ?string
    {
        return $this->_locality->value;
    }

    public function getStreet(): ?string
    {
        return $this->_street->value;
    }

    public function getPostalCodeStatus(bool $message = true): ?string
    {
        return $message ? $this->_postalCode->getStatusMessage() : $this->_postalCode->getStatusCode();
    }

    public function getLocalityStatus(bool $message = true): ?string
    {
        return $message ? $this->_locality->getStatusMessage() : $this->_locality->getStatusCode();
    }

    public function getStreetStatus(bool $message = true): ?string
    {
        return $message ? $this->_street->getStatusMessage() : $this->_street->getStatusCode();
    }
}
