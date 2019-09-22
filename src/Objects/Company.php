<?php

namespace Someson\TIN\Objects;

use Someson\TIN\Resource\Item;
use Someson\TIN\Traits\Status;

final class Company
{
    use Status {
        Status::__construct as private __constructStatus;
    }

    /** @var ?string */
    private $_name;

    /** @var Address */
    private $_address;

    /**
     * Company constructor.
     * @param Item $item
     * @param Address $address
     */
    public function __construct(Item $item, Address $address)
    {
        $this->_name = $item->value;
        $this->_address = $address;
        $this->__constructStatus($item->getStatusCode(), $item->translations);
    }

    public function getName(): ?string
    {
        return $this->_name;
    }

    public function getAddress(): Address
    {
        return $this->_address;
    }
}
