<?php

declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Exception\InvalidNumberFormat;
use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Model\DecimalNameModel;

class ParseNumber implements ParseNumberInterface
{
    public const TYPE_UNKNOWN = 0;
    public const TYPE_INT = 1;
    public const TYPE_FLOAT = 2;

    /* Тип числа  */
    private int $type = self::TYPE_UNKNOWN;

    /* Целая часть числа */
    private int $integer = 0;

    /* Дробная часть числа  */
    private int $decimal = 0;

    /* Количество цифр дробной части числа */
    private int $decimalLength = 0;

    /**
     * @throws InvalidNumberFormat
     */
    public function __construct($number = null)
    {
        $this->parseNumber($number);
    }

    /**
     * @throws InvalidNumberFormat
     */
    public static function parse($number = null): self
    {
        return new static($number);
    }

    public function type(): int
    {
        return $this->type;
    }

    public function integer(): int
    {
        return $this->integer;
    }

    public function decimal(): int
    {
        return $this->decimal;
    }

    public function decimalLength(): int
    {
        return $this->decimalLength;
    }

    /**
     * @throws UnknownIndexException
     */
    public function decimalModel(): DecimalNameModel
    {
        return DecimalNameModel::get($this->decimalLength);
    }

    /**
     * @param string|int|float $number
     *
     * @return void
     * @throws InvalidNumberFormat
     */
    private function parseNumber($number): void
    {
        if ($number === null) {
            return;
        }

        if (is_string($number)) {
            if (strpos($number, '.') === false) {
                $this->parseNumber((int)$number);
            } else {
                $this->parseNumber((float)$number);
            }

            return;
        }

        if (is_int($number)) {
            $this->type = self::TYPE_INT;
            $this->integer = $number;

            return;
        }

        if (is_float($number)) {
            $this->type = self::TYPE_FLOAT;
            $stringValue = (string) $number;
            $pos = strpos($stringValue, '.');
            if ($pos === false) {
                $this->integer = (int) $number;

            } else {
                $this->integer = (int) substr($stringValue, 0, $pos);
                $this->decimal = (int) substr($stringValue, $pos + 1);
                $this->decimalLength = strlen((string)$this->decimal);
            }

            return;
        }

        throw new InvalidNumberFormat('The number type must be one of: int, float, string');
    }
}
