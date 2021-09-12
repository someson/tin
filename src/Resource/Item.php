<?php

namespace Someson\TIN\Resource;

use Someson\TIN\Traits\Status;

final class Item
{
    use Status {
        Status::__construct as private __constructStatus;
    }

    /** @var ?string */
    public $value;

    /**
     * @param ?string $value
     * @param ?string $statusCode
     * @param string[] $translations
     */
    public function __construct(?string $value = null, ?string $statusCode = null, array $translations = [])
    {
        $this->value = $value ?: null;
        $this->__constructStatus($statusCode, $translations);
    }
}
