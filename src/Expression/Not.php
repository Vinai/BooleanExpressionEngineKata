<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression;

use BooleanExpressionEngine\Expression;

class Not implements Expression
{
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
        return ! $this->expression->evaluate();
    }
}
