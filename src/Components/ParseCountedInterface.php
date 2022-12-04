<?php

declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Model\CountedNounModel;

interface ParseCountedInterface
{
    public function id(): string;

    public function groupType(): int;

    public function counted(): array;

    public function shortTo(): int;

    public function countedModel($id = null): CountedNounModel;

    public function zeroIsSpelt(): bool;

    public function spellDecimal(): bool;
}