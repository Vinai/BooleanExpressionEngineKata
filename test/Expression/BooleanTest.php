<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression;

use BooleanExpressionEngine\Exception\InvalidBooleanException;
use BooleanExpressionEngine\Expression;
use PHPUnit\Framework\TestCase;

class BooleanTest extends TestCase
{
    public function testIsAnExpression()
    {
        $this->assertInstanceOf(Expression::class, new Boolean('1'));
    }

    /**
     * @dataProvider invalidBooleanDataProvider
     */
    public function testThrowsExceptionIfBooleanStringIsInvalid()
    {
        $source = '2';
        $this->expectException(InvalidBooleanException::class);
        $this->expectExceptionMessage(sprintf('Invalid boolean input: "%s", it has to be 1 or 0', $source));
        
        new Boolean($source);
    }

    public function invalidBooleanDataProvider(): array
    {
        return array_map(function ($b) { return [$b]; }, ['2', '-1', 'a', '11', '00', '01', ' 1', '1 ']);
    }
}
