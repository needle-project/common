<?php
/**
 * This file is part of the NeedleProject\Common package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NeedleProject\Common\Convertor;

/**
 * Class CompoundWordConvertor
 *
 * @package NeedleProject\Common\Convertor
 * @author Adrian Tilita <adrian@tilita.ro>
 * @copyright 2018 Adrian Tilita
 * @license https://opensource.org/licenses/MIT MIT Licence
 */
class CompoundWordConvertor
{
    /**
     * Convert any string to camelCase
     * @param string $word
     * @return string
     */
    public static function convertToCamelCase($word)
    {
        return lcfirst(self::convertToPascalCase($word));
    }

    /**
     * Convert any string to PascalCase
     * @param string $word
     * @return string
     */
    public static function convertToPascalCase($word)
    {
        // separate into block for easier readability
        $word = str_replace('_', ' ', $word);
        $word = strtolower($word);
        $word = ucwords($word);
        return str_replace(' ', '', $word);
    }

    /**
     * Convert any string to underscore_string (snake_case)
     * @param string $word
     * @return string
     */
    public static function convertToSnakeCase($word)
    {
        // append an _ to all capital letters. Ex: Abc will be converted to _Abc
        $word = preg_replace('/(?<!^)[A-Z]/', '_$0', $word);
        // replace spaces to underscore
        $word = preg_replace('/\s/','_', $word);
        // replace multiple spaces to one underscore
        $word = preg_replace('/\s\s+/','_', $word);
        // replace multiple underscores to one underscore
        $word = preg_replace('/([_]+)/','_', $word);
        return strtolower($word);
    }
}
