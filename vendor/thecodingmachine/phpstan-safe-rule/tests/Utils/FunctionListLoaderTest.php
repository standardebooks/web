<?php

namespace TheCodingMachine\Safe\PHPStan\Utils;

use PHPUnit\Framework\TestCase;

class FunctionListLoaderTest extends TestCase
{

    public function testGetFunctionList()
    {
        $functions = FunctionListLoader::getFunctionList();
        $this->assertArrayHasKey('fopen', $functions);
    }
}
