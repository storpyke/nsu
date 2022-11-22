<?php

declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Spell;

class SpellMoney implements SpellClassInterface
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
     */
    public function spell(string $format = null): string
    {
        if ($format === null) {
            $format = Spell::$defaultFormat[1];
        }
        $this->format = $format;

        $counted = $this->counted->counted();

        $integer = Numbering::create($this->number->integer(), [$counted[0]]);
        $this->values = [
            $integer->spelt(),
            $integer->counted(),
            $this->number->integer()
        ];

        $decimal = Numbering::create($this->number->decimal(), [$counted[1]]);

        $this->values[] = $decimal->spelt();
        $this->values[] = $decimal->counted();
        $this->values[] = $this->number->decimal();

        $this->spelt = vsprintf($this->format, $this->values);

        return $this->spelt;
    }
}
