<?php
/**
 * This file is part of the NeedleProject\Common package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\Common\Util;

/**
 * Class ErrorToExceptionConverter
 *
 * @package NeedleProject\Common\Util
 * @author Adrian Tilita <adrian@tilita.ro>
 * @copyright 2017 Adrian Tilita
 * @license https://opensource.org/licenses/MIT MIT Licence
 */
class ErrorToExceptionConverter
{
    /**
     * @const string  Exception class name to be thrown
     */
    const EXCEPTION_THROW_CLASS = \Exception::class;

    /**
     * States that the current object is the current error handler
     * @var bool
     */
    protected $isHandledLocal = false;

    /**
     * Converts errors to exceptions
     *
     * @param int|null $level The error level to be handled.
     *                        See http://php.net/manual/en/errorfunc.constants.php
     * @param string|null $exceptionClass
     */
    public function convertErrorsToExceptions($level = null, $exceptionClass = null)
    {
        $this->isHandledLocal = true;
        if (is_null($level)) {
            $level = E_ALL;
        }
        if (is_null($exceptionClass)) {
            $exceptionClass = static::EXCEPTION_THROW_CLASS;
        }
        set_error_handler(function ($errorNumber, $errorMessage, $errorFile, $errorLine) use ($exceptionClass) {
            throw new $exceptionClass(
                sprintf("%s in %s on line %s!", $errorMessage, $errorFile, $errorLine),
                $errorNumber
            );
        }, $level);
    }

    /**
     * Restore previous defined error handlers
     */
    public function restoreErrorHandler()
    {
        if (true === $this->isHandledLocal) {
            \restore_error_handler();
            $this->isHandledLocal = false;
        }
    }
}
