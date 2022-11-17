<?php
declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Models\NumberNames;
use Algetar\Nsu\Models\ThousandNames;
use Algetar\Nsu\Spell;

class Thousand
{
    /* Хранилище наименований "простых" чисел */
    private NumberNames $numbers;

    /* Хранилище наименований тысячных разрядов */
    private ThousandNames $thousands;

    /* Содержит склонение последней цифры */
    private int $declension;

    /* Род исчисляемого/тысячного разряда */
    private ?int $gender = null;

    /* Исчисляемое */
    private string $termed;

    public function __construct(array $termed = [])
    {
        $this->thousands = new ThousandNames();
        $this->numbers = new NumberNames();

        $this->thousands->merge($termed);
    }

    public function declension(): int
    {
        return $this->declension;
    }

    /**
     * Произносит тысячный разряд
     *
     * @param int|string $number Целое число, не более 3 цифр
     * @param int $i Номер тысячного разряда
     * @return string
     * @throws UnknownIndexException
     */
    public function spell($number, int $i): string
    {
        $intValue = (int) $number;
        $stringValue = (string) $intValue;

        if ($this->gender === null) {
            $this->gender = $this->thousands->find($i)->gender;
        }

        if ($intValue == 0) {
            $numberRow = $this->numbers->find(0);
            $this->declension = $numberRow->declension;

            return $i ? $numberRow->name($this->gender) . ' ' . $this->thousands->name($this->declension) : '';
        }

        if ($intValue > 100) {
            $numberRow = $this->numbers->find((int) (substr($stringValue, 0, 1) . '00'));
            $this->declension = $numberRow->declension;

            return trim($numberRow->name(Spell::GENDER_NEUTRAL) . ' ' . $this->spell(substr($stringValue, 1), $i));
        } elseif ($intValue > 20) {
            $numberRow = $this->numbers->find((int) (substr($stringValue, 0, 1) . '0'));
            $this->declension = $numberRow->declension;

            return trim($numberRow->name(Spell::GENDER_NEUTRAL) . ' ' . $this->spell(substr($stringValue, 1), $i));
        }

        $numberRow = $this->numbers->find($intValue);
        $this->declension = $numberRow->declension;

        if (0 === $i) {
            $this->termed = $this->thousands->name($this->declension);
        }

        return $numberRow->name($this->gender) . ' ' . $this->thousands->name($this->declension);
    }
}
