<?php

declare(strict_types=1);

namespace Algetar\Nsu\Components;

interface SpellNumberInterface
{
    public function spelt(): string;

    public function values(): array;

    public function spell(): string;
}
