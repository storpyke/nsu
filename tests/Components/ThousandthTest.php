<?php

namespace Algetar\Nsu\Tests\Components;

use Algetar\Nsu\Components\Thousandth;
use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Spell;
use PHPUnit\Framework\TestCase;

class ThousandthTest extends TestCase
{
    /**
     * @dataProvider provideData
     * @return void
     * @throws UnknownIndexException
     */
    public function testSpell($number, $gender, $expect)
    {
        $thousand = new Thousandth();
        if ($number === 0) {
            $result = $thousand->spellZero($gender);
        } else {
            $result = $thousand->spell($number, $gender);
        }
        self::assertEquals($expect, $result);
    }

    public function provideData(): array
    {
        return [
            [
                0,
                Spell::GENDER_FEMALE,
                'ноль'
            ],
            [
                1,
                Spell::GENDER_MALE,
                'один'
            ],
            [
                100,
                Spell::GENDER_NEUTRAL,
                'сто'
            ],
            [
                101,
                Spell::GENDER_NEUTRAL,
                'сто одно'
            ],
        ];
    }
}
