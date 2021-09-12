<?php

namespace Someson\TIN\Objects;

use DateTime;

final class Validity
{
    /** @var ?DateTime */
    private $_from;

    /** @var ?DateTime */
    private $_till;

    public function __construct(?DateTime $from = null, ?DateTime $till = null)
    {
        $this->_from = $from;
        $this->_till = $till;
    }

    public function from(): ?DateTime
    {
        return $this->_from;
    }

    public function till(): ?DateTime
    {
        return $this->_till;
    }
}
