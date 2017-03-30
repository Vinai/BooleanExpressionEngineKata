<?php

declare(strict_types=1);

namespace BooleanExpressionEngine;

use BooleanExpressionEngine\Exception\EmptySourceException;
use BooleanExpressionEngine\Expression\Boolean;
use BooleanExpressionEngine\Expression\Group;
use BooleanExpressionEngine\Expression\Not;
use BooleanExpressionEngine\Expression\Operator;

class BooleanExpressionLexer
{
    private $expressionTokens = [
        Boolean::FALSE,
        Boolean::TRUE,
        Operator::AND,
        Operator::OR,
        Group::START,
        Group::END,
        Not::NOT,
    ];
    
    public function tokenize(string $source): array
    {
        $tokens = $this->gatherTokens(trim($source));
        if (empty($tokens)) {
            throw new EmptySourceException('No boolean expression provided');
        }
        return $tokens;
    }

    private function gatherTokens(string $source): array
    {
        if ('' === $source) return [];
        [$token, $remainingSource] = $this->splitNextTokenFromRemainingSource($source);
        return array_merge([$token], $this->gatherTokens(ltrim($remainingSource)));
    }
    
    private function splitNextTokenFromRemainingSource(string $source): array
    {
        $token = $this->getNextToken($source);
        return [$token, substr($source, strlen($token))];
    }

    private function getNextToken(string $source): string
    {
        if (in_array($source[0], $this->expressionTokens, true)) {
            return $source[0];
        }

        return preg_match($this->variableRegEx(), $source, $matches) ? $matches['token'] : '';
    }

    private function variableRegEx(): string
    {
        return '#^(?<token>[^' . implode('', $this->expressionTokens) . ' ]+)#';
    }
}
