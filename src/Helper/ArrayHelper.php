<?php
/**
 * This file is part of the NeedleProject\Common package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\Common\Helper;

use NeedleProject\Common\Exception\NotFoundException;

/**
 * Class ErrorToExceptionConverter
 *
 * @package NeedleProject\Common\Helper
 * @author Adrian Tilita <adrian@tilita.ro>
 * @copyright 2017 Adrian Tilita
 * @license https://opensource.org/licenses/MIT MIT Licence
 */
class ArrayHelper
{
    const USAGE_EXISTS = 'hasKey';
    const USAGE_EXTRACT = 'getValue';

    /**
     * Verify if a key exist in depth
     *
     * @param array $haystack  - The array in which to search
     * @param array $keys - an array with the linear items treated as depth.
     *                      Ex: array('first_level','second_level','third_level')
     * @return bool
     * @throws \NeedleProject\Common\Exception\NotFoundException
     */
    public function hasKeysInDepth($haystack, $keys)
    {
        $this->validateInput($haystack, $keys);
        return $this->getNode($haystack, $keys, static::USAGE_EXISTS);
    }

    /**
     * Get a value in depth of an array
     *
     * @param array $haystack - The array in which to search
     * @param array $keys     - an array with the linear items treated as depth.
     *                          Ex: array('first_level','second_level','third_level')
     * @return mixed
     * @throws \NeedleProject\Common\Exception\NotFoundException
     */
    public function getValueFromDepth($haystack, $keys)
    {
        $this->validateInput($haystack, $keys);
        return $this->getNode($haystack, $keys, static::USAGE_EXTRACT);
    }

    /**
     * @param array  $haystack
     * @param array  $keys
     * @param string $searchType    Added search type to reuse the same code
     *                              even if it increases the complexity (for ~20 line)
     * @throws \NeedleProject\Common\Exception\NotFoundException
     * @return mixed
     */
    private function getNode($haystack, $keys, $searchType)
    {
        $depth = count($keys);
        for ($i = 0; $i < $depth; $i++) {
            $needle = $keys[$i];
            $isLast = ($i + 1) === $depth;
            if ($isLast && array_key_exists($needle, $haystack)) {
                $node = $haystack[$needle];
                break;
            }
            if ($isLast || !isset($haystack[$needle]) || !is_array($haystack[$needle])) {
                if (static::USAGE_EXISTS === $searchType) {
                    return false;
                }
                throw new NotFoundException("Given path does not exists.");
            }
            $haystack = $haystack[$needle];
        }
        return (static::USAGE_EXISTS === $searchType) ? true : $node;
    }

    /**
     * Validate if the given input are valid array
     * @param array $haystack
     * @param array $keys
     */
    private function validateInput($haystack, $keys)
    {
        if (!is_array($haystack) || empty($haystack)) {
            throw new \InvalidArgumentException(
                "Provided input array is either empty or not an array."
            );
        }
        if (!is_array($keys) || empty($keys)) {
            throw new \InvalidArgumentException(
                "Provided input keys argument is either empty or not an array."
            );
        }
    }
}
