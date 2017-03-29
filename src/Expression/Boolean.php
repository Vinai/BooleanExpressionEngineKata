<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression;

use BooleanExpressionEngine\Exception\InvalidBooleanException;
use BooleanExpressionEngine\Expression;

class Boolean implements Expression
{
    const TRUE = '1';
    const FALSE = '0';
    
    /**
     * @var string
     */
    private $booleanString;

    public function __construct(string $source)
    {
        $this->validate($source);
        $this->booleanString = $source;
    }
    
    public function evaluate(): bool
    {
        return self::TRUE === $this->booleanString;
    }

    private function validate(string $booleanSource): void
    {
        if (!in_array($booleanSource, [self::TRUE, self::FALSE], true)) {
            $strTemplate = 'Invalid boolean input: "%s", it has to be %s or %s';
            throw new InvalidBooleanException(sprintf($strTemplate, $booleanSource, self::TRUE, self::FALSE));
        }
    }
}
