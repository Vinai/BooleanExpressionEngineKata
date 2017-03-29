<?php

declare(strict_types=1);

namespace BooleanExpressionEngine;

use BooleanExpressionEngine\Exception\EmptySourceException;

class BooleanExpressionLexer
{
    public function tokenize(string $source)
    {
        $trimmedSource = preg_replace('#\s#', '', $source);
        if ('' === $trimmedSource) {
            throw new EmptySourceException('No boolean expression provided');
        }

        return str_split($trimmedSource);
    }
}
