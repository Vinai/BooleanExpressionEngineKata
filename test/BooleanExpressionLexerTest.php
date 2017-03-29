<?php

declare(strict_types=1);

namespace BooleanExpressionEngine;

use BooleanExpressionEngine\Exception\EmptySourceException;
use PHPUnit\Framework\TestCase;

class BooleanExpressionLexerTest extends TestCase
{
    /**
     * @dataProvider lexerExamplesProvider
     */
    public function testTokenizing(string $source, array $expectedTokens)
    {
        $this->assertSame($expectedTokens, (new BooleanExpressionLexer)->tokenize($source));
    }

    public function lexerExamplesProvider(): array
    {
        return [
            'single operator' => ['1', ['1']],
            'simple group' => ['(1)', ['(', '1', ')']],
            'single op + space' => [' 1', ['1']],
            'expression group with spaces' => ['( 1 & 0 ) | !1 ', ['(', '1', '&', '0', ')', '|', '!', '1']],
        ];
    }

    /**
     * @dataProvider emptySourceProvider
     */
    public function testThrowsExceptionIfInputIsEmpty(string $emptySource)
    {
        $this->expectException(EmptySourceException::class);
        $this->expectExceptionMessage('No boolean expression provided');

        (new BooleanExpressionLexer)->tokenize($emptySource);
    }

    public function emptySourceProvider(): array
    {
        return [ [''], [' '], ["\n"]];
    }
}
