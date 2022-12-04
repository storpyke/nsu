<?php

namespace Algetar\Nsu\Tests\Components;

use Algetar\Nsu\Components\Numbering;
use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Exception\UnknownPropertyException;
use Algetar\Nsu\Spell;
use PHPUnit\Framework\TestCase;

class NumberingTest extends TestCase
{
    /**
     * @throws UnknownIndexException
     */
    public function testItCanSpellZero()
    {
        $int = new Numbering(true);
        $counted = [
            ['titles' => ['целых', 'целое', 'целых'], 'type' => Spell::GENDER_MALE]
        ];
        $result = $int->spell(0, $counted);

        self::assertEquals('ноль', $result->spelt());
    }

    /**
     * @throws UnknownIndexException
     */
    public function testItCanSpellWithCounted()
    {
        $int = new Numbering();

        $counted = [
            ['titles' => ['копеек', 'копейка', 'копейки'], 'type' => Spell::GENDER_FEMALE]
        ];

        $result = $int->spell(2, $counted);

        self::assertEquals('две', $result->spelt());
        self::assertEquals('копейки', $result->counted());
    }

    /**
     * @throws UnknownIndexException
     */
    public function testItCanSpellThousand()
    {
        $int = new Numbering();

        $counted = [
            ['titles' => ['дней', 'день', 'дня'], 'type' => Spell::GENDER_MALE]
        ];

        $result = $int->spell(1000, $counted);

        self::assertEquals('одна тысяча', $result->spelt());
        self::assertEquals('дней', $result->counted());
    }
}
