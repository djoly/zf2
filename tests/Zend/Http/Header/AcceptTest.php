<?php

namespace ZendTest\Http\Header;

use Zend\Http\Header\Accept;

class AcceptTest extends \PHPUnit_Framework_TestCase
{

    public function testAcceptFromStringCreatesValidAcceptHeader()
    {
        $acceptHeader = Accept::fromString('Accept: xxx');
        $this->assertInstanceOf('Zend\Http\Header\HeaderDescription', $acceptHeader);
        $this->assertInstanceOf('Zend\Http\Header\Accept', $acceptHeader);
    }

    public function testAcceptGetFieldNameReturnsHeaderName()
    {
        $acceptHeader = new Accept();
        $this->assertEquals('Accept', $acceptHeader->getFieldName());
    }

    public function testAcceptGetFieldValueReturnsProperValue()
    {
        $acceptHeader = new Accept();
        $acceptHeader->addValue('text/html');
        $this->assertEquals('text/html', $acceptHeader->getFieldValue());
    }

    public function testAcceptToStringReturnsHeaderFormattedString()
    {
        $acceptHeader = new Accept();
        $acceptHeader->addValue('text/xml')
                     ->addValue('text/html;level=1')
                     ->addValue('text/*;q=0.8');
        $this->assertEquals('Accept: text/xml,text/html;level=1,text/*;q=0.8', $acceptHeader->toString());
    }
    
    
    /**
     * Test quality factor value
     */
    public function testQualityFactor()
    {
        $header = Accept::fromString('Accept: text/plain; q=0.5, text/html,text/x-dvi; q=0.8, text/x-c');
        $this->assertTrue($header->hasValue('text/plain'));
        $this->assertEquals($header->getQualityFactor('text/plain'), 0.5);
        $this->assertTrue($header->hasValue('text/html'));
        $this->assertEquals($header->getQualityFactor('text/html'), 1); // by default
        $this->assertTrue($header->hasValue('text/x-dvi'));
        $this->assertEquals($header->getQualityFactor('text/x-dvi'), 0.8);
    }

    /**
     * Test level value
     */
    public function testLevel()
    {
        $header= Accept::fromString('Accept: text/html;level=1, text/xml;level=2,text/*');
        $this->assertTrue($header->hasValue('text/html'));
        $this->assertEquals($header->getLevel('text/html'), 1);
        $this->assertTrue($header->hasValue('text/xml'));
        $this->assertEquals($header->getLevel('text/xml'), 2); // by default
        $this->assertTrue($header->hasValue('text/*'));
        $this->assertEquals($header->getLevel('text/*'),false);
    }

    /** Implmentation specific tests here */

//    /**
//     * Test construct with type
//     */
//    public function testConstructWithType()
//    {
//        $header= new Header('Accept');
//        $this->assertEquals($header->getType(), 'Accept');
//    }
//
//    /**
//     * Test construct with type and value
//     */
//    public function testConstructWithTypeValue()
//    {
//        $header= new Header('Accept', 'text/html');
//        $this->assertEquals($header->getType(), 'Accept');
//        $this->assertEquals($header->getValue(), 'text/html');
//    }
//
//    /**
//     * Test construct with a header encoded in a raw string
//     */
//    public function testConstructWithRawString()
//    {
//        $header= new Header('Accept: text/html');
//        $this->assertEquals($header->getType(), 'Accept');
//        $this->assertEquals($header->getValue(), 'text/html');
//    }
//
//    /**
//     * Test construct with Accept-Charset type and multiple values
//     */
//    public function testConstructAcceptMultipleValue()
//    {
//        $header= new Header('Accept-Charset: iso-8859-1, utf-8');
//        $this->assertEquals($header->getValue(), 'iso-8859-1, utf-8');
//    }
//
//    /**
//     * Test normalize header type
//     */
//    public function testNormalizeHeaderType()
//    {
//        $header= new Header('accept');
//        $this->assertEquals($header->getType(), 'Accept');
//        $header->setType('Accept charset');
//        $this->assertEquals($header->getType(), 'Accept-Charset');
//    }
//
//    /**
//     * Test load header from a raw string
//     */
//    public function testLoadFromString()
//    {
//        $header= new Header('Accept');
//        $this->assertTrue($header->fromString('Accept: text/html'));
//        $this->assertEquals($header->getType(), 'Accept');
//        $this->assertEquals($header->getValue(), 'text/html');
//    }
//
//    /**
//     * Test to string
//     */
//    public function testToString()
//    {
//        $header= new Header('Accept', 'text/html');
//        $this->assertEquals((string) $header,"Accept: text/html\r\n");
//    }
//
//    /**
//     * Test load header from an invalid raw string
//     */
//    public function testLoadFromInvalidString()
//    {
//        $header= new Header('Accept');
//        $this->setExpectedException(
//            'Zend\Http\Exception\InvalidArgumentException',
//            'The header specified is not valid'
//        );
//        $header->fromString('text/html');
//    }
//
//    /**
//     * Test set type
//     */
//    public function testSetType()
//    {
//        $header= new Header('Accept');
//        $header->setType('Accept-Encoding');
//        $this->assertEquals($header->getType(), 'Accept-Encoding');
//    }
//
//    /**
//     * Test set value
//     */
//    public function testSetValue()
//    {
//        $header= new Header('Accept');
//        $header->setValue('text/html');
//        $this->assertEquals($header->getValue(), 'text/html');
//    }
//
//    /**
//     * Test has value
//     */
//    public function testHasValue()
//    {
//        $header= new Header('Accept: text/html');
//        $this->assertTrue($header->hasValue('text/html'));
//        $this->assertEquals($header->getValue(), 'text/html');
//    }
//
//    /**
//     * Test has value with multiple values
//     */
//    public function testHasValueWithMultiple()
//    {
//        $header= new Header('Accept: text/html, text/plain');
//        $this->assertTrue($header->hasValue('text/html'));
//        $this->assertTrue($header->hasValue('text/plain'));
//        $this->assertEquals($header->getValue(), 'text/html, text/plain');
//    }
//
//    /**
//     * Test quality factor value
//     */
//    public function testQualityFactor()
//    {
//        $header= new Header('Accept-Charset: iso-8859-1, utf-8;q=0.5, *;q=0.5');
//        $this->assertTrue($header->hasValue('utf-8'));
//        $this->assertEquals($header->getQualityFactor('utf-8'), '0.5');
//        $this->assertTrue($header->hasValue('iso-8859-1'));
//        $this->assertEquals($header->getQualityFactor('iso-8859-1'), '1'); // by default
//        $this->assertTrue($header->hasValue('*'));
//        $this->assertEquals($header->getQualityFactor('*'), '0.5');
//    }
//
//    /**
//     * Test level value
//     */
//    public function testLevel()
//    {
//        $header= new Header('Accept-Charset: iso-8859-1;level=1, utf-8;q=0.5;level=2, *;q=0.5');
//        $this->assertTrue($header->hasValue('utf-8'));
//        $this->assertEquals($header->getLevel('utf-8'), '2');
//        $this->assertTrue($header->hasValue('iso-8859-1'));
//        $this->assertEquals($header->getLevel('iso-8859-1'), '1'); // by default
//        $this->assertTrue($header->hasValue('*'));
//        $this->assertEquals($header->getLevel('*'),false);
//    }

}
