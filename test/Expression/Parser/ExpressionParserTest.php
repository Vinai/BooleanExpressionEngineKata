<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression\Parser;

use BooleanExpressionEngine\Exception\BooleanExpressionErrorException;
use BooleanExpressionEngine\Exception\UnbalancedGroupException;
use BooleanExpressionEngine\Expression\Parser\ExpressionParser;
use PHPUnit\Framework\TestCase;

class ExpressionParserTest extends TestCase
{
    public function testParsesSimpleBoolean()
    {
        $this->assertTrue((new ExpressionParser())->parse('1')->evaluate());
        $this->assertFalse((new ExpressionParser())->parse('0')->evaluate());
    }

    public function testParsesGroupedBoolean()
    {
        $this->assertTrue((new ExpressionParser())->parse('(', '1', ')')->evaluate());
        $this->assertFalse((new ExpressionParser())->parse('(', '0', ')')->evaluate());
    }

    public function testParsesNotBoolean()
    {
        $this->assertFalse((new ExpressionParser())->parse('!', '1')->evaluate());
        $this->assertTrue((new ExpressionParser())->parse('!', '0')->evaluate());
    }

    public function testParsesNotGroup()
    {
        $this->assertFalse((new ExpressionParser())->parse('!', '(', '1', ')')->evaluate());
        $this->assertTrue((new ExpressionParser())->parse('!', '(', '0', ')')->evaluate());
    }

    public function testParsesMultipleNegations()
    {
        $this->assertTrue((new ExpressionParser())->parse('!', '!', '1')->evaluate());
    }

    public function testParsesAndExpression()
    {
        $this->assertTrue((new ExpressionParser())->parse('&', '1', '1')->evaluate());
        $this->assertFalse((new ExpressionParser())->parse('&', '1', '0')->evaluate());
        $this->assertFalse((new ExpressionParser())->parse('&', '0', '0')->evaluate());
    }

    public function testParsesOrExpression()
    {
        $this->assertTrue((new ExpressionParser())->parse('|', '1', '1')->evaluate());
        $this->assertTrue((new ExpressionParser())->parse('|', '1', '0')->evaluate());
        $this->assertFalse((new ExpressionParser())->parse('|', '0', '0')->evaluate());
    }

    public function testParsesOperationsOnGroups()
    {
        $parser = new ExpressionParser();
        $this->assertFalse(($parser)->parse('&', '(', '1', ')', '(', '!', '1', ')')->evaluate());
    }

    public function testThrowsExceptionsForUnbalancedGroupsInOperations()
    {
        $this->expectException(UnbalancedGroupException::class);
        $this->expectExceptionMessage('The expression contains an unbalanced opening parenthesis.');
        (new ExpressionParser())->parse('&', '1', '(', '0')->evaluate();
    }

    public function testThrowsExceptionForUnknownTokens()
    {
        $this->expectException(BooleanExpressionErrorException::class);
        $this->expectExceptionMessage('Syntax error: invalid token: a');
        (new ExpressionParser())->parse('a')->evaluate();
    }
}
