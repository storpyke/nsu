<?php

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Exception\InvalidNumberFormat;
use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Model\DecimalNameModel;
use Algetar\Nsu\Spell;

class SpellWeight implements SpellClassInterface
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
     * 0 - число словами
     * 1 - исчислимое
     * 2 - само число
     */
    private array $values;

    public function __construct(ParseNumberInterface $number, ParseCountedInterface $counted)
    {
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
    public function spell(string $format = null): string
    {
        if ($this->number->type() === ParseNumber::TYPE_UNKNOWN) {
            throw new InvalidNumberFormat('Forbidden number type.');
        }

        if (
            $this->number->type() === ParseNumber::TYPE_INT ||
            $this->number->decimalLength() === 0
        ) {
            $this->spellInteger($format);
        }

        if ($this->number->type() === ParseNumber::TYPE_FLOAT) {
            $this->spellDecimal($format);
        }

        return $this->spelt;
    }

    /**
     * @throws UnknownIndexException
     */
    private function spellInteger(?string $format): void
    {
        if ($format === null) {
            $format = Spell::$defaultFormat[0];
        }
        $this->format = $format;

        $value = Numbering::create($this->number->integer(), $this->counted->counted());
        $this->values = [
            $value->spelt(),
            $value->counted(),
            $this->number->integer()
        ];

        $this->spelt = vsprintf($this->format, $this->values);
    }

    /**
     * @throws UnknownIndexException
     */
    private function spellDecimal(?string $format): void
    {
        if ($format === null) {
            $format = Spell::$defaultFormat[1];
        }
        $this->format = $format;

        $spellZero = count($this->counted->counted()) > 1;

        $this->counted->unshiftCounted('целое');
        $counted = $this->counted->counted();

        $integer = Numbering::create($this->number->integer(), [$counted[0]]);
        $this->values = [
            $integer->spelt(),
            $integer->counted(),
            $this->number->integer()
        ];

        $decimalNames = new DecimalNameModel();
        $decimalNames->find($this->number->decimalLength());
        $decimal = Numbering::create($this->number->decimal(), [$decimalNames->row()], $spellZero);

        $this->values[] = trim($decimal->spelt() . ' ' . $decimal->counted());
        $this->values[] = $this->counted->countedModel()->name(Spell::DECLENSION_FEW);
        $this->values[] = $this->number->decimal();

        $this->spelt = vsprintf($this->format, $this->values);
    }
}
