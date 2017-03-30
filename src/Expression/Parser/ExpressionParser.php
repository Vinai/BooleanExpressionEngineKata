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
            [$firstOperandTokens, $secondOperandTokens] = $this->getNextTwoExpressionsTokensTuple($tokens);
            return new Operator($token, $this->parse(...$firstOperandTokens), $this->parse(...$secondOperandTokens));
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
            [$firstOperandTokens, $secondOperandTokens] = $this->getNextTwoExpressionsTokensTuple($tokens);
            return array_merge([$token], $firstOperandTokens, $secondOperandTokens);
        }
        return [];
    }
    
    private function getNextTwoExpressionsTokensTuple(array $tokens): array
    {
        $firstOperandTokens = $this->getNextExpressionTokens(...$tokens);
        $secondOperandTokens = $this->getNextExpressionTokens(...array_slice($tokens, count($firstOperandTokens)));
        return [$firstOperandTokens, $secondOperandTokens];
    }

    private function findGroupEnd(array $tokens): int
    {
        for ($size = count($tokens), $i = 0, $stack = 1; $i < $size; $i++) {
            $stack += (int) $this->isGroupStart($tokens[$i]);
            $stack -= (int) $this->isGroupEnd($tokens[$i]);
            if (0 === $stack) return $i;
        }
        throw new UnbalancedGroupException('The expression contains an unbalanced opening parenthesis.');
    }

    private function isBoolean(string $token): bool
    {
        return in_array($token, [Boolean::TRUE, Boolean::FALSE], true);
    }

    private function isGroupStart(string $token): bool
    {
        return Group::START === $token;
    }

    private function isGroupEnd(string $token): bool
    {
        return Group::END === $token;
    }

    private function isNot(string $token): bool
    {
        return Not::NOT === $token;
    }

    private function isOperator(string $token): bool
    {
        return in_array($token, [Operator:: AND, Operator:: OR], true);
    }
}
