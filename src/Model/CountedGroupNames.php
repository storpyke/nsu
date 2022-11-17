<?php

declare(strict_types=1);

namespace Algetar\Nsu\Model;

class CountedGroupNames extends Model
{
    public const GROUP_MONEY = 1;
    public const GROUP_WEIGHT = 2;

    protected array $source = [
        'руб' => ['titles' => ['рубль', 'копейка'], 'type' => self::GROUP_MONEY],
        'доллар' => ['titles' => ['доллар', 'цент'], 'type' => self::GROUP_MONEY],
        'гр' => ['titles' => ['т', 'кг', 'гр'], 'type' => self::GROUP_WEIGHT],
        'кг' => ['titles' => ['т', 'кг'], 'type' => self::GROUP_WEIGHT],
    ];
}
