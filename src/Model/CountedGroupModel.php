<?php

declare(strict_types=1);

namespace Algetar\Nsu\Model;

class CountedGroupModel extends Model
{
    public const GROUP_NONE = 0;
    public const GROUP_MONEY = 1;
    public const GROUP_WEIGHT = 2;

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

    public function spellDecimal(int $groupType): bool
    {
        return $this->spellDecimal[$groupType];
    }
}
