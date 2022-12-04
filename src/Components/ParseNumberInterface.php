<?php

declare(strict_types=1);

namespace Algetar\Nsu\Components;

use Algetar\Nsu\Model\DecimalNameModel;

interface ParseNumberInterface
{
    public function type(): int;

    public function integer(): int;

    public function decimal(): int;

    public function decimalLength(): int;

    public function decimalModel(): DecimalNameModel;
}
