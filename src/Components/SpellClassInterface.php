<?php

declare(strict_types=1);

namespace Algetar\Nsu\Components;

interface SpellClassInterface
{
    public function spelt(): string;

    public function values(): array;

    public function spell(string $format): string;
}