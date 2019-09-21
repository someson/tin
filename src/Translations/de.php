<?php

namespace Someson\TIN\Translations;

use Someson\TIN\Interfaces\Translatable;

final class de implements Translatable
{
    public static $locale = 'de_DE';

    /**
     * {@inheritdoc}
     */
    public static function common(): string
    {
        return 'Die Validierung der Umsatzsteuer-Identifikationsnummer ist fehlgeschlagen. Bitte prüfe deine Eingabe oder versuche es zu einem späteren Zeitpunkt noch einmal.';
    }

    /**
     * {@inheritdoc}
     */
    public static function status(): array
    {
        return [
            'MATCH' => 'stimmt überein',
            'NOT_MATCH' => 'stimmt nicht überein',
            'NOT_REQUESTED' => 'nicht angefragt',
            'UNDISCLOSED' => 'vom EU-Mitgliedsstaat nicht mitgeteilt'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function message(): array
    {
        return [
            'VALID' => 'Die angefragte USt-IdNr. [%TIN%] ist gültig.',
            'INVALID' => 'Die angefragte USt-IdNr. [%TIN%] ist ungültig.',
            'INVALID_UNKNOWN' => 'Die angefragte USt-IdNr. [%TIN%] ist ungültig. Sie ist nicht in der Unternehmerdatei des betreffenden EU-Mitgliedstaates registriert. Hinweis: Ihr Geschäftspartner kann seine gültige USt-IdNr. bei der für ihn zuständigen Finanzbehörde in Erfahrung bringen. Möglicherweise muss er einen Antrag stellen, damit seine USt-IdNr. in die Datenbank aufgenommen wird.',
            'INVALID_NOT_YET' => 'Die angefragte USt-IdNr. [%TIN%] ist ungültig. Sie ist erst ab dem %validFrom% gültig.',
            'INVALID_OLD' => 'Die angefragte USt-IdNr. [%TIN%] ist ungültig. Sie war im Zeitraum von %validFrom% bis %validTill% gültig.',
            'NO_RESPONSE' => 'Ihre Anfrage kann derzeit durch den angefragten EU-Mitgliedstaat oder aus anderen Gründen nicht beantwortet werden. Bitte versuchen Sie es später noch einmal. Bei wiederholten Problemen wenden Sie sich bitte an das Bundeszentralamt für Steuern - Dienstsitz Saarlouis.',
            'INVALID_DE_TIN' => 'Ihre deutsche USt-IdNr. [%myTIN%] ist ungültig. Eine Bestätigungsanfrage ist daher nicht möglich. Den Grund hierfür können Sie beim Bundeszentralamt für Steuern - Dienstsitz Saarlouis - erfragen.',
            'LIMITED_FUNCTIONALITY' => 'Ihnen wurde die deutsche USt-IdNr. ausschliesslich zu Zwecken der Besteuerung des innergemeinschaftlichen Erwerbs erteilt. Sie sind somit nicht berechtigt, Bestätigungsanfragen zu stellen.',
            'ALREADY_REQUESTED' => 'Für die von Ihnen angefragte USt-IdNr. [%TIN%] läuft gerade eine Anfrage von einem anderen Nutzer. Eine Bearbeitung ist daher nicht möglich. Bitte versuchen Sie es später noch einmal.',
            'INVALID_COUNTRY_SCHEMA' => 'Die angefragte USt-IdNr. [%TIN%] ist ungültig. Sie entspricht nicht dem Aufbau der für diesen EU-Mitgliedstaat gilt. (Aufbau der USt-IdNr. aller EU-Länder)',
            'INVALID_DIGITS_SCHEMA' => 'Die angefragte USt-IdNr. [%TIN%] ist ungültig. Sie entspricht nicht den Prüfziffernregeln die für diesen EU-Mitgliedstaat gelten.',
            'INVALID_SYMBOL_SCHEMA' => 'Die angefragte USt-IdNr. [%TIN%] ist ungültig. Sie enthält unzulässige Zeichen (wie z.B. Leerzeichen oder Punkt oder Bindestrich usw.).',
            'INVALID_UNKNOWN_COUNTRY' => 'Die angefragte USt-IdNr. [%TIN%] ist ungültig. Sie enthält ein unzulässiges Länderkennzeichen.',
            'REQUEST_DE_TIN_FAILED' => 'Die Abfrage einer deutschen USt-IdNr. [%myTIN%] ist nicht möglich.',
            'WRONG_DE_TIN' => 'Ihre deutsche USt-IdNr. [%myTIN%] ist fehlerhaft. Sie beginnt mit \'DE\' gefolgt von 9 Ziffern.',
            'REQUEST_NOT_VALID_SIMPLE' => 'Ihre Anfrage enthält nicht alle notwendigen Angaben für eine einfache Bestätigungsanfrage (Ihre deutsche USt-IdNr. und die ausl. USt-IdNr.). Ihre Anfrage kann deshalb nicht bearbeitet werden.',
            'REQUEST_NOT_VALID_QUALIFIED' => 'Ihre Anfrage enthält nicht alle notwendigen Angaben für eine qualifizierte Bestätigungsanfrage (Ihre deutsche USt-IdNr., die ausl. USt-IdNr., Firmenname einschl. Rechtsform und Ort). Ihre Anfrage kann deshalb nicht bearbeitet werden.',
            'RESPONSE_FAILED' => 'Bei der Verarbeitung der Daten aus dem angefragten EU-Mitgliedstaat ist ein Fehler aufgetreten. Ihre Anfrage kann deshalb nicht bearbeitet werden.',
            'QUALIFIED_REQUEST_BLOCKED' => 'Eine qualifizierte Bestätigung ist zur Zeit nicht möglich. Es wurde eine einfache Bestätigungsanfrage mit folgendem Ergebnis durchgeführt: Die angefragte USt-IdNr. [%TIN%] ist gültig.',
            'QUALIFIED_REQUEST_FAILED' => 'Bei der Durchführung der qualifizierten Bestätigungsanfrage ist ein Fehler aufgetreten. Es wurde eine einfache Bestätigungsanfrage mit folgendem Ergebnis durchgeführt: Die angefragte USt-IdNr. [%TIN%] ist gültig.',
            'PRINT_REQUEST_FAILED' => 'Bei der Anforderung der amtlichen Bestätigungsmitteilung ist ein Fehler aufgetreten. Sie werden kein Schreiben erhalten.',
            'REQUEST_WRONG' => 'Die Anfragedaten enthalten nicht alle notwendigen Parameter oder einen ungültigen Datentyp. Weitere Informationen erhalten Sie bei den Hinweisen zum Schnittstelle - Aufruf.',
            'MAINTENANCE' => 'Eine Bearbeitung Ihrer Anfrage ist zurzeit nicht möglich. Bitte versuchen Sie es später noch einmal.',
        ];
    }
}
