<?php // ./vendor/bin/codecept run unit library/tests/unit/TIN/DetailsTest -c library --env=de -d

namespace Tests\Unit;

use Codeception\Test\Unit;
use Someson\TIN\Translations\de;
use Someson\TIN\Resource\Details;

class DetailsTest extends Unit
{
    /** @var \UnitTester */
    protected $tester;

    protected function _before()
    {
    }

    public function testMustPopulateData(): void
    {
        $fixture = $this->_validResponseFixture()[0];
        $details = new Details($fixture, de::status());

        $this->assertEquals($details->getOwnTIN(), $fixture['UstId_1']);
        $this->assertEquals($details->getTIN(), $fixture['UstId_2']);
        $this->assertInstanceOf(\DateTime::class, $details->getDateTime());
    }

    public function testShouldBeExpired(): void
    {
        $fixture = $this->_validResponseFixture()[0];
        $details = new Details($fixture, de::status());
        $validity = $details->getValidity();

        $this->assertEquals((new \DateTime($fixture['Gueltig_ab']))->getTimestamp(), $validity->from()->getTimestamp());
        $this->assertEquals((new \DateTime($fixture['Gueltig_bis']))->getTimestamp(), $validity->till()->getTimestamp());
    }

    public function testAwareOfRequestTime(): void
    {
        $fixture = $this->_validResponseFixture()[0];
        $details = new Details($fixture, de::status());
        $requestedAt = $details->getDateTime();

        $dt = sprintf('%s %s', $fixture['Datum'], $fixture['Uhrzeit']);
        $this->assertEquals((new \DateTime($dt))->getTimestamp(), $requestedAt->getTimestamp());
    }

    public function testDescribeAddress(): void
    {
        $fixture = $this->_validResponseFixture()[1];
        $details = new Details($fixture, de::status());
        $company = $details->getCompany();

        $this->assertEquals($company->getAddress()->getLocality(), 'Nürnberg');
        $this->assertEquals($company->getAddress()->getPostalCode(), '90409');

        $this->assertNull($company->getAddress()->getStreet());
        $this->assertNull($details->getCompany()->getName());
    }

    public function testCompanyNameCanHaveStatus(): void
    {
        $fixture = $this->_validResponseFixture()[1];
        $details = new Details($fixture, de::status());
        $company = $details->getCompany();

        $this->assertEquals($company->getStatusCode(), 'C');
        $this->assertEquals($company->getStatusMessage(), 'nicht angefragt');
    }

    public function testCompanyAddressCanHaveStatus(): void
    {
        $fixture = $this->_validResponseFixture()[1];
        $details = new Details($fixture, de::status());
        $address = $details->getCompany()->getAddress();

        $this->assertEquals($address->getPostalCodeStatus(false), 'A');
        $this->assertEquals($address->getPostalCodeStatus(), 'stimmt überein');

        $this->assertEquals($address->getLocalityStatus(false), 'A');
        $this->assertEquals($address->getLocalityStatus(), 'stimmt überein');

        $this->assertEquals($address->getStreetStatus(false), 'C');
        $this->assertEquals($address->getStreetStatus(), 'nicht angefragt');
    }

    public function _validResponseFixture(): array
    {
        /** @noinspection PhpIncludeInspection */
        return require sprintf('%s/details.php', FIXTURES);
    }
}
