<?php
declare(strict_types=1);

namespace Algetar\Nsu\Model;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Spell;

class DecimalNameModel extends NounModel
{
    /**
     * @throws UnknownIndexException
     */
    public static function get($id): self
    {
        return (new static())->find($id);
    }

    protected array $source = [
        //доли склоняются: пять десятых, одна десятая, две десятых
        0 => ['titles' => ['целых', 'целое', 'целых'], 'type' => 0], //произнесение целой части дробного числа
        1 => ['titles' => ['десятых', 'десятая', 'десятых'], 'type' => Spell::GENDER_FEMALE],
        2 => ['titles' => ['сотых', 'сотая', 'сотых'], 'type' => Spell::GENDER_FEMALE],
        3 => ['titles' => ['тысячных', 'тысячная', 'тысячных'], 'type' => Spell::GENDER_FEMALE],
        4 => ['titles' => ['десятитысячных', 'десятитысячная', 'десятитысячных'], 'type' => Spell::GENDER_FEMALE],
        5 => ['titles' => ['стотысячных', 'стотысячная', 'стотысячных'], 'type' => Spell::GENDER_FEMALE],
        6 => ['titles' => ['миллионных', 'миллионная', 'миллионных'], 'type' => Spell::GENDER_FEMALE],
        7 => ['titles' => ['десятимиллионных', 'десятимиллионная', 'десятимиллионных'], 'type' => Spell::GENDER_FEMALE],
        8 => ['titles' => ['стомиллионных', 'стомиллионная', 'стомиллионных'], 'type' => Spell::GENDER_FEMALE],
        9 => ['titles' => ['миллиардных', 'миллиардная', 'миллиардных'], 'type' => Spell::GENDER_FEMALE],
        10 => ['titles' => ['десятимиллиардных', 'десятимиллиардная', 'десятимиллиардных'], 'type' => Spell::GENDER_FEMALE],
        11 => ['titles' => ['сто миллиардных', 'сто миллиардная', 'сто миллиардных'], 'type' => Spell::GENDER_FEMALE],
        12 => ['titles' => ['триллионных', 'триллионная', 'триллионных'], 'type' => Spell::GENDER_FEMALE],
        13 => ['titles' => ['десятитриллионных', 'десятитриллионная', 'десятитриллионных'], 'type' => Spell::GENDER_FEMALE],
        14 => ['titles' => ['стотриллионных', 'стотриллионная', 'стотриллионных'], 'type' => Spell::GENDER_FEMALE],
        15 => ['titles' => ['квадрильонных', 'квадрильонная', 'квадрильонных'], 'type' => Spell::GENDER_FEMALE],
        16 => ['titles' => ['десятиквадрильонных', 'десятиквадрильонная', 'десятиквадрильонных'], 'type' => Spell::GENDER_FEMALE],
        17 => ['titles' => ['стоквадрильонных', 'стоквадрильонная', 'стоквадрильонных'], 'type' => Spell::GENDER_FEMALE],
        18 => ['titles' => ['дохренилионных', 'дохренилионная', 'дохренилионных'], 'type' => Spell::GENDER_FEMALE],
    ];
}