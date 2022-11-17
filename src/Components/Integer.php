<?php
declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Exception\UnknownPropertyException;
use Algetar\Nsu\Model\TermedGroupNames;
use Algetar\Nsu\Models\ThousandNames;
use Algetar\Nsu\Spell;

class Integer
{
    /* Тысячный разряд словами */
    private Thousand $thousand;

    /* исчисляемое */
    private ?Termed $termed = null;

    /* 0-целая/1-дробная часть числа */
    private int $part = 0;

    /* Само число */
    private ?int $number = null;

    /* Число словами */
    private string $spelt;

    /* Склонение последнего разряда */
    private int $declension;

    /* Род исчисляемого */
    private int $gender;

    /**
     * Произносит целое число
     *
     * @throws UnknownPropertyException
     * @throws UnknownIndexException
     */
    public static function spell($number, Termed $termed = null): self
    {
        return (new static())
            ->setNumber($number)
            ->setTermed($termed)
            ->parse()
            ;
    }

    public function number(): int
    {
        return $this->number;
    }

    public function setNumber($number): self
    {
        $this->number = (int) $number;

        return $this;
    }

    /**
     * @throws UnknownIndexException
     */
    public function setTermed(Termed $termed = null): self
    {
        if ($termed === null) {
            $termed = new Termed();
            $termed->spell('целое');
        }

        $this->termed = $termed;
        $this->thousand = new Thousand($this->termed->getTerms());

        return $this;
    }

    public function spelt(): string
    {
        return $this->spelt;
    }

    public function declension(): int
    {
        return $this->declension;
    }

    public function gender(): int
    {
        return $this->gender;
    }

    /**
     * @throws UnknownPropertyException
     * @throws UnknownIndexException
     */
    public function parse($number = null): self
    {
        if ($number !== null ) {
            $this->setNumber($number);
        }

        if ($this->number === null) {
            throw new UnknownPropertyException('Не задано число');
        }

        if ($this->termed === null) {
            $this->setTermed();
        }

        $this->spelt = $this->spellNumber((string) $this->number, 0);

        return $this;
    }

    /**
     * Разбивает на тысячные разряды и произносит
     *
     * @param string $number
     * @param int $id
     *
     * @return string
     * @throws UnknownIndexException
     */
    private function spellInt(string $number, int $id): string
    {
        $len = strlen($number);
        $value = (int) $number;

        $thousand1 = ($len > 3) ? substr($number, -3): $number;
        $thousand2 = ($len > 3) ? substr($number, 0,  -3): '';

        $spelt = $this->thousand->spell($thousand1, $id);
        $termed =

        if ($id === 0) {
            if (0 === (int) $this->number) {
                $this->declension = Spell::DECLENSION_MANY;

            }
        }
        //
        $spelt = $value === 0 ? '' : $this->thousand->spell($thousand, $id);
        $this->declension = ($id === 0) ? Spell::DECLENSION_MANY : $this->thousand->declension;
        $termed = $this->thousandNames->name($this->declension);

        if ($id === 0 && $value === 0) {
            return $spelt;
        }

        $spelt = $spelt . ' ' . $this->thousandNames->name($this->thousand->declension);

        if ($len > 3) {
            $speltNext = $this->divideAndSpell(substr($number, 0,  -3), $id + 1);
            $spelt = $speltNext . ($speltNext ? '' : ' ') . $spelt;
        }

        return $spelt;
    }

    /**
     * @throws UnknownIndexException
     */
    private function spellNumber(string $number, int $i): string
    {
        $len = strlen( $number);
        //задаем текущий разряд
        $this->thousandNames->find($i);
        //выделяем очередной разряд
        $thousand = ($len > 3) ? mb_substr($number, $len -= 3): $number;
        //Слово ноль произносится, если:
        //1. это нулевой разряд ($i === 0),
        // для $this->part === 0 если $this->termed->digits() > 0
        // для $this->part === 1 если $this->termed->group === CountedGroupNames::GROUP_MONEY
        //2. это нулевой разряд ($i === 0), текущая часть числа равна нулю ($this->number === 0),
        //  это денежная группа ($this->item->type === TGS::GROUP_MONEY)
        //3. это нулевой разряд ($i > 0), число текущего разряда ((int)$digitNumber === 0),
        //   число не целое ($this->item->status > 0) и это целая часть числа ($this->part === 0)
        if ($i === 0) {
            //заодно сохраняем род исчисляемого
            $this->gender = $this->thousandNames->gender;

            if ($this->number === 0) {
                $silent = (($this->part > 0) ||
                    ($this->termed->getGroupType() === TermedGroupNames::GROUP_MONEY));
            } else {
                $flag = (((int) $thousand === 0) &&
                    ($this->item->status > 0) && ($this->item->type !== TGS::GROUP_MONEY) &&
                    ($this->part === 0));
            }
        } else {
            $flag = false;
        }
        //выделяем очередной разряд из трех цифр, и произносим его
        $speltDigit = $this->_sd->spell($digit, $this->_dn->gender, $flag);
        //число произнесено, выставляем склонение и произносим имя числительного
        $digitName = $this->_dn->setDeclension($this->_sd->declension)->spell($i);
        if ($i === 0){
            //склонение нулевого разряда и есть склонение всего числа
            $this->declension = $this->_sd->declension;
            $spelt = $speltDigit;
        } else {
            $spelt = TGU::joinNotEmpty($speltDigit, $digitName);
        }
        if (strlen($number) > 3){
            //произносим следующий разряд, объединяем с текущим
            $spelt = TGU::joinWords([$this->_spell(mb_substr($number, 0, $len), $i+1), $spelt]);
        }
        if ($i === 0){
            //нулевой разряд в рекурсии произносится последним, и потому
            //$spelt содержит произнесенной число без исчисляемого, а
            //$digitName - имя исчисляемого
            return TGU::joinWords($this->parts = [$spelt, $digitName]);
        } else {
            return $spelt;
        }
    }
}
