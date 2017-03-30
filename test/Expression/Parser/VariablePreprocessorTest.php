<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression\Parser;

use BooleanExpressionEngine\Expression\Boolean;
use PHPUnit\Framework\TestCase;

class VariablePreprocessorTest extends TestCase
{
    public function testImplementsTokenStreamPreprocessor()
    {
        $this->assertInstanceOf(TokenStreamPreprocessor::class, new VariablePreprocessor([]));
    }
    
    public function testDoesNotChangeNonVariables()
    {
        $tokens = ['1', '0', '!'];
        $this->assertSame($tokens, (new VariablePreprocessor(['foo' => true]))->process($tokens));
    }

    public function testThrowsExceptionIfVariableIsNotBoolean()
    {
        $this->expectException(\TypeError::class);
        new VariablePreprocessor(['foo' => '1']);
    }

    public function testReplacesVariableTokenWithStringRepresentationOfBooleanValue()
    {
        $tokens = ['foo', 'bar'];
        $variables = ['foo' => true, 'bar' => false];
        $expected = [Boolean::TRUE, Boolean::FALSE];
        $this->assertSame($expected, (new VariablePreprocessor($variables))->process($tokens));
    }
}
