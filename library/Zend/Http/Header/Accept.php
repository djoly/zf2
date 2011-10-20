<?php

namespace Zend\Http\Header;

/**
 * @todo Implement q and level lookups
 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.1
 */
class Accept extends MultiValueHeader
{
    public static function fromString($headerLine)
    {
        $acceptHeader = new static();

        list($name, $values) = preg_split('#: #', $headerLine, 2);

        // check to ensure proper header type for this factory
        if (strtolower($name) !== 'accept') {
            throw new Exception\InvalidArgumentException('Invalid header line for accept header string');
        }
		
        $acceptHeader->addValues($values);
        return $acceptHeader;
    }

    public function getFieldName()
    {
        return 'Accept';
    }

//
//    /**
//     * Get the quality factor of the value (q=)
//     *
//     * @param string $value
//     * @return float
//     */
//    public function getQualityFactor($value)
//    {
//        if ($this->hasValue($value)) {
//            if (!empty($this->arrayValue)) {
//                if (isset($this->arrayValue[$value])) {
//                    foreach ($this->arrayValue[$value] as $val) {
//                        if (preg_match('/q=(\d\.?\d?)/',$val,$matches)) {
//                            return $matches[1];
//                        }
//                    }
//                }
//                return 1;
//            }
//        }
//        return false;
//    }
//
//    /**
//     * Get the level of a value (level=)
//     *
//     * @param string $value
//     * @return integer
//     */
//    public function getLevel($value)
//    {
//        if ($this->hasValue($value)) {
//            if (isset($this->arrayValue[$value])) {
//                foreach ($this->arrayValue[$value] as $val) {
//                    if (preg_match('/level=(\d+)/',$val,$matches)) {
//                        return $matches[1];
//                    }
//                }
//            }
//        }
//        return false;
//    }
//

}
