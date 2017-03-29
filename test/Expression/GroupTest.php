<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression;

use BooleanExpressionEngine\Expression;
use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
    /**
     * @return Expression|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createDummyExpression(): Expression
    {
        return $this->createMock(Expression::class);
    }

    public function testIsAnExpression()
    {
        $dummyExpression = $this->createDummyExpression();
        $this->assertInstanceOf(Expression::class, new Group($dummyExpression));
    }

    public function testDelegatesEvaluationToExpression()
    {
        $mockExpression = $this->createDummyExpression();
        $mockExpression->expects($this->once())->method('evaluate')->willReturn(true);
        
        $this->assertTrue((new Group($mockExpression))->evaluate());
    }
}
