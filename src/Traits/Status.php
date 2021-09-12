<?php

namespace Someson\TIN\Traits;

trait Status
{
    /** @var ?string */
    private $_statusCode;

    /** @var string[] */
    public $translations;

    /**
     * @param ?string $statusCode
     * @param string[] $translations
     */
    public function __construct(?string $statusCode = null, array $translations = [])
    {
        $this->_statusCode = $statusCode;
        $this->translations = $translations;
    }

    public function getStatusCode(): ?string
    {
        return $this->_statusCode;
    }

    public function getStatusMessage(): ?string
    {
        return $this->verbose($this->_statusCode);
    }

    /**
     * @param ?string $code
     * @return ?string
     */
    public function verbose(?string $code = null): ?string
    {
        static $types = [
            'A' => 'MATCH',
            'B' => 'NOT_MATCH',
            'C' => 'NOT_REQUESTED',
            'D' => 'UNDISCLOSED',
        ];
        if (! isset($code, $types[$code], $this->translations[$types[$code]])) {
            return null;
        }
        return $this->translations[$types[$code]];
    }
}
