<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression;

use BooleanExpressionEngine\Exception\InvalidOperatorException;
use BooleanExpressionEngine\Expression;

class Operator implements Expression
{
    const AND = '&';
    const OR = '|';

    /**
     * @var Expression[]
     */
    private $expressions;

    /**
     * @var bool
     */
    private $initialValues = [
        self::AND => true,
        self::OR  => false,
    ];

    /**
     * @var callable[]
     */
    private $reducers = [];

    /**
     * @var string
     */
    private $code;

    public function __construct(string $source, Expression ...$expressions)
    {
        $this->validate($source);
        $this->expressions = $expressions;
        $this->reducers = [
            self::AND => function (bool $result, Expression $expression): bool {
                return $result && $expression->evaluate();
            },
            self::OR => function (bool $result, Expression $expression): bool {
                return $result || $expression->evaluate();
            }
        ];
        $this->code = $source;
    }

    public function evaluate(): bool
    {
        return (bool) array_reduce($this->expressions, $this->getReducer(), $this->getDefaultValue());
    }

    public static function and(Expression ...$expressions): self
    {
        return new self(self:: AND, ...$expressions);
    }

    public static function or(Expression ...$expressions): self
    {
        return new self(self:: OR, ...$expressions);
    }

    /**
     * @param string $source
     */
    private function validate(string $source): void
    {
        if (!in_array($source, [self:: AND, self:: OR], true)) {
            $strTemplate = 'Invalid operator input: "%s", it has to be %s or %s';
            $message = sprintf($strTemplate, $source, self:: AND, self:: OR);
            throw new InvalidOperatorException($message);
        }
    }

    private function getReducer(): callable
    {
        return $this->reducers[$this->code];
    }

    private function getDefaultValue(): bool
    {
        return $this->initialValues[$this->code];
    }
}
