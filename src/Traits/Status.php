<?php

namespace Someson\TIN\Traits;

trait Status
{
    /** @var string */
    private $_statusCode;

    /** @var array */
    public $translations;

    /**
     * Status constructor.
     * @param string|null $statusCode
     * @param array $translations
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
     * @param string|null $code
     * @return string
     */
    public function verbose(?string $code = null): string
    {
        if (! $code) {
            return null;
        }
        static $types = [
            'A' => 'MATCH',
            'B' => 'NOT_MATCH',
            'C' => 'NOT_REQUESTED',
            'D' => 'UNDISCLOSED',
        ];
        return isset($types[$code]) ? ($this->translations[$types[$code]] ?? null) : null;
    }
}
