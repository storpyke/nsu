<?php
declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Model\TermedGroupNames;
use Algetar\Nsu\Models\TermedNames;

/**
 * Анализирует строку формат исчисляемого,
 * формат: [@][<termed>][.x]
 * @ - группа исчисляемых (вес, деньги и т.д)
 * <termed> - имя исчисляемого или группы
 * x - число, количество букв, до которых будет
 *   сокращено имя исчисляемого (лист.1 будет представлено как л.)
 * Если неуказан $term, будет выбрано пустое значение в среднем роде
 * ['tittles' => ['']]
 */
class Termed
{
    /* Наименования исчисляемых */
    private TermedNames $termedNames;

    /* Наименования групп исчисляемых */
    private TermedGroupNames $termedGroupNames;

    /* Выбранные записи с   */
    private array $terms = [];

    /**
     * Номер группы исчисляемых
     * 0 - нет группы
     */
    private int $group = 0;

    /* Имя исчисляемого или группы  */
    private string $title = '';

    /** Сокращать до $shortTo букв */
    private int $shortTo = 0;

    /**
     * Количество цифр после запятой произносимого числа.
     * 0 - Исчисляется целое число.
     */
    private int $digits = 0;

    public function __construct()
    {
        $this->termedNames = new TermedNames();
        $this->termedGroupNames = new TermedGroupNames();
    }

    /**
     * @throws UnknownIndexException
     */
    public function spell(string $format): array
    {
        $isGroup = false;

        if (substr($format, 0, 1)  === '@') {
            $format = substr($format, 1);
            $isGroup = true;
        }

        if (($pos = strpos($format, '.')) !== false) {
            $this->shortTo = ((strlen($format) - $pos) > 0) ? (int) substr($format, $pos + 1) : 0;
            $format = substr($format, 0, $pos);
        }

        $this->title = $format;

        if (! $isGroup) {
            return $this->add($this->termedNames->find($this->title)->row());
        }

        $this->group = $this->termedGroupNames->find($this->title)->type;

        foreach ($this->termedGroupNames->titles as $title) {
            $this->add($this->termedNames->find($title)->row());
            $this->title = $title;
        }

        return $this->terms;
    }

    private function add(array $row): array
    {
        $this->terms[] = $row;

        return $this->terms();
    }

    public function terms(): array
    {
        return $this->terms;
    }

    public function shortTo(): int
    {
        return $this->shortTo;
    }

    public function group(): int
    {
        return $this->group;
    }

    public function isGroup(): bool
    {
        return $this->group === 0;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function digits(): int
    {
        return $this->digits;
    }

    public function setDigits(int $digits): self
    {
        $this->digits = $digits;

        return $this;
    }
}
