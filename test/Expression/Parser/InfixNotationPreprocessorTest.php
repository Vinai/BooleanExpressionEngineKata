<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression\Parser;

use PHPUnit\Framework\TestCase;

class InfixNotationPreprocessorTest extends TestCase
{
    public function testMovesOperatorsBeforePreviousBooleanExpression()
    {
        $input = ['1', '&', '1'];
        $expected = ['&', '1', '1'];
        $this->assertSame($expected, (new InfixNotationPreprocessor())->transformIntoPrefixNotation($input));
    }

    public function testMovesOperatorBeforePreviousGroup()
    {
        $input = ['(', '1', ')', '&', '1'];
        $expected = ['&','(', '1', ')', '1'];
        $this->assertSame($expected, (new InfixNotationPreprocessor())->transformIntoPrefixNotation($input));
    }

    public function testMovesOperatorBeforePreviousNestedGroup()
    {
        $input = ['(', '1', '(', '0', ')', ')', '&', '1'];
        $expected = ['&','(', '1', '(', '0', ')', ')', '1'];
        $this->assertSame($expected, (new InfixNotationPreprocessor())->transformIntoPrefixNotation($input));
    }

    public function testDoesNotChangeTokensWithoutOperator()
    {
        $input = ['!', '(', '1', '(', '0', ')', ')'];
        $expected = $input;
        $this->assertSame($expected, (new InfixNotationPreprocessor())->transformIntoPrefixNotation($input));
    }
}
