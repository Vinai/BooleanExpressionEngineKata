<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression\Parser;

interface TokenStreamPreprocessor
{
    public function process(array $tokens): array;
}
