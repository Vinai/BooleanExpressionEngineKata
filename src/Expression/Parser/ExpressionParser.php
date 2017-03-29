<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression\Parser;

use BooleanExpressionEngine\Exception\BooleanExpressionErrorException;
use BooleanExpressionEngine\Exception\UnbalancedGroupException;
use BooleanExpressionEngine\Expression;
use BooleanExpressionEngine\Expression\Boolean;
use BooleanExpressionEngine\Expression\Group;
use BooleanExpressionEngine\Expression\Not;
use BooleanExpressionEngine\Expression\Operator;

class ExpressionParser
{
    public function parse(string $token, string ...$tokens): Expression
    {
        if ($this->isBoolean($token)) {
            return new Boolean($token);
        }
        if ($this->isGroupStart($token)) {
            return new Group($this->parse(...$tokens));
        }
        if ($this->isNot($token)) {
            return new Not($this->parse(...$tokens));
        }
        if ($this->isOperator($token)) {
            $firstExpressionTokens = $this->getNextExpressionTokens(...$tokens);
            $secondExpressionTokens = $this->getNextExpressionTokens(...array_slice($tokens, count($firstExpressionTokens)));
            
            return new Operator($token, $this->parse(...$firstExpressionTokens), $this->parse(...$secondExpressionTokens));
        }
        throw new BooleanExpressionErrorException(sprintf('Syntax error: invalid token: %s', $token));
    }
    
    private function getNextExpressionTokens(string $token, string ...$tokens): array
    {
        if ($this->isBoolean($token)) {
            return [$token];
        }
        if ($this->isGroupStart($token)) {
            return array_merge([$token], array_slice($tokens, 0, $this->findGroupEnd($tokens) + 1));
        }
        if ($this->isNot($token)) {
            return array_merge([$token], $this->getNextExpressionTokens(...$tokens));
        }
        if ($this->isOperator($token)) {
            return [$token];
        }
        return [];
    }

    private function findGroupEnd(array $tokens)
    {
        foreach ($tokens as $i => $token) {
            if ($this->isGroupEnd($token)) return $i;
        }
        throw new UnbalancedGroupException('The expression contains an unbalanced opening parenthesis.');
    }

    private function isBoolean(string $token): bool
    {
        return in_array($token, [Boolean::TRUE, Boolean::FALSE], true);
    }

    private function isGroupStart(string $token): bool
    {
        return '(' === $token;
    }

    private function isGroupEnd(string $token): bool
    {
        return ')' === $token;
    }

    private function isNot(string $token): bool
    {
        return '!' === $token;
    }

    private function isOperator(string $token): bool
    {
        return in_array($token, [Operator:: AND, Operator:: OR], true);
    }
}
