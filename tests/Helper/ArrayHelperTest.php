<?php
namespace NeedleProject\Common\Helper;

use NeedleProject\Common\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

class ArrayHelperTest extends TestCase
{
    /**
     * @dataProvider provideTrueScenarios
     * @param $array
     * @param $keys
     */
    public function testHasKey($array, $keys)
    {
        $arrayHelper = new ArrayHelper();
        $this->assertTrue(
            $arrayHelper->hasKeysInDepth($array, $keys)
        );
    }

    /**
     * @dataProvider provideFalseScenarios
     * @param $array
     * @param $keys
     */
    public function testFailHasKey($array, $keys)
    {
        $arrayHelper = new ArrayHelper();
        $this->assertFalse(
            $arrayHelper->hasKeysInDepth($array, $keys)
        );
    }

    /**
     * @dataProvider provideExceptionScenarios
     * @param $notArray
     */
    public function testHasKeyException($notArray)
    {
        $arrayHelper = new ArrayHelper();
        $this->expectException(\InvalidArgumentException::class);
        $arrayHelper->hasKeysInDepth($notArray, []);
    }

    /**
     * @dataProvider provideExceptionScenarios
     * @param $notArray
     */
    public function testGetValueException($notArray)
    {
        $arrayHelper = new ArrayHelper();
        $this->expectException(\InvalidArgumentException::class);
        $arrayHelper->getValueFromDepth($notArray, []);
    }

    /**
     * @dataProvider provideValueScenarios
     * @param array $array
     * @param array $keys
     * @param mixed $expectedValue
     */
    public function testGetValue($array, $keys, $expectedValue)
    {
        $arrayHelper = new ArrayHelper();
        $this->assertEquals(
            $expectedValue,
            $arrayHelper->getValueFromDepth($array, $keys)
        );
    }

    /**
     * @dataProvider provideValueScenarios
     * @param array $array
     * @param array $keys
     */
    public function testFailGetValue($array, $keys)
    {
        $arrayHelper = new ArrayHelper();
        $this->assertNotNull($arrayHelper->getValueFromDepth($array, $keys));
    }

    public function testNotFoundGetValue()
    {
        $arrayHelper = new ArrayHelper();
        $this->expectException(NotFoundException::class);
        $arrayHelper->getValueFromDepth(['a' => 'b'], ['a', 'c']);
    }

    /**
     * Tied to ::testHasKey
     * @return array
     */
    public function provideTrueScenarios()
    {
        return [
            [
                [
                    'foo' => [
                        'bar' => [
                            'baz' => [
                                'qux' => 'Lorem ipsum'
                            ]
                        ]
                    ]
                ],
                ['foo','bar','baz','qux']
            ],
            [
                [[[['a' => ['bar']]]]],
                [0, 0, 0, 'a', 0]
            ]
        ];
    }

    /**
     * Tied to ::testFailHasKey
     * @return array
     */
    public function provideFalseScenarios()
    {
        return [
            [
                [
                    'foo' => [
                        'foo' => [
                            'foo' => [
                                'foo' => [
                                    'bar' => 'Lorem ipsum'
                                ]
                            ]
                        ]
                    ]
                ],
                ['foo', 'foo', 'foo', 'foo', 'foo', 'bar']
            ],
            [
                [
                    'foo' => [
                        'bar' => 'baz'
                    ]
                ],
                ['foo', 'bar', 'baz']
            ]
        ];
    }

    /**
     * Tied to ::testException
     * @return array
     */
    public function provideExceptionScenarios()
    {
        return [
            [1],
            ['a'],
            [new \stdClass()],
            [0xFF],
            [1.2],
            [[]],
            [['a' => 'b']]
        ];
    }

    /**
     * Tied to ::testGetValue
     * @return array
     */
    public function provideValueScenarios()
    {
        return [
            // first scenario
            [
                [
                    'foo' => [
                        'foo' => [
                            'foo' => [
                                'foo' => [
                                    'bar' => 'Lorem ipsum'
                                ]
                            ]
                        ]
                    ]
                ],
                ['foo', 'foo', 'foo', 'foo', 'bar'],
                'Lorem ipsum'
            ]
        ];
    }


    /**
     * Tied to ::testFailGetValue
     * @return array
     */
    public function provideFailValueScenarios()
    {
        return [
            // first scenario
            [
                [
                    'foo' => [
                        'foo' => [
                            'foo' => [
                                'foo' => [
                                    'bar' => 'Lorem ipsum'
                                ]
                            ]
                        ]
                    ]
                ],
                ['foo', 'foo', 'foo', 'foo', 'foo', 'bar']
            ]
        ];
    }
}
