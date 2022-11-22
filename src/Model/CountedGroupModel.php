<?php

declare(strict_types=1);

namespace Algetar\Nsu\Model;

use Algetar\Nsu\Components\SpellMoney;
use Algetar\Nsu\Components\SpellNoGroup;
use Algetar\Nsu\Components\SpellWeight;

class CountedGroupModel extends Model
{
    public const GROUP_NONE = 0;
    public const GROUP_MONEY = 1;
    public const GROUP_WEIGHT = 2;

    protected array $spellClass = [
        self::GROUP_NONE => SpellNoGroup::class,
        self::GROUP_MONEY => SpellMoney::class,
        self::GROUP_WEIGHT => SpellWeight::class
    ];

    /**
     * True десятичная часть произносится как десятых, сотых и т.д.
     * False десятичная часть произносится как целое число (например две копейки)
     */
    protected array $spellDecimal = [
        self::GROUP_NONE => true,
        self::GROUP_MONEY => false,
        self::GROUP_WEIGHT => true
    ];

    protected array $source = [
        'ru' => ['titles' => ['рубль', 'копейка'], 'type' => self::GROUP_MONEY],
        'usd' => ['titles' => ['доллар', 'цент'], 'type' => self::GROUP_MONEY],
        'вес-гр' => ['titles' => ['т', 'кг', 'гр'], 'type' => self::GROUP_WEIGHT],
        'вес-кг' => ['titles' => ['т', 'кг'], 'type' => self::GROUP_WEIGHT],
    ];

    public function spellClass(int $groupType): ?string
    {
        return $this->spellClass[$groupType];
    }

    public function spellDecimal(int $groupType): bool
    {
        return $this->spellDecimal[$groupType];
    }
}
