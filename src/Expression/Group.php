<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression;

use BooleanExpressionEngine\Expression;

class Group implements Expression
{
    const START = '(';
    const END = ')';
    
    /**
     * @var Expression
     */
    private $expression;

    public function __construct(Expression $expression)
    {
        $this->expression = $expression;
    }

    public function evaluate(): bool
    {
        return $this->expression->evaluate();
    }
}
