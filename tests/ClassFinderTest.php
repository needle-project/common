<?php
namespace NeedleProject\Common;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ClassFinderTest extends TestCase
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

    /**
     * Test that the Log will output - fixed to cover
     */
    public function testLogOutput()
    {
        $classFinder = new ClassFinder(
            realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR,
            \Fixture\BaseInterface::class
        );

        $loggerMock = $this->getMockBuilder(
            LoggerInterface::class
        );
        $loggerMock = $loggerMock->getMock();
        $loggerMock->expects($this->atLeastOnce())
            ->method('log')
            ->will($this->returnValue(null));

        $classFinder->setLogger(
            $loggerMock
        );

        $classFinder->findClasses();
    }

    /**
     * @return array
     */
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
