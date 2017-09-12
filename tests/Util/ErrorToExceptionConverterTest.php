<?php
namespace NeedleProject\Common\Util;

use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_Error as Error;

class ErrorToExceptionConverterTest extends TestCase
{
    /**
     * Restore base error handler after each test
     */
    public function tearDown()
    {
        \restore_error_handler();
    }

    /**
     * @expectedException \Exception
     */
    public function testHandler()
    {
        $exceptionConverter = new ErrorToExceptionConverter();
        $exceptionConverter->convertErrorsToExceptions();
        trigger_error("Dummy", E_USER_ERROR);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCustomException()
    {
        $exceptionConverter = new ErrorToExceptionConverter();
        $exceptionConverter->convertErrorsToExceptions(E_ALL, \RuntimeException::class);
        trigger_error("Dummy", E_USER_ERROR);
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     * @expectedExceptionMessage Dummy
     */
    public function testRestore()
    {
        $exceptionConverter = new ErrorToExceptionConverter();
        $exceptionConverter->convertErrorsToExceptions(E_ALL, \RuntimeException::class);
        try {
            trigger_error("Dummy", E_USER_ERROR);
        } catch (\Exception $e) {
            $this->assertEquals(
                sprintf("Dummy in %s on line %s!", __FILE__, __LINE__ - 3),
                $e->getMessage()
            );
        }

        $exceptionConverter->restoreErrorHandler();
        trigger_error("Dummy", E_USER_ERROR);
    }
}
