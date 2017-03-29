<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression;

use BooleanExpressionEngine\Expression;
use PHPUnit\Framework\TestCase;

class NotTest extends TestCase
{
    /**
     * @return Expression|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createDummyExpression(): Expression
    {
        return $this->createMock(Expression::class);
    }
    
    private function createStubExpressionWithValue(bool $value)
    {
        $stubExpression = $this->createDummyExpression();
        $stubExpression->method('evaluate')->willReturn($value);
        return $stubExpression;
    }
    
    public function testIsAnExpression()
    {
        $this->assertInstanceOf(Expression::class, new Not($this->createDummyExpression()));
    }

    public function testReturnsNegatedExpression()
    {
        $this->assertFalse((new Not($this->createStubExpressionWithValue(true)))->evaluate());
        $this->assertTrue((new Not($this->createStubExpressionWithValue(false)))->evaluate());
    }
}
