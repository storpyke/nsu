<?php

declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Model\ThousandthNameModel;

class IntegerUnit
{
    /* Произносит тысячный разряд числа */
    private Thousandth $thousandth;

    /* Наименования тысячных разрядов  */
    private ThousandthNameModel $thousandthNames;

    /* Произносимое число */
    private int $number;

    /* Число словами */
    private string $spelt;

    /* Исчисляемого  */
    private string $counted;

    public function __construct()
    {
        $this->thousandth = new Thousandth();
        $this->thousandthNames = new ThousandthNameModel();
    }

    public function number(): int
    {
        return $this->number;
    }

    public function spelt(): string
    {
        return $this->spelt;
    }

    public function counted(): string
    {
        return $this->counted;
    }

    public function setCounted(array $counted): self
    {
        foreach ($counted as $row) {
            $this->thousandthNames->unshift($row);
        }

        return $this;
    }

    /**
     * @throws UnknownIndexException
     */
    public function spell($number, array $counted = null): self
    {
        $this->number = (int) $number;

        if ($counted !== null) {
            $this->setCounted($counted);
        }

        $this->spelt = $this->spellNumber($number);

        return $this;
    }

    /**
     * @throws UnknownIndexException
     */
    private function spellNumber($number, int $i = 0): string
    {
        $stringValue = (string) $number;
        $length = strlen($stringValue);

        $this->thousandthNames->find($i);

        if ($length > 3) {
            $spelt = $this->thousandth->spell(substr($stringValue, -3), $this->thousandthNames->gender());

            if ($i === 0) {
                $this->counted = $this->thousandthNames->name($this->thousandth->declension());
            } else {
                $spelt = trim($spelt . ' ' . $this->thousandthNames->name($this->thousandth->declension()));
            }

            return trim($this->spellNumber(substr($stringValue, 0, -3), $i + 1) . ' ' . $spelt);
        }

        if ($i === 0 && $number == null) {
            $spelt = $this->thousandth->spellZero($this->thousandthNames->gender());
        } else {
            $spelt = $this->thousandth->spell($stringValue, $this->thousandthNames->gender());
        }

        if ($i === 0) {
            $this->counted = $this->thousandthNames->name($this->thousandth->declension());
        } else {
            $spelt = trim($spelt . ' ' . $this->thousandthNames->name($this->thousandth->declension()));
        }

        return $spelt;
    }
}
