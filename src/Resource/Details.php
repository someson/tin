<?php

namespace Someson\TIN\Resource;

use Someson\TIN\Objects\{ Company, Address, Validity };

final class Details
{
    /** @var null|string */
    private $_tin, $_ownTin;

    /** @var Company */
    private $_company;

    /** @var Validity */
    private $_validity;

    /** @var null|\DateTime */
    private $_datetime;

    /**
     * Details constructor.
     * @param array|null $details
     * @param array $messages
     */
    public function __construct(?array $details, array $messages)
    {
        $item = function(string $key) use ($details): ?string {
            return $details[$key] ?? null;
        };
        $this->_ownTin = $item('UstId_1');
        $this->_tin = $item('UstId_2');

        $this->_company = new Company(
            new Item($item('Firmenname'), $item('Erg_Name'), $messages),
            new Address(
                new Item($item('PLZ'), $item('Erg_PLZ'), $messages),
                new Item($item('Ort'), $item('Erg_Ort'), $messages),
                new Item($item('Strasse'), $item('Erg_Str'), $messages)
            )
        );

        $date = $item('Datum');
        $time = $item('Uhrzeit');
        if ($date && $time) {
            $this->_datetime = \DateTime::createFromFormat('d.m.Y H:i:s', sprintf('%s %s', $date, $time)) ?: null;
        }

        $fromValue = $item('Gueltig_ab');
        $from = $fromValue ? \DateTime::createFromFormat('d.m.Y H:i:s', $fromValue . ' 00:00:00') : null;

        $tillValue = $item('Gueltig_bis');
        $till = $tillValue ? \DateTime::createFromFormat('d.m.Y H:i:s', $tillValue . ' 00:00:00') : null;

        $this->_validity = new Validity($from ?: null, $till ?: null);
    }

    public function getOwnTIN(): ?string
    {
        return $this->_ownTin;
    }

    public function getTIN(): ?string
    {
        return $this->_tin;
    }

    public function getCompany(): Company
    {
        return $this->_company;
    }

    public function getValidity(): Validity
    {
        return $this->_validity;
    }

    public function getDateTime(): ?\DateTime
    {
        return $this->_datetime;
    }
}
