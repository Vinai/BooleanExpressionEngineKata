<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression;

use BooleanExpressionEngine\Exception\InvalidOperatorException;
use BooleanExpressionEngine\Expression;
use PHPUnit\Framework\TestCase;

class OperatorTest extends TestCase
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
        $this->assertInstanceOf(Expression::class, new Operator(Operator::AND));
    }

    public function testThrowsExceptionIfOperatorIsInvalid()
    {
        $this->expectException(InvalidOperatorException::class);
        $this->expectExceptionMessage('Invalid operator input: "%", it has to be & or |');
        
        new Operator('%');
    }

    public function testReturnsOperatorInstance()
    {
        $this->assertInstanceOf(Operator::class, Operator::and());
        $this->assertInstanceOf(Operator::class, Operator::or());
    }

    public function testAndOperatorReturnsTrueIfAllExpressionsAreTrue()
    {
        $trueExpression = $this->createStubExpressionWithValue(true);
        $this->assertTrue(Operator::and()->evaluate());
        $this->assertTrue(Operator::and($trueExpression)->evaluate());
        $this->assertTrue(Operator::and($trueExpression, $trueExpression)->evaluate());
        $this->assertTrue(Operator::and($trueExpression, $trueExpression, $trueExpression)->evaluate());
    }

    public function testAndOperatorReturnsFalseIfOneOrMoreExpressionsAreFalse()
    {
        $trueExpression = $this->createStubExpressionWithValue(true);
        $falseExpression = $this->createStubExpressionWithValue(false);
        $this->assertFalse(Operator::and($falseExpression)->evaluate());
        $this->assertFalse(Operator::and($trueExpression, $falseExpression)->evaluate());
        $this->assertFalse(Operator::and($trueExpression, $falseExpression, $trueExpression)->evaluate());
    }

    public function testOrOperatorReturnsFalseIfAllExpressionsAreFalse()
    {
        $falseExpression = $this->createStubExpressionWithValue(false);
        $this->assertFalse(Operator::or()->evaluate());
        $this->assertFalse(Operator::or($falseExpression)->evaluate());
        $this->assertFalse(Operator::or($falseExpression, $falseExpression)->evaluate());
        $this->assertFalse(Operator::or($falseExpression, $falseExpression, $falseExpression)->evaluate());
    }

    public function testOrOperatorReturnsTrueIfOneOrMoreExpressionsAReTrue()
    {
        $trueExpression = $this->createStubExpressionWithValue(true);
        $falseExpression = $this->createStubExpressionWithValue(false);
        $this->assertTrue(Operator::or($trueExpression)->evaluate());
        $this->assertTrue(Operator::or($falseExpression, $trueExpression)->evaluate());
        $this->assertTrue(Operator::or($falseExpression, $falseExpression, $trueExpression)->evaluate());
    }
}
