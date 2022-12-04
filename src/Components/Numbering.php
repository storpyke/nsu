<?php

declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Model\ThousandthNameModel;
use Algetar\Nsu\Spell;

class Numbering
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

    /* количество цифр */
    private int $length;

    /* Удалить исчисляемые для нулевых разрядов */
    private bool $skipCountedOnZero;

    /**
     * @throws UnknownIndexException
     */
    public static function create($number, array $counted = null, bool $skipCountedOnZero = false): Numbering
    {
        return (new static($skipCountedOnZero))->spell($number, $counted);
    }

    public function __construct(bool $skipCountedOnZero = false)
    {
        $this->skipCountedOnZero = $skipCountedOnZero;

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

    public function numberLength(): int
    {
        return $this->length;
    }

    /**
     * @throws UnknownIndexException
     */
    public function spell($number, array $counted = null): self
    {
        $this->number = (int) $number;
        $this->length = strlen((string) $number);

        if ($counted !== null) {
            $counted = $this->skipZeroDigits($counted);
            $this->setCounted($counted);
        }

        if ($this->number === 0) {
            $this->spelt = $this->spellZero();
        } else {
            $this->spelt = $this->spellNumber($this->number);
        }

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
            $spelt = $this->spellThousand(substr($stringValue, -3), $i);

            return trim($this->spellNumber(substr($stringValue, 0, -3), $i + 1) . ' ' . $spelt);
        }

        return $this->spellThousand($stringValue, $i);
    }

    /**
     * @throws UnknownIndexException
     */
    private function spellThousand(string $number, int $i): string
    {
        $thousand = (int) $number;
        if ($thousand == 0) {
            if ($i === 0) {
                $this->counted = $this->thousandthNames->name(Spell::DECLENSION_MANY);
            }

            return '';
        }

        $spelt = $this->thousandth->spell($thousand, $this->thousandthNames->gender());
        $counted = $this->thousandthNames->name($this->thousandth->declension());

        if ($i === 0) {
            $this->counted = $counted;
        } else {
            $spelt = trim($spelt . ' ' . $counted);
        }

        return $spelt;
    }

    /**
     * @throws UnknownIndexException
     */
    private function spellZero(): string
    {
        $this->thousandthNames->find(0);
        $spelt = $this->thousandth->spellZero($this->thousandthNames->gender());
        $this->counted = $this->thousandthNames->name($this->thousandth->declension());

        return $spelt;
    }

    private function skipZeroDigits(array $counted): array
    {
        if (! $this->skipCountedOnZero) {
            return $counted;
        }

        return $this->skipZeroDigit($counted);
    }

    private function skipZeroDigit(array $counted): array
    {
        if (count($counted) < 2) {
            return $counted;
        }

        $strValue = (string) $this->number;

        if (strlen($strValue) > 3) {
            $thousand = substr($strValue, -3);
            if ($thousand == 0) {
                array_pop($counted);
                $this->number = (int)substr($strValue, 0,-3);

                return $this->skipZeroDigit($counted);
            }
        }

        return $counted;
    }
}
