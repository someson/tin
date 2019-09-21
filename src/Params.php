<?php

namespace Someson\TIN;

class Params implements \Countable
{
    /** @var array */
    protected $_collection;

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
     * @param array $params
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

    public function getCollection(): array
    {
        return $this->_collection;
    }

    public function count(): int
    {
        return \count($this->_collection);
    }

    public function __get($name)
    {
        return $this->_params[$name] ?? null;
    }

    public function __set($name, $value)
    {
        if (isset(self::$_schema[$name])) {
            $this->_collection[$name] = $value;
        }
        throw new Exceptions\UnexpectedValueException(sprintf('Param [%s] unknown', $name));
    }

    public function __isset($name)
    {
        return isset($this->_collection[$name]);
    }
}
