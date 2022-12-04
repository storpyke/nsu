<?php

declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Spell;

class Integer implements SpellNumberInterface
{
    /* Заданное число */
    protected ParseNumberInterface $number;

    /* Заданное исчисляемое */
    protected ParseCountedInterface $counted;

    /* Формат представления результата */
    protected string $format;

    /* @var string|int Результат*/
    private $spelt;

    /*
     * Результат
     * 0 - число словами
     * 1 - исчислимое
     * 2 - само число
     */
    private array $values;

    public function __construct(ParseNumberInterface $number, ParseCountedInterface $counted, string $format = null)
    {
        if ($format === null) {
            $format = Spell::$defaultFormat[0];
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
        $kipCountedOnZero = false;
        if ($this->counted->groupType() > 0 && $this->counted->spellDecimal()) {
            $kipCountedOnZero = true;
        }

        $value = Numbering::create($this->number->integer(), $this->counted->counted(), $kipCountedOnZero);
        $this->values = [
            $value->spelt(),
            $value->counted(),
            $value->number()
        ];

        $this->spelt = vsprintf($this->format, $this->values);

        return $this->spelt;
    }
}
