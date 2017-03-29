<?php

declare(strict_types = 1);

namespace BooleanExpressionEngine;

use PHPUnit\Framework\TestCase;

class BooleanExpressionEngineTest extends TestCase
{
    public function testSingleBoolean()
    {
        $this->assertFalse((new BooleanExpressionEngine())->evaluate('0'));
        $this->assertTrue((new BooleanExpressionEngine())->evaluate('1'));
    }

    public function testSingleBooleanGroup()
    {
        $this->assertTrue((new BooleanExpressionEngine())->evaluate('(1)'));
        $this->assertFalse((new BooleanExpressionEngine())->evaluate('(0)'));
    }

    public function testCombinedExpression()
    {
        $this->assertTrue((new BooleanExpressionEngine())->evaluate('(0&1)|1'));
    }
}
