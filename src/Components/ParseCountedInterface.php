<?php

declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Model\CountedNounModel;

interface ParseCountedInterface
{
    public function groupType(): int;

    public function counted(): array;

    public function shortTo(): int;

    public function unshiftCounted($id): self;

    public function countedModel(): CountedNounModel;

    public function spellZero(): bool;

    public function spellDecimal(): bool;
}