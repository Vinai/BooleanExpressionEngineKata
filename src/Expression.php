<?php

declare(strict_types=1);

namespace BooleanExpressionEngine;

interface Expression
{
    public function evaluate(): bool;
}
