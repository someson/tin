<?php

namespace Someson\TIN;

class Params implements \Countable
{
    /** @var string[] */
    protected $_collection;

    /** @var array[] */
    private static $_schema = [
        'UstId_1' => [
            'required' => true,
            'description' => 'Ihre deutsche USt-IdNr.',
        ],
        'UstId_2' => [
            'required' => true,
            'description' => 'Anzufragende ausländische USt-IdNr.',
        ],
        'Firmenname' => [
            'required' => false,
            'description' => 'Name der anzufragenden Firma einschl. Rechtsform',
        ],
        'Ort' => [
            'required' => false,
            'description' => 'Ort der anzufragenden Firma',
        ],
        'PLZ' => [
            'required' => false,
            'description' => 'Postleitzahl der anzufragenden Firma',
        ],
        'Strasse' => [
            'required' => false,
            'description' => 'Strasse und Hausnummer der anzufragenden Firma',
        ],
        'Druck' => [
            'required' => false,
            'allowed' => false,
            'description' => 'mit/ohne amtlicher Bestätigungsmitteilung',
        ],
    ];

    /**
     * Params constructor.
     * @param string[] $params
     */
    public function __construct(array $params)
    {
        foreach (self::$_schema as $key => $settings) {
            if ($settings['required'] && ! isset($params[$key])) {
                throw new Exceptions\UnexpectedValueException(sprintf('Param [%s] required', $key));
            }
            $this->_collection[$key] = '';
            if (isset($params[$key]) && $params[$key]) {
                if (isset($settings['allowed'])&& ! $settings['allowed']) {
                    throw new Exceptions\DomainException(sprintf('Param [%s] not allowed', $key));
                }
                $this->_collection[$key] = trim($params[$key]);
            }
        }
    }

    /**
     * @param string $name
     * @return string
     */
    public function describe(string $name): string
    {
        return isset(self::$_schema[$name]) ? self::$_schema[$name]['description'] : '[unknown]';
    }

    /**
     * @return string[]
     */
    public function getCollection(): array
    {
        return $this->_collection;
    }

    public function count(): int
    {
        return \count($this->_collection);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function __get(string $name)
    {
        return $this->_collection[$name] ?? null;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        if (! isset(self::$_schema[$name])) {
            throw new Exceptions\UnexpectedValueException(sprintf('Param [%s] unknown', $name));
        }
        $this->_collection[$name] = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name)
    {
        return isset($this->_collection[$name]);
    }
}
