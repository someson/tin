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
     * @param array $details
     * @param array $messages
     */
    public function __construct(array $details, array $messages)
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
        if ($item('Datum') && $item('Uhrzeit')) {
            $this->_datetime = \DateTime::createFromFormat('d.m.Y H:i:s',
                sprintf('%s %s', $item('Datum'), $item('Uhrzeit'))
            );
        }
        if ($from = $item('Gueltig_ab')) {
            $from = \DateTime::createFromFormat('d.m.Y H:i:s', $from . ' 00:00:00');
        }
        if ($till = $item('Gueltig_bis')) {
            $till = \DateTime::createFromFormat('d.m.Y H:i:s', $till. ' 00:00:00');
        }
        $this->_validity = new Validity($from, $till);
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
