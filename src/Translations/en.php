<?php

namespace Someson\TIN\Translations;

use Someson\TIN\Interfaces\Translatable;

final class en implements Translatable
{
    public static $locale = 'en_GB';

    /**
     * {@inheritdoc}
     */
    public static function common(): string
    {
        return 'The validation of the VAT identification number failed. Please check your entry or try again later.';
    }

    /**
     * {@inheritdoc}
     */
    public static function status(): array
    {
        return [
            'MATCH' => 'matched',
            'NOT_MATCH' => 'not matched',
            'NOT_REQUESTED' => 'not requested',
            'UNDISCLOSED' => 'from EU-Member undisclosed',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function message(): array
    {
        return [
            'VALID' => 'The requested VAT ID [%TIN%] is valid.',
            'INVALID' => 'The requested VAT ID [%TIN%] is invalid.',
            'INVALID_UNKNOWN' => 'The requested VAT ID [%TIN%] is invalid. It is not registered in the entrepreneur file of the EU Member State concerned. Note: Your business partner can obtain his valid VAT ID number from the tax authority responsible for him. He may have to submit an application for his VAT number to be included in the database.',
            'INVALID_NOT_YET' => 'The requested VAT ID [%TIN%] is invalid. It is only valid from the %validFrom%.',
            'INVALID_OLD' => 'The requested VAT ID [%TIN%] is invalid. It was valid in the period from %validFrom% until %validTill%.',
            'NO_RESPONSE' => 'Your request can currently not be answered by the requested EU Member State or for other reasons. Please try again later. In case of repeated problems, please contact the German Federal Central Tax Office in Saarlouis.',
            'INVALID_DE_TIN' => 'Your German VAT ID [%myTIN%] is invalid. A confirmation request is therefore not possible. You can find out the reason for this from the German Federal Central Tax Office in Saarlouis.',
            'LIMITED_FUNCTIONALITY' => 'They were granted the German VAT ID exclusively for the purpose of taxation of the intra-community acquisition. You are therefore not entitled to make confirmation requests.',
            'ALREADY_REQUESTED' => 'The VAT ID [%TIN%] you requested is currently being requested by another user. Processing is therefore not possible. Please try again later.',
            'INVALID_COUNTRY_SCHEMA' => 'The requested VAT ID [%TIN%] is invalid. It does not correspond to the structure that applies to this EU member state. (Structure of the VAT ID No. of all EU countries)',
            'INVALID_DIGITS_SCHEMA' => 'The requested VAT ID [%TIN%] is invalid. It does not comply with the check digit rules applicable to this EU member state.',
            'INVALID_SYMBOL_SCHEMA' => 'The requested VAT ID [%TIN%] is invalid. It contains invalid characters (such as spaces, periods, hyphens, and so on).',
            'INVALID_UNKNOWN_COUNTRY' => 'The requested VAT ID [%TIN%] is invalid. It contains an invalid country code.',
            'REQUEST_DE_TIN_FAILED' => 'It is not possible to request a German VAT ID [%myTIN%].',
            'WRONG_DE_TIN' => 'Your German VAT [%myTIN%] is incorrect. It begins with \'DE\' followed by 9 digits..',
            'REQUEST_NOT_VALID_SIMPLE' => 'Your request does not contain all necessary information for a simple confirmation request (your German VAT ID number and the foreign VAT ID number). Your request can therefore not be processed.',
            'REQUEST_NOT_VALID_QUALIFIED' => 'Your request does not contain all necessary information for a qualified confirmation request (your German VAT ID number, foreign VAT ID number, company name including legal form and location). Your request can therefore not be processed.',
            'RESPONSE_FAILED' => 'An error has occurred in the processing of the data from the requested EU Member State. Your request cannot therefore be processed.',
            'QUALIFIED_REQUEST_BLOCKED' => 'A qualified confirmation is currently not possible. A simple confirmation request was made with the following result: The requested VAT ID [%TIN%] is valid.',
            'QUALIFIED_REQUEST_FAILED' => 'An error occurred during the execution of the qualified confirmation request. A simple confirmation request was executed with the following result: The requested VAT ID [%TIN%] is valid.',
            'PRINT_REQUEST_FAILED' => 'An error occurred when requesting the official confirmation message. You will not receive a letter.',
            'REQUEST_WRONG' => 'The request data does not contain all necessary parameters or an invalid data type. For more information, see the notes on the interface call.',
            'MAINTENANCE' => 'It is currently not possible to process your request. Please try again later.',
        ];
    }
}
