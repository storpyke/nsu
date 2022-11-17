<?php
declare(strict_types=1);

namespace Algetar\Nsu\Model;

use Algetar\Nsu\Spell;

class ThousandthNameModel extends NounModel
{
    protected array $source = [
        //имена числительные склоняются: пять тысяч, одна тысяча, две тысячи
        0 => ['titles' => ['тысяч', 'тысяча', 'тысячи'], 'type' => Spell::GENDER_FEMALE],
        1 => ['titles' => ['миллионов', 'миллион', 'миллиона'], 'type' => Spell::GENDER_MALE],
        2 => ['titles' => ['миллиардов', 'миллиард', 'миллиарда'], 'type' => Spell::GENDER_MALE],
        3 => ['titles' => ['триллионов', 'триллион', 'триллиона'], 'type' => Spell::GENDER_MALE],
        4 => ['titles' => ['квадрильонов', 'квадрильон', 'квадрильона'], 'type' => Spell::GENDER_MALE],
        5 => ['titles' => ['квинтиллионов', 'квинтиллион', 'квинтиллиона'], 'type' => Spell::GENDER_MALE],
        6 => ['titles' => ['дохренилионов', 'дохренилион', 'дохренилиона'], 'type' => Spell::GENDER_MALE],
    ];
}