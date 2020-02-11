<?php
namespace NeedleProject\Common\Convertor;

use PHPUnit\Framework\TestCase;

class CompoundWordConvertorTest extends TestCase
{
    /**
     * @dataProvider provideCamelCaseScenarios
     * @param $input
     * @param $expectedOutput
     */
    public function testConvertToCamelCase($input, $expectedOutput)
    {
        $this->assertEquals(
            $expectedOutput,
            CompoundWordConvertor::convertToCamelCase($input)
        );
    }

    /**
     * @dataProvider providePascalCaseScenarios
     * @param $input
     * @param $expectedOutput
     */
    public function testConvertToPascalCase($input, $expectedOutput)
    {
        $this->assertEquals(
            $expectedOutput,
            CompoundWordConvertor::convertToPascalCase($input)
        );
    }

    /**
     * @dataProvider provideSnakeCaseScenarios
     * @param $input
     * @param $expectedOutput
     */
    public function testConvertToSnakeCase($input, $expectedOutput)
    {
        $this->assertEquals(
            $expectedOutput,
            CompoundWordConvertor::convertToSnakeCase($input)
        );
    }

    /**
     * @dataProvider provideIsCamelCaseScenarios
     * @param $input
     */
    public function testIsCamelCaseTrue($input)
    {
        $this->assertTrue(CompoundWordConvertor::isCamelCase($input));
    }

    public function provideCamelCaseScenarios()
    {
        return [
            ['foo_bar', 'fooBar'],
            ['foo_a', 'fooA'],
            ['FOOO', 'fooo'],
            ['fOoOoOo BaRrRr', 'fooooooBarrrr'],
            ['Hello World', 'helloWorld']
            // @needs a fix: ['fooBar', 'fooBar']
        ];
    }

    public function providePascalCaseScenarios()
    {
        return [
            ['foo_bar', 'FooBar'],
            ['foo_a', 'FooA'],
            ['FOOO', 'Fooo'],
            ['fOoOoOo BaRrRr', 'FooooooBarrrr'],
            ['Hello World', 'HelloWorld'],
            // @need fix ['FooBar', 'FooBar']
        ];
    }

    public function provideSnakeCaseScenarios()
    {
        return [
            ['FooBar', 'foo_bar'],
            ['fooBarBaz', 'foo_bar_baz'],
            ['foo_bar', 'foo_bar'],
            ['Hello World', 'hello_world'],
            // @need fix ['FOO BAR', 'foo_bar'],
            // @need fix ['SimpleXML', 'simple_xml']
        ];
    }

    public function provideIsCamelCaseScenarios()
    {
        return [
            ['fooBar'],
            ['fooBarBaz']
        ];
    }
}
