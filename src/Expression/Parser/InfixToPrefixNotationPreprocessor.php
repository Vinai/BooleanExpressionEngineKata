<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression\Parser;

use BooleanExpressionEngine\Expression\Group;
use BooleanExpressionEngine\Expression\Operator;

class InfixToPrefixNotationPreprocessor implements TokenStreamPreprocessor
{
    public function process(array $tokens): array
    {
        for ($i = 0, $size = count($tokens); $i < $size; $i++) {
            if ($this->isOperator($tokens[$i])) {
                $tokens = $this->swapOperatorWithPreviousExpression($i, $tokens);
            }
        }
        return $tokens;
    }

    private function isOperator(string $token): bool
    {
        return in_array($token, [Operator::OR, Operator::AND], true);
    }
    
    private function isGroupEnd(int $i, array $tokens): bool
    {
        return Group::END === $tokens[$i];
    }

    private function isGroupStart(int $i, array $tokens): bool
    {
        return Group::START === $tokens[$i];
    }

    private function findExpressionStart(int $i, array $tokens): int
    {
        if ($i === 0) {
            return $i;
        }
        if ($this->isGroupEnd($i - 1, $tokens)) {
            return $this->findGroupStart($i - 1, $tokens);
        }
        return $i - 1;
    }

    private function findGroupStart(int $groupEnd, array $tokens): int
    {
        for ($i = $groupEnd, $stack = 0; $i >= 0; $i--) {
            $stack += (int) $this->isGroupEnd($i, $tokens);
            $stack -= (int) $this->isGroupStart($i, $tokens);
            if ($stack === 0) return $i;
        }
        throw new \RuntimeException('Unable to find start of group from position ' . $groupEnd);
    }

    private function swapOperatorWithPreviousExpression(int $i, array $tokens): array
    {
        $swapWithPosition = $this->findExpressionStart($i, $tokens);
        $expressionToSwapWith = array_slice($tokens, $swapWithPosition, $i - $swapWithPosition);
        $before = array_slice($tokens, 0, $swapWithPosition);
        $operator = [$tokens[$i]];
        $after = array_slice($tokens, $i + 1);

        return array_merge($before, $operator, $expressionToSwapWith, $after);
    }
}
