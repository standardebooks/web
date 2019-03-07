<?php

namespace TheCodingMachine\Safe\PHPStan\Rules;

use PHPStan\Testing\RuleTestCase;
use TheCodingMachine\Safe\PHPStan\Type\Php\ReplaceSafeFunctionsDynamicReturnTypeExtension;

class UseSafeFunctionsRuleTest extends RuleTestCase
{
    protected function getRule(): \PHPStan\Rules\Rule
    {
        return new UseSafeFunctionsRule();
    }

    public function testCatch()
    {
        $this->analyse([__DIR__ . '/data/fopen.php'], [
            [
                "Function fopen is unsafe to use. It can return FALSE instead of throwing an exception. Please add 'use function Safe\\fopen;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library.",
                4,
            ],
        ]);
    }

    public function testNoCatchSafe()
    {
        $this->analyse([__DIR__ . '/data/safe_fopen.php'], []);
    }

    public function testExprCall()
    {
        $this->analyse([__DIR__ . '/data/undirect_call.php'], []);
    }
}
