<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_PDF
 * @subpackage Zend_PDF_Internal
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\Pdf\InternalType;
use Zend\Pdf;

/**
 * PDF file 'binary string' element implementation
 *
 * @uses       \Zend\Pdf\InternalType\StringObject
 * @category   Zend
 * @package    Zend_PDF
 * @subpackage Zend_PDF_Internal
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class BinaryStringObject extends StringObject
{
    /**
     * Object value
     *
     * @var string
     */
    public $value;


    /**
     * Escape string according to the PDF rules
     *
     * @param string $inStr
     * @return string
     */
    public static function escape($inStr)
    {
        return strtoupper(bin2hex($inStr));
    }


    /**
     * Unescape string according to the PDF rules
     *
     * @param string $inStr
     * @return string
     */
    public static function unescape($inStr)
    {
        $chunks = array();
        $offset = 0;
        $length = 0;
        while ($offset < strlen($inStr)) {
            // Collect hexadecimal characters
            $start = $offset;
            $offset += strspn($inStr, "0123456789abcdefABCDEF", $offset);
            $chunks[] = substr($inStr, $start, $offset - $start);
            $length += strlen(end($chunks));

            // Skip non-hexadecimal characters
            $offset += strcspn($inStr, "0123456789abcdefABCDEF", $offset);
        }
        if ($length % 2 != 0) {
            // We have odd number of digits.
            // Final digit is assumed to be '0'
            $chunks[] = '0';
        }

        return pack('H*' , implode($chunks));
    }


    /**
     * Return object as string
     *
     * @param \Zend\Pdf\ObjectFactory $factory
     * @return string
     */
    public function toString(Pdf\ObjectFactory $factory = null)
    {
        return '<' . self::escape((string)$this->value) . '>';
    }
}
