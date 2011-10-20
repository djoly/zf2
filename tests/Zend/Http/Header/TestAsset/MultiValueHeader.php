<?php
namespace ZendTest\Http\Header\TestAsset;

use Zend\Http\Header\MultiValueHeader as MultiVal;

class MultiValueHeader extends MultiVal
{
    public static function fromString($headerLine)
    {
        $header = new static();
        
        list($name, $values) = preg_split('#: #', $headerLine, 2);
        
        $header->addValues($values);
        return $header;
    }
    
    public function getFieldName()
    {
        return 'Test';
    }
}