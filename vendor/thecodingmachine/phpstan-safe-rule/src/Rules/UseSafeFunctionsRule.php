<?php


namespace TheCodingMachine\Safe\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Rules\Rule;
use PHPStan\ShouldNotHappenException;
use TheCodingMachine\Safe\PHPStan\Utils\FunctionListLoader;

/**
 * This rule checks that no superglobals are used in code.
 */
class UseSafeFunctionsRule implements Rule
{
    public function getNodeType(): string
    {
        return Node\Expr\FuncCall::class;
    }

    /**
     * @param Node\Expr\FuncCall $node
     * @param \PHPStan\Analyser\Scope $scope
     * @return string[]
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Node\Name) {
            return [];
        }
        $functionName = $node->name->toString();
        $unsafeFunctions = FunctionListLoader::getFunctionList();

        if (isset($unsafeFunctions[$functionName])) {
            return ["Function $functionName is unsafe to use. It can return FALSE instead of throwing an exception. Please add 'use function Safe\\$functionName;' at the beginning of the file to use the variant provided by the 'thecodingmachine/safe' library."];
        }

        return [];
    }
}
