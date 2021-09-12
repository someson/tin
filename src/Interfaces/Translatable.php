<?php

namespace Someson\TIN\Interfaces;

interface Translatable
{
    /**
     * Pack of Status messages [A|B|C|D] for company attributes (name, address)
     * @return string[]
     */
    public static function status(): array;

    /**
     * Pack of response messages
     * @return string[]
     */
    public static function message(): array;

    /**
     * Common message
     * @return string
     */
    public static function common(): string;
}
