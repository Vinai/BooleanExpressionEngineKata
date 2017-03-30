<?php

declare(strict_types=1);

namespace BooleanExpressionEngine;

use BooleanExpressionEngine\Expression\Parser\ExpressionParser;
use BooleanExpressionEngine\Expression\Parser\InfixToPrefixNotationPreprocessor;
use BooleanExpressionEngine\Expression\Parser\TokenStreamPreprocessor;
use BooleanExpressionEngine\Expression\Parser\VariablePreprocessor;

/**
 * expression ::= bool | group | operation | not
 * bool       ::= '1' | '0'
 * group      ::= '(' expression ')'
 * operation  ::= operator | operator expression | expression operator expression
 * operator   ::= '&' | '|'
 * not        ::= '!' expression
 */
class BooleanExpressionEngine
{
    public function evaluate(string $expression): bool
    {
        return $this->createAst($expression)->evaluate();
    }

    public function evaluateWithVariables(string $expression, array $variables): bool
    {
        return $this->createAst($expression, $variables)->evaluate();
    }

    private function createAst(string $expression, array $variables = []): Expression
    {
        $lexer = new BooleanExpressionLexer();
        $parser = new ExpressionParser();
        $preprocessors = $this->getPreprocessors($variables);

        return $parser->parse(...$this->applyPreProcessors($lexer->tokenize($expression), ...$preprocessors));
    }

    private function applyPreProcessors(array $tokens, TokenStreamPreprocessor ...$preprocessors): array
    {
        return array_reduce($preprocessors, function (array $tokens, TokenStreamPreprocessor $preprocessor) {
            return $preprocessor->process($tokens);
        }, $tokens);
    }

    private function getPreprocessors(array $variables): array
    {
        return [new InfixToPrefixNotationPreprocessor(), new VariablePreprocessor($variables)];
    }
}
