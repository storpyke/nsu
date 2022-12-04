<?php

declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Exception\InvalidNumberFormat;
use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Model\CountedGroupModel;
use Algetar\Nsu\Spell;

class Decimal implements SpellNumberInterface
{
    /* Заданное число */
    Private ParseNumberInterface $number;

    /* Заданное исчисляемое */
    Private ParseCountedInterface $counted;

    /* Формат представления результата */
    private string $format;

    /* @var string|int Результат*/
    private $spelt;

    /*
     * Результат
     * 1 - число словами
     * 2 - исчислимое
     * 3 - само число
     */
    private array $values;

    public function __construct(ParseNumberInterface $number, ParseCountedInterface $counted, string $format = null)
    {
        if ($format === null) {
            $format = Spell::$defaultFormat[1];
        }

        $this->format = $format;
        $this->number = $number;
        $this->counted = $counted;
    }

    public function spelt(): string
    {
        return $this->spelt;
    }

    public function values(): array
    {
        return $this->values;
    }

    /**
     * @throws UnknownIndexException
     * @throws InvalidNumberFormat
     */
    public function spell(): string
    {
        if ($this->counted->groupType()  === CountedGroupModel::GROUP_MONEY) {
            return $this->spellMoney();
        }

        if (
            $this->counted->groupType() === CountedGroupModel::GROUP_WEIGHT &&
            $this->number->integer() > 999
        ) {
            return $this->spellMultiCounted();
        }

        $integer = Numbering::create($this->number->integer(), [$this->counted->countedModel('целое')->row()]);
        $decimal = Numbering::create($this->number->decimal(), [$this->number->decimalModel()->row()]);

        $this->values = [
            $integer->spelt(),
            $integer->counted(),
            $integer->number(),
            trim($decimal->spelt() . ' ' . $decimal->counted()),
            $this->counted->countedModel()->name(Spell::DECLENSION_FEW),
            $decimal->number(),
        ];

        $this->spelt = vsprintf($this->format, $this->values);

        return $this->spelt;
    }

    /**
     * @throws UnknownIndexException
     */
    private function spellMoney(): string
    {
        $counted = $this->counted->counted();
        $integer = Numbering::create($this->number->integer(), [$counted[0]]);
        $decimal = Numbering::create($this->number->decimal(), [$counted[1]]);

        $this->values = [
            $integer->spelt(),
            $integer->counted(),
            $integer->number(),
            $decimal->spelt(),
            $decimal->counted(),
            $decimal->number()
        ];

        $this->spelt = vsprintf($this->format, $this->values);

        return $this->spelt;
    }

    /**
     * @throws UnknownIndexException
     * @throws InvalidNumberFormat
     */
    private function spellMultiCounted(): string
    {
        $numberValue = substr((string)$this->number->integer(), 0, -3);
        $counted = $this->counted->counted();
        array_pop($counted);

        $integer = Numbering::create($numberValue, $counted, true);

        $numberValue = substr((string)$this->number->integer(), -3) . '.' . $this->number->decimal();
        $number = ParseNumber::parse($numberValue);
        $counted = ParseCounted::parse($this->counted->id());

        $decimal = new Decimal($number, $counted);
        $decimal->spell();

        $integerSpelt = trim($integer->spelt() . ' ' . $integer->counted());
        $this->values = [
            trim($integerSpelt . ' ' . $decimal->values()[0]),
            $decimal->values()[1],
            $this->number->integer(),
            $decimal->values()[3],
            $decimal->values()[4],
            $decimal->values()[5]
        ];

        $this->spelt = vsprintf($this->format, $this->values);

        return $this->spelt;
    }
}