<?php

declare(strict_types=1);

namespace Algetar\Nsu\Model;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Spell;

class NumberNameModel extends Model
{
    protected array $source = [
        0  => ['titles' => ['ноль']],
        // числа склоняются по родам: одно Окно, один Кол, одна Калоша
        1  => ['titles' => ['одно', 'один', 'одна'], 'type' => Spell::DECLENSION_SINGLE],
        2  => ['titles' => ['два', 'два', 'две', ], 'type' => Spell::DECLENSION_FEW],
        3  => ['titles' => ['три'], 'type' => Spell::DECLENSION_FEW],
        4  => ['titles' => ['четыре'], 'type' => Spell::DECLENSION_FEW],
        5  => ['titles' => ['пять']],
        6  => ['titles' => ['шесть']],
        7  => ['titles' => ['семь']],
        8  => ['titles' => ['восемь']],
        9  => ['titles' => ['девять']],
        10 => ['titles' => ['десять']],
        11 => ['titles' => ['одиннадцать']],
        12 => ['titles' => ['двенадцать']],
        13 => ['titles' => ['тринадцать']],
        14 => ['titles' => ['четырнадцать']],
        15 => ['titles' => ['пятнадцать']],
        16 => ['titles' => ['шестнадцать']],
        17 => ['titles' => ['семнадцать']],
        18 => ['titles' => ['восемнадцать']],
        19 => ['titles' => ['девятнадцать']],
        20 => ['titles' => ['двадцать']],
        30 => ['titles' => ['тридцать']],
        40 => ['titles' => ['сорок']],
        50 => ['titles' => ['пятьдесят']],
        60 => ['titles' => ['шестьдесят']],
        70 => ['titles' => ['семьдесят']],
        80 => ['titles' => ['восемьдесят']],
        90 => ['titles' => ['девяносто']],
        100=> ['titles' => ['сто']],
        200=> ['titles' => ['двести']],
        300=> ['titles' => ['триста']],
        400=> ['titles' => ['четыреста']],
        500=> ['titles' => ['пятьсот']],
        600=> ['titles' => ['шестьсот']],
        700=> ['titles' => ['семьсот']],
        800=> ['titles' => ['восемьсот']],
        900=> ['titles' => ['девятьсот']],
    ];

    /**
     * @throws UnknownIndexException
     */
    public function declension(): int
    {
        $row = $this->row();

        return $row['type'] ?? Spell::DECLENSION_MANY;
    }
}
