<?php
/**
 * This file is part of the NeedleProject\Common package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\Common;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LogLevel;

/**
 * Class ErrorToExceptionConverter
 *
 * @package NeedleProject\Common
 * @author Adrian Tilita <adrian@tilita.ro>
 * @copyright 2017 Adrian Tilita
 * @license https://opensource.org/licenses/MIT MIT Licence
 */
class ClassFinder
{
    use LoggerAwareTrait;

    /**
     * @const string
     */
    const BASE_EXTENSION = '.php';

    /**
     * @var null|string Fully qualified path
     */
    private $searchPath = null;

    /**
     * @var null|string Fully qualified namespace
     */
    private $classType = null;

    /**
     * @var null|string The extension of a file in format ".ext"
     */
    private $extension = null;

    /**
     * ClassSearchService constructor.
     * @param string $searchPath    Fully qualified path
     * @param string $classType     Fully qualified path
     */
    public function __construct($searchPath, $classType)
    {
        $this->searchPath = $searchPath;
        $this->classType = $classType;
        $this->extension = static::BASE_EXTENSION;
    }

    /**
     * Start searching for files
     * @return array
     */
    public function findClasses()
    {
        $files = $this->getFiles($this->searchPath);

        // if class are not loaded they will ne be caught by "declared_classes"
        $this->loadFiles($files);

        return $this->identifyClasses();
    }

    /**
     * Log Messages
     * @param string $message
     */
    private function log($message)
    {
        if (!is_null($this->logger)) {
            $this->logger->log(
                LogLevel::DEBUG,
                sprintf("[%s] - %s", date('Y-m-d H:i:s'), $message)
            );
        }
    }

    /**
     * Scan a directory and list all files that correspond to given criteria
     * @param string $directory
     * @return array
     */
    private function getFiles($directory)
    {
        $fileList = [];
        $files = scandir($directory);
        $this->log(sprintf("Scanning dir %s, found %d files", $directory, count($files)));
        foreach ($files as $index => $file) {
            // recursive retrieve files in subdirectories
            if (true === is_dir($directory . DIRECTORY_SEPARATOR . $file) && !in_array($file, [".", ".."])) {
                $fileList = array_merge(
                    $fileList,
                    $this->getFiles($directory . DIRECTORY_SEPARATOR . $file)
                );
                continue;
            }

            // not the file we want
            if (false === $this->isRequiredFile($file)) {
                $this->log(sprintf("Files %s is not required", $directory . DIRECTORY_SEPARATOR . $file));
                continue;
            }
            $this->log(sprintf("Collected %s", $directory . DIRECTORY_SEPARATOR . $file));
            $fileList[] = $directory . DIRECTORY_SEPARATOR . $file;
        }
        return $fileList;
    }

    /**
     * @param string $file
     * @return bool
     */
    private function isRequiredFile($file)
    {
        return substr($file, strlen($this->extension) * -1) === $this->extension;
    }

    /**
     * Load all files that are not caught by auto-loader
     * @param array $files
     */
    private function loadFiles($files)
    {
        foreach ($files as $file) {
            require_once $file;
        }
    }

    /**
     * @return array
     */
    private function identifyClasses()
    {
        $definedClasses = get_declared_classes();
        $models = [];
        foreach ($definedClasses as $className) {
            if (false === $this->isA($className, $this->classType)) {
                continue;
            }
            $models[] = $className;
        }
        return $models;
    }

    /**
     * Identify if a class-name extends a type or implements an interface
     * @param string $className
     * @param string $classType
     * @return bool
     */
    private function isA($className, $classType)
    {
        $types = array_merge(
            class_parents($className),
            class_implements($className)
        );
        $this->log(sprintf("Class %s is a %s", $className, implode(', ', $types)));
        return in_array($classType, $types);
    }
}
