<?php

namespace Tests\Unit;

use Codeception\Test\Unit;
use Someson\TIN\Params;
use Someson\TIN\Exceptions\{ UnexpectedValueException, DomainException };

class ClientParamsTest extends Unit
{
    /** @var \UnitTester */
    protected $tester;

    public function testParamsCanThrowExceptions(): void
    {
        $this->tester->expectThrowable(UnexpectedValueException::class, function() {
            new Params([]);
        });
        $this->tester->expectThrowable(DomainException::class, function() {
            new Params([
                'UstId_1' => 'DE123456789',
                'UstId_2' => 'DE789456132',
                'Druck' => true,
            ]);
        });
    }

    public function testParamsMustBeNotEmpty(): void
    {
        $params = new Params([
            'UstId_1' => 'DE123456789',
            'UstId_2' => 'DE789456132',
        ]);
        $this->assertNotEmpty($params->getCollection());
    }

    public function testParamsShouldBeDescribable(): void
    {
        $params = new Params([
            'UstId_1' => 'DE123456789',
            'UstId_2' => 'DE789456132',
        ]);
        foreach ($params->getCollection() as $description) {
            $this->assertNotEquals('[unknown]', $description);
        }
    }

    public function testCheckMagicFunctionality(): void
    {
        $params = new Params([
            'UstId_1' => 'DE123456789',
            'UstId_2' => 'DE789456132',
        ]);
        $this->assertCount(7, $params);
        $this->assertEquals('DE123456789', $params->UstId_1);
        $this->assertEmpty($params->Firmenname);

        $params->Firmenname = 'TestName GmbH.';
        $this->assertEquals('TestName GmbH.', $params->Firmenname);
        $this->assertTrue(isset($params->Firmenname));

        $this->assertEquals('Ort der anzufragenden Firma', $params->describe('Ort'));

        $this->tester->expectThrowable(UnexpectedValueException::class, function() use ($params) {
            $params->NotExistedKey = 1;
        });
    }
}
