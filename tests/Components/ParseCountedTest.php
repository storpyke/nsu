<?php

declare(strict_types=1);

namespace Algetar\Nsu\Tests\Components;

use Algetar\Nsu\Components\ParseCounted;
use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Model\CountedGroupModel;
use Algetar\Nsu\Spell;
use PHPUnit\Framework\TestCase;

class ParseCountedTest extends TestCase
{
    /**
     * @dataProvider provideData
     *
     * @throws UnknownIndexException
     */
    public function testParseCounted(?string $format, int $groupType, array $counted, int $shortTo)
    {
        $result = new ParseCounted($format);

        self::assertEquals($groupType, $result->groupType());
        self::assertEquals($counted, $result->counted());
        self::assertEquals($shortTo, $result->shortTo());
    }

    public function provideData(): array
    {
        return [
            [
                null,
                CountedGroupModel::GROUP_NONE,
                [
                    ['titles' => ['', '', ''], 'type' => Spell::GENDER_MALE]
                ],
                0
            ],
            [
                'usd',
                CountedGroupModel::GROUP_MONEY,
                [
                    ['titles' => ['долларов', 'доллар', 'доллара'], 'type' => Spell::GENDER_MALE],
                    ['titles' => ['центов', 'цент', 'цента'], 'type' => Spell::GENDER_MALE],
                ],
                0
            ],
            [
                'день',
                CountedGroupModel::GROUP_NONE,
                [
                    ['titles' => ['дней', 'день', 'дня'], 'type' => Spell::GENDER_MALE],
                ],
                0
            ],
            [
                'рубль.3',
                CountedGroupModel::GROUP_NONE,
                [
                    ['titles' => ['рублей', 'рубль', 'рубля'], 'type' => Spell::GENDER_MALE],
                ],
                3
            ],
        ];
    }
}
