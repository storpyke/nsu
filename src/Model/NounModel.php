<?php

declare(strict_types=1);

namespace Algetar\Nsu\Model;

use Algetar\Nsu\Exception\UnknownIndexException;
use Algetar\Nsu\Spell;

class NounModel extends Model
{
    public function unshift(array $row): self
    {
        array_unshift($this->source, $row);

        return $this;
    }

    /**
     * @throws UnknownIndexException
     */
    public function gender(): int
    {
        $row = $this->row();

        return $row['type'] ?? Spell::GENDER_NEUTRAL;
    }
}
