<?php
declare(strict_types=1);

namespace Algetar\Nsu;

use Algetar\Nsu\Components\IntegerUnit;

/**
 *
 */
class Speller
{
    /* Исходное число. */
    private ?string $number;

    /* Целая часть числа */
    private ?int $integerPart = null;

    /* Дробная часть числа. */
    private ?int $fractionalPart = null;

    /**
     * Род исчисляемого целой и дробной части.
     * @var int[]
     */
    private array $gender;

    /**
     * @var IntegerUnit[]
     */
    private array $int = [];

    public function __construct($number = null, int $gender = Spell::GENDER_NEUTRAL)
    {
        $this->spell($number, $gender);
    }

    public  function spell($number = null, int $gender = Spell::GENDER_NEUTRAL)
    {

    }

    public function number(): ?string
    {
        return $this->number;
    }

    public function setNumber($number): self
    {
        if ($number === null) {
            return $this;
        }

        if (is_string($number)) {
            $number = str_replace($number, ',', '.');
        }
        $this->number = (string) $number;

        return $this;
    }

    public function gender(): array
    {
        return $this->gender;
    }

    public function integer(): ?int
    {
        return $this->integerPart;
    }

    public function fractional(): ?int
    {
        return $this->fractionalPart;
    }

    public function getSpeltInteger(): ?string
    {
        return $this->spelt[0] ?? null;
    }

    public function getSpeltFractional(): ?string
    {
        return $this->spelt[1] ?? null;
    }

    public function setGender($gender = Spell::GENDER_NEUTRAL): self
    {
        if (! is_array($gender)) {
            $gender = [$gender];
        }

        $this->gender = $gender;

        return $this;
    }

    public function parse($number = null, $gender = Spell::GENDER_NEUTRAL): self
    {
        $this->setNumber($number);
        $this->setGender($gender);

        $value = (string) $this->number;

        $this->integerPart = (int) $value;
        if (($pos = strpos($value, '.')) !== false) {
            $this->integer = ($pos === 0) ? 0 : (int) substr($value, 0, $pos);
            $this->fractionalPart = (int) substr($value, $pos + 1);
        }

        //$this->int[0] = (new IntegerUnit())->spell($this->integer(), $this->gender[0])

        return $this;
    }
}
