<?php
declare(strict_types=1);

namespace Algetar\Nsu\Tests\Components;

use Algetar\Nsu\Components\ParseNumber;
use Algetar\Nsu\Exception\InvalidNumberFormat;
use PHPUnit\Framework\TestCase;

class ParseNumberTest extends TestCase
{
    /**
     * @dataProvider provideData
     * @return void
     * @throws InvalidNumberFormat
     */
    public function testParse($numberValue, $type, $int, $frac)
    {
        $number = ParseNumber::parse($numberValue);

        self::assertEquals($type, $number->type());
        self::assertEquals($int, $number->integer());
        self::assertEquals($frac, $number->decimal());
    }

    public function testInvalidNumberFormat()
    {
        self::expectException(InvalidNumberFormat::class);

        ParseNumber::parse(false);
        ParseNumber::parse([]);
    }

    public function provideData(): array
    {
        return [
            [
                0,ParseNumber::TYPE_INT,0,0
            ],
            [
                0.23,ParseNumber::TYPE_FLOAT,0,23
            ],
            [
                '14522',ParseNumber::TYPE_INT,14522,0
            ],
            [
                '156.56',ParseNumber::TYPE_FLOAT,156,56
            ],
            [
                101.23,ParseNumber::TYPE_FLOAT,101,23
            ],
        ];
    }
}
