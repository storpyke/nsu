<?php

declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Model\DecimalNameModel;
use Algetar\Nsu\Spell;

class Decimal
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
     */
    public function spell(): string
    {
        $decimalNames = new DecimalNameModel();
        $decimalNames->find($this->number->decimalLength());

        if ($this->counted->spellDecimal()) {
            $this->counted->unshiftCounted('целое');
        }

        $counted = $this->counted->counted();

        $integer = Numbering::create($this->number->integer(), [$counted[0]]);
        $this->values = [
            $integer->spelt(),
            $integer->counted(),
            $this->number->integer()
        ];

        if ($this->counted->spellDecimal()) {
            $decimal = Numbering::create($this->number->decimal(), [$decimalNames->row()], $this->counted->spellZero());

            $this->values[] = trim($decimal->spelt() . ' ' . $decimal->counted());
            $this->values[] = $this->counted->countedModel()->name(Spell::DECLENSION_FEW);
        } else {
            $decimal = Numbering::create($this->number->decimal(), [$counted[1]]);

            $this->values[] = $decimal->spelt();
            $this->values[] = $decimal->counted();
        }

        $this->values[] = $this->number->decimal();

        $this->spelt = vsprintf($this->format, $this->values);

        return $this->spelt;
    }
}