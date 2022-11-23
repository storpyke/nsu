<?php
declare(strict_types=1);

namespace Algetar\Nsu;

use Algetar\Nsu\Components\Decimal;
use Algetar\Nsu\Components\Integer;
use Algetar\Nsu\Components\ParseCounted;
use Algetar\Nsu\Components\ParseNumber;
use Algetar\Nsu\Exception\InvalidNumberFormat;
use Algetar\Nsu\Exception\UnknownIndexException;

class Spell
{
    public const DECLENSION_MANY = 0;
    public const DECLENSION_SINGLE = 1;
    public const DECLENSION_FEW = 2;

    public const GENDER_NEUTRAL = 0;
    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 2;

    public static array $defaultFormat = [
        '%1$s %2$s',
        '%1$s %2$s %4$s %5$s'
    ];

    /* Число */
    private ParseNumber $number;

    /* Исчисляемое */
    private ParseCounted $counted;

    /* Результат */
    private string $spelt;

    /*
     * Компоненты результата
     * 0 - число/целая часть словами
     * 1 - исчислимое/исчислимое целой части
     * 2 - само число/целая часть числа
     * 3 - дробная часть словами
     * 4 - исчислимое дробной части
     * 5 - дробная часть числа
     */
    public array $speltValues;

    public function number(): ParseNumber
    {
        return $this->number;
    }

    public function counted(): ParseCounted
    {
        return  $this->counted;
    }

    public function spelt(): string
    {
        return $this->spelt;
    }

    /**
     * @throws InvalidNumberFormat
     * @throws UnknownIndexException
     */
    public function __invoke($number, string $counted = null, string $format = null): string
    {
        $this->number = ParseNumber::parse($number);
        $this->counted = ParseCounted::parse($counted);

        $numberType = $this->number->type();

        if ($this->counted->spellDecimal() && $this->number->decimalLength() === 0) {
            $numberType = ParseNumber::TYPE_INT;
        }

        if ($numberType === ParseNumber::TYPE_INT) {
            $speller = new Integer($this->number, $this->counted, $format);
        } else {
            $speller = new Decimal($this->number, $this->counted, $format);
        }

        $this->spelt = $speller->spell();
        $this->speltValues = $speller->values();

        return $this->spelt;
    }
}
