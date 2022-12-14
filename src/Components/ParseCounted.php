<?php

declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Model\CountedGroupModel;
use Algetar\Nsu\Model\CountedNounModel;
use Algetar\Nsu\Spell;

class ParseCounted implements ParseCountedInterface
{
    private CountedGroupModel $groupNames;
    private CountedNounModel $nounNames;

    /* Идентификатор исчисляемого/группы */
    private string $id;

    /* Сократить до количества букв, 0 без сокращений */
    private int $shortTo = 0;

    /* Тип группы, 0   */
    private int $groupType = CountedGroupModel::GROUP_NONE;

    private array $counted = [];

    private bool $zeroIsSpelt = false;

    /**
     * @throws UnknownIndexException
     */
    public static function parse(string $format = null): self
    {
        return new static($format);
    }

    /**
     * @throws UnknownIndexException
     */
    public function __construct(string $format = null)
    {
        $this->groupNames = new CountedGroupModel();
        $this->nounNames = new CountedNounModel();

        $this->parseCounted($format);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function groupType(): int
    {
        return $this->groupType;
    }

    public function counted(): array
    {
        return $this->counted;
    }

    public function shortTo(): int
    {
        return $this->shortTo;
    }

    public function zeroIsSpelt(): bool
    {
        return $this->zeroIsSpelt;
    }

    public function spellDecimal(): bool
    {
        return $this->groupNames->spellDecimal($this->groupType);
    }

    /**
     * @throws UnknownIndexException
     */
    public function countedModel($id = null): CountedNounModel
    {
        return $this->nounNames->find($id ?? $this->id);
    }

    /**
     * @throws UnknownIndexException
     */
    private function parseCounted(string $format = null): void
    {
        if ($format === null) {
            $this->counted[] = ['titles' => ['', '', ''], 'type' => Spell::GENDER_MALE];

            return;
        }

        $pos = strpos($format, '.');
        if ($pos !== false) {
            $this->shortTo = ((strlen($format) - $pos) > 0) ? (int) substr($format, $pos + 1) : 0;
            $format = substr($format, 0, $pos);
        }

        $this->id = $format;

        if ($this->groupNames->exists($format)) {
            $this->setGroup($format);

            return;
        }

        if ($this->nounNames->exists($format)) {
            $this->addCounted($format);

            return;
        }

        throw new UnknownIndexException('Unknown counted or group: ' . $format);
    }

    /**
     * @throws UnknownIndexException
     */
    private function setGroup(string $id)
    {
        $this->groupNames->find($id);

        $this->groupType = $this->groupNames->type;

        if (count($this->groupNames->titles) > 0 && $this->spellDecimal()) {
            $this->zeroIsSpelt = true;
        }

        $lastId = '';
        foreach ($this->groupNames->titles as $countedId) {
            $this->addCounted($countedId);
            $lastId = $countedId;
        }

        $this->id = $lastId;
    }

    /**
     * @throws UnknownIndexException
     */
    private function addCounted(string $id)
    {
        $this->nounNames->find($id);

        $this->counted[] = $this->nounNames->row();
    }
}
