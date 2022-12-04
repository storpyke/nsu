<?php

declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Model\NumberNameModel;
use Algetar\Nsu\Spell;

class Thousandth
{
    /* Хранилище наименований "простых" чисел */
    private NumberNameModel $numbers;

    /* Содержит склонение последней цифры */
    private int $declension;

    private int $gender;

    public function __construct()
    {
        $this->numbers = new NumberNameModel();
    }

    public function declension(): int
    {
        return $this->declension;
    }

    /**
     * @throws UnknownIndexException
     */
    public function spell($number, int $gender = 0): string
    {
        $this->gender = $gender;

        return $this->spellNumber($number);
    }

    /**
     * @throws UnknownIndexException
     */
    public function spellZero(int $gender = 0): string
    {
        $this->gender = $gender;

        $number = $this->numbers->find(0);
        $this->declension = $number->declension();

        return $number->name($this->gender);
    }

    /**
     * @throws UnknownIndexException
     */
    private function spellNumber($number): string
    {
        $intValue = (int) $number;
        $stringValue = (string) $intValue;

        if ($intValue == 0) {
            $this->declension = Spell::DECLENSION_MANY;
            return '';
        }

        if ($intValue > 99) {
            $number = $this->numbers->find((int)(substr($stringValue, 0, 1) . '00'));
            $this->declension = $number->declension();

            return trim(
                $number->name(Spell::GENDER_NEUTRAL) . ' ' .
                $this->spellNumber(substr($stringValue, 1))
            );
        } elseif ($intValue > 20) {
            $number = $this->numbers->find((int)(substr($stringValue, 0, 1) . '0'));
            $this->declension = $number->declension();

            return trim(
                $number->name(Spell::GENDER_NEUTRAL) . ' ' .
                $this->spellNumber(substr($stringValue, 1))
            );
        }

        $number = $this->numbers->find($intValue);
        $this->declension = $number->declension();

        return $number->name($this->gender);
    }
}
