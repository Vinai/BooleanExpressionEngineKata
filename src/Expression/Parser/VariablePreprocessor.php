<?php

declare(strict_types=1);

namespace BooleanExpressionEngine\Expression\Parser;

use BooleanExpressionEngine\Expression\Boolean;

class VariablePreprocessor implements TokenStreamPreprocessor
{
    /**
     * @var array
     */
    private $variableToValueMap;

    public function __construct(array $variableToValueMap)
    {
        $this->validateVariableValuesAreBooleans($variableToValueMap);
        $this->variableToValueMap = $variableToValueMap;
    }

    private function validateVariableValuesAreBooleans(array $variableToValueMap): void
    {
        (function (bool ...$values) {})(...array_values($variableToValueMap));
    }

    public function process(array $tokens): array
    {
        return array_map(function (string $token) {
            return isset($this->variableToValueMap[$token]) ?
                $this->getAsBooleanExpression($this->variableToValueMap[$token]) :
                $token; 
        }, $tokens);
    }

    private function getAsBooleanExpression(bool $variableValue): string 
    {
        return $variableValue ? Boolean::TRUE : Boolean::FALSE;
    }
}
