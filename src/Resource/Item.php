<?php

namespace Someson\TIN\Resource;

use Someson\TIN\Traits\Status;

final class Item
{
    use Status {
        Status::__construct as private __constructStatus;
    }

    /** @var string */
    public $value;

    /**
     * Item constructor.
     * @param string|null $value
     * @param string|null $statusCode
     * @param array $translations
     */
    public function __construct(?string $value = null, ?string $statusCode = null, array $translations = [])
    {
        $this->value = $value ?: null;
        $this->__constructStatus($statusCode, $translations);
    }
}
