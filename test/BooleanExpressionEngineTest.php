<?php

declare(strict_types = 1);

namespace BooleanExpressionEngine;

use PHPUnit\Framework\TestCase;

class BooleanExpressionEngineTest extends TestCase
{
    /**
     * @dataProvider expressionProvider
     */
    public function testEvaluatesBooleanExpression(string $expression, bool $expected)
    {
        $message = sprintf('%s is not %s', $expression, ($expected ? 'true' : 'false'));
        $this->assertSame($expected, (new BooleanExpressionEngine())->evaluate($expression), $message);
    }

    public function expressionProvider(): array
    {
        return [
            ['0', false],
            ['1', true],
            ['(0)', false],
            ['(1)', true],
            ['1&1', true],
            ['1&1&0', false],
            ['1&1&1', true],
            ['0&0', false],
            ['0&1', false],
            ['0|0', false],
            ['0|1', true],
            ['1|1', true],
            ['!1', false],
            ['!0', true],
            ['!(1)', false],
            ['(!1)', false],
            ['!(0)', true],
            ['(!0)', true],
            ['!(!0)', false],
            ['!!(!0)', true],
            ['!!(!!0)', false],
            ['(0&1)|1', true],
            ['(0&1)|0', false],
            ['(0&1)|0|1', true],
            ['!(0&1)|0', true],
            ['(1|0)&1', true],
            ['(1|0)&0', false],
            ['(1|0)&!1', false],
            ['((1))', true],
            ['!((1))', false],
            ['(1&1)&(0|1)', true],
            ['(1&1)&(0|(1&1))', true],
            ['0|((1|1)&1))', true],
            ['(1&1)&(0|((1|1)&1))', true],
            ['(1&(1|0))&(0|(1)|(0|1)&((1)))', true],
        ];
    }
}
