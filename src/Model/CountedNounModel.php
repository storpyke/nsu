<?php

declare(strict_types=1);

namespace Algetar\Nsu\Model;

use Algetar\Nsu\Spell;

class CountedNounModel extends NounModel
{
    protected array $source = [
        // склонения исчисляемых: сто рублей, один рубль, два рубля
        'целое'   => ['titles' => ['целых', 'целое', 'целых'], 'type' => Spell::GENDER_MALE],
        'рубль'   => ['titles' => ['рублей', 'рубль', 'рубля'], 'type' => Spell::GENDER_MALE],
        'копейка' => ['titles' => ['копеек', 'копейка', 'копейки'], 'type' => Spell::GENDER_FEMALE],
        'доллар'  => ['titles' => ['долларов', 'доллар', 'доллара'], 'type' => Spell::GENDER_MALE],
        'цент'    => ['titles' => ['центов', 'цент', 'цента'], 'type' => Spell::GENDER_MALE],
        'гр'      => ['titles' => ['граммов', 'грамм', 'грамма'], 'type' => Spell::GENDER_MALE],
        'кг'      => ['titles' => ['килограммов', 'килограмм', 'килограмма'], 'type' => Spell::GENDER_MALE],
        'т'       => ['titles' => ['тон', 'тонна', 'тонны'], 'type' => Spell::GENDER_FEMALE],
        'день'    => ['titles' => ['дней', 'день', 'дня'], 'type' => Spell::GENDER_MALE],
        'месяц'   => ['titles' => ['месяцев', 'месяц', 'месяца'], 'type' => Spell::GENDER_MALE],
        'год'     => ['titles' => ['лет', 'год', 'года'], 'type' => Spell::GENDER_MALE],
        'лист'    => ['titles' => ['листов', 'лист', 'листа'], 'type' => Spell::GENDER_MALE],
        'копия'   => ['titles' => ['копий', 'копия', 'копии'], 'type' => Spell::GENDER_FEMALE],
    ];
}
