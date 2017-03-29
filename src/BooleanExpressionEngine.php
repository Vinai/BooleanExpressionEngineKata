<?php

declare(strict_types=1);

namespace BooleanExpressionEngine;

use BooleanExpressionEngine\Expression\Parser\ExpressionParser;
use BooleanExpressionEngine\Expression\Parser\InfixNotationPreprocessor;

/**
 * expression ::= bool | group | operation | not
 * bool       ::= '1' | '0'
 * group      ::= '(' expression ')'
 * operation  ::= operator | operator expression | expression operator expression
 * operator   ::= '&' | '|'
 * not        ::= '!' expression
 * 
 * transitions:
 * 
 * identity -> bool | group | operator | not
 * 
 * bool       -> operator
 * not        -> bool | not | operator | group
 * group      -> bool | not | operator 
 * operator   -> bool | not | group
 */
class BooleanExpressionEngine
{
    public function evaluate(string $expression)
    {
        $lexer = new BooleanExpressionLexer();
        $preprocessor = new InfixNotationPreprocessor();
        $parser = new ExpressionParser();
        
        $tokens = $preprocessor->transformIntoPrefixNotation($lexer->tokenize($expression));
        $ast = $parser->parse(...$tokens);
        return $ast->evaluate();
    }
}
