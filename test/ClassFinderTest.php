<?php
namespace NeedleProject\Common;

class ClassFinderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideScenarios
     * @param string $className
     * @param array $expectedFoundClasses
     */
    public function testClassSearch($className, $expectedFoundClasses)
    {
        $classFinder = new ClassFinder(
            realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR,
            $className
        );

        $foundClasses = $classFinder->findClasses();
        sort($foundClasses);
        sort($expectedFoundClasses);
        $this->assertEquals(
            $expectedFoundClasses,
            $foundClasses
        );
    }

    public function provideScenarios()
    {
        return [
            [
                \Fixture\BaseInterface::class,
                [
                    \Fixture\Path\ClassList\BazClass::class,
                    \Fixture\Path\ClassList\GodClass::class,
                    \Fixture\Path\FooClass::class
                ]
            ],
            [
                \Fixture\BaseClass::class,
                [
                    \Fixture\Path\ExtendsBaseClass::class
                ]
            ],
            [
                \Fixture\AbstractBase::class,
                [
                    \Fixture\Path\FooClass::class,
                    \Fixture\Path\ClassList\BarClass::class,
                    \Fixture\Path\ClassList\BazClass::class,
                    \Fixture\Path\ClassList\GodClass::class
                ]
            ]
        ];
    }
}
