<?php

namespace Someson\TIN;

class Response
{
    public const SIMPLE = 1;
    public const QUALIFIED = 2;

    /** @var ?array */
    protected $_result;

    /** @var int */
    protected $_resultCode;

    /** @var string */
    protected $_locale;

    /** @var Interfaces\Translatable */
    protected $_translations;

    /**
     * Response constructor.
     * @param string $body
     * @param string $lang
     */
    public function __construct(string $body, string $lang = 'de')
    {
        $reg = '#<param>\s*<value><array><data>\s*<value><string>([^<]*)</string></value>\s*' .
               '<value><string>([^<]*)</string></value>\s*</data></array></value>\s*</param>#msi';

        if (! preg_match_all($reg, $body, $matches)) {
            throw new Exceptions\UnexpectedValueException('Parsing of response failed');
        }
        if (! $map = array_combine($matches[1], $matches[2])) {
            throw new Exceptions\UnexpectedValueException('Parsing of response failed');
        }
        $this->_result = array_map(function($item) {
            return $item ?: null;
        }, $map);
        $this->_resultCode = (int) ($this->_result['ErrorCode'] ?? 0);

        $className = sprintf('%s\Translations\\%s', __NAMESPACE__, $lang);
        if (! class_exists($className)) {
            throw new Exceptions\RuntimeException(sprintf('Translations for [%s] could not be loaded', $lang));
        }
        $this->_translations = new $className;
        $this->_locale = $className::$locale;
    }

    public function getDetails(): Resource\Details
    {
        return new Resource\Details($this->_result, $this->_translations::status());
    }

    public function getType(): int
    {
        return isset($this->_result->Firmenname, $this->_result->Ort) ? self::QUALIFIED : self::SIMPLE;
    }

    public function isValid(): bool
    {
        return $this->_resultCode === 200;
    }

    public function getMessage(): string
    {
        static $message = [
            200 => 'VALID',
            201 => 'INVALID',
            202 => 'INVALID_UNKNOWN',
            203 => 'INVALID_NOT_YET',
            204 => 'INVALID_OLD',
            205 => 'NO_RESPONSE',
            206 => 'INVALID_DE_TIN',
            207 => 'LIMITED_FUNCTIONALITY',
            208 => 'ALREADY_REQUESTED',
            209 => 'INVALID_COUNTRY_SCHEMA',
            210 => 'INVALID_DIGITS_SCHEMA',
            211 => 'INVALID_SYMBOL_SCHEMA',
            212 => 'INVALID_UNKNOWN_COUNTRY',
            213 => 'REQUEST_DE_TIN_FAILED',
            214 => 'WRONG_DE_TIN',
            215 => 'REQUEST_NOT_VALID_SIMPLE',
            216 => 'REQUEST_NOT_VALID_QUALIFIED',
            217 => 'RESPONSE_FAILED',
            218 => 'QUALIFIED_REQUEST_BLOCKED',
            219 => 'QUALIFIED_REQUEST_FAILED',
            220 => 'PRINT_REQUEST_FAILED',
            221 => 'REQUEST_WRONG',
            999 => 'MAINTENANCE',
        ];

        $messages = $this->_translations::message();
        if (isset($message[$this->_resultCode])) {
            if (\in_array($this->_resultCode, [206, 213, 214], true)) {
                return $this->_translations::common();
            }
            return $this->_verbose($messages[$message[$this->_resultCode]], $this->_locale);
        }
        return '[Unknown code]';
    }

    /**
     * @param string $message
     * @param string $locale
     * @return string
     */
    protected function _verbose(string $message, string $locale = 'de_DE'): string
    {
        $details = $this->getDetails();
        if ($from = $details->getValidity()->from()) {
            $formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT);
            $from = $formatter->format($from->getTimestamp());
        }
        if ($till = $details->getValidity()->till()) {
            $formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT);
            $till = $formatter->format($till->getTimestamp());
        }
        $replaces = [
            '%validFrom%' => $from,
            '%validTill%' => $till,
            '%myTIN%' => $details->getOwnTIN(),
            '%TIN%' => $details->getTIN(),
        ];
        return str_replace(array_keys($replaces), array_values($replaces), $message);
    }
}
