<?php
namespace NeedleProject\Common\Util;

use PHPUnit\Framework\TestCase;

class ErrorToExceptionConverterTest extends TestCase
{
    /**
     * Restore base error handler after each test
     */
    public function tearDown(): void
    {
        \restore_error_handler();
    }

    public function testHandler()
    {
        $exceptionConverter = new ErrorToExceptionConverter();
        $exceptionConverter->convertErrorsToExceptions();

        $this->expectException(\Exception::class);
        trigger_error("Dummy", E_USER_ERROR);
    }

    public function testCustomException()
    {
        $exceptionConverter = new ErrorToExceptionConverter();
        $exceptionConverter->convertErrorsToExceptions(E_ALL, \RuntimeException::class);
        $this->expectException(\RuntimeException::class);
        trigger_error("Dummy", E_USER_ERROR);
    }

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
        $this->expectException(\PHPUnit\Framework\Error\Error::class);
        $this->expectExceptionMessage("Dummy");

        trigger_error("Dummy", E_USER_ERROR);
    }
}
