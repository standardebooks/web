<?php


namespace TheCodingMachine\Safe\PHPStan\Utils;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;

class FunctionListLoader
{
    private static $functions;

    /**
     * @return string[]
     */
    public static function getFunctionList(): array
    {
        if (self::$functions === null) {
            if (\file_exists(__DIR__.'/../../../safe/generated/functionsList.php')) {
                $functions = require __DIR__.'/../../../safe/generated/functionsList.php';
            } elseif (\file_exists(__DIR__.'/../../vendor/thecodingmachine/safe/generated/functionsList.php')) {
                $functions = require __DIR__.'/../../vendor/thecodingmachine/safe/generated/functionsList.php';
            } else {
                throw new \RuntimeException('Could not find thecodingmachine/safe\'s functionsList.php file.');
            }
            // Let's index these functions by their name
            self::$functions = \Safe\array_combine($functions, $functions);
        }
        return self::$functions;
    }
}
