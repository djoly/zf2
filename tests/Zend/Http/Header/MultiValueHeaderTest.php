<?php
namespace ZendTest\Http\Header;

use ZendTest\Http\Header\TestAsset\MultiValueHeader;

class MultiValueHeaderTest extends \PHPUnit_Framework_TestCase
{
    public function testAddAndCheckForValuesOneAtATime()
    {
        $header = new MultiValueHeader();
        $header->addValue('text/html');
        $this->assertTrue($header->hasValue('text/html'));
        $this->assertFalse($header->hasValue('text/xml'));
        $header->addValue('text/xml');
        $this->assertTrue($header->hasValue('text/xml'));
    }
    
    public function testAddQualityFactoryInValueString()
    {
        $header = new MultiValueHeader();
        $header->addValue('text/html;q=0.5')
            ->addValue('text/xml;q=0.3')
            ->addValue('text/plain; q=0.1');
            
        $this->assertEquals(0.5,$header->getQualityFactor('text/html'));
        $this->assertEquals(0.3,$header->getQualityFactor('text/xml'));
        $this->assertEquals(0.1,$header->getQualityFactor('text/plain'));
    }
    
    public function testQualityFactorDefaultToOne()
    {
        $header = new MultiValueHeader();
        $header->addValue('text/html;q=0.5')
            ->addValue('text/xml')
            ->addValue('text/plain; q=0.1');
            
        $this->assertEquals(0.5,$header->getQualityFactor('text/html'));
        $this->assertEquals(1,$header->getQualityFactor('text/xml'));
        $this->assertEquals(0.1,$header->getQualityFactor('text/plain'));
    }
    
    public function testAddLevelInValueString()
    {
        $header = new MultiValueHeader();
        $header->addValue('text/html;level=1')
            ->addValue('text/xml')
            ->addValue('text/plain; level=2');
            
        $this->assertEquals(1,$header->getLevel('text/html'));
        $this->assertEquals(false,$header->getLevel('text/xml'));
        $this->assertEquals(2,$header->getLevel('text/plain'));
    }
    
    public function testAddValuesUsingSpecArgument()
    {
        $header = new MultiValueHeader();
        $header->addValue('text/html','level=1');
        $header->addValue('text/xml',array('level' => 2));
        $header->addValue('text/plain','q=0.8');
        $header->addValue('text/*',array('q' => 0.3));
        
        $this->assertEquals(1,$header->getLevel('text/html'));
        $this->assertEquals(2,$header->getLevel('text/xml'));
        $this->assertEquals(0.8,$header->getQualityFactor('text/plain'));
        $this->assertEquals(0.3,$header->getQualityFactor('text/*'));
    }
    
    public function testAddSeveralValuesFromString()
    {
        $header = new MultiValueHeader();
        $header->addValues('text/html, text/xml; q=0.4, text/plain; level=2');
        
        $this->assertTrue($header->hasValue('text/html'));
        $this->assertTrue($header->hasValue('text/xml'));
        $this->assertTrue($header->hasValue('text/plain'));
        
        $this->assertEquals(1,$header->getQualityFactor('text/html'));
        $this->assertEquals(0.4,$header->getQualityFactor('text/xml'));
        $this->assertEquals(2,$header->getLevel('text/plain'));
    }
    
    public function testAddSeveralValuesFromArrayWithStringSpecs()
    {
        $header = new MultiValueHeader();
        $header->addValues(array('text/html','text/xml; q=0.4','text/plain; level=2'));
        
        $this->assertTrue($header->hasValue('text/html'));
        $this->assertTrue($header->hasValue('text/xml'));
        $this->assertTrue($header->hasValue('text/plain'));
        
        $this->assertEquals(1,$header->getQualityFactor('text/html'));
        $this->assertEquals(0.4,$header->getQualityFactor('text/xml'));
        $this->assertEquals(2,$header->getLevel('text/plain'));
    }
    
    public function testAddSeveralValuesFromArrayWithArraySpecs()
    {
        $header = new MultiValueHeader();
        $header->addValues(array(
            array('text/html'),
            array('text/xml',array('q' => 0.4)),
            array('text/plain',array('level' => 2))
        ));
        
        $this->assertTrue($header->hasValue('text/html'));
        $this->assertTrue($header->hasValue('text/xml'));
        $this->assertTrue($header->hasValue('text/plain'));
        
        $this->assertEquals(1,$header->getQualityFactor('text/html'));
        $this->assertEquals(0.4,$header->getQualityFactor('text/xml'));
        $this->assertEquals(2,$header->getLevel('text/plain'));
    }
    
    
    public function testFieldValue()
    {
        $header = new MultiValueHeader();
        $header->addValues('text/html, text/xml; q=0.4, text/plain; level=2');   
        $this->assertEquals('text/html,text/xml;q=0.4,text/plain;level=2',$header->getFieldValue());
        
        $header = new MultiValueHeader();
        $header->addValues(array('text/html','text/xml; q=0.4','text/plain; level=2'));
        $this->assertEquals('text/html,text/xml;q=0.4,text/plain;level=2',$header->getFieldValue());
        
        $header = new MultiValueHeader();
        $header->addValues(array(
            array('text/html'),
            array('text/xml',array('q' => 0.4)),
            array('text/plain',array('level' => 2))
        ));
        $this->assertEquals('text/html,text/xml;q=0.4,text/plain;level=2',$header->getFieldValue());
    }
    
    public function testToString()
    {
        $header = new MultiValueHeader();
        $header->addValues('text/html, text/xml; q=0.4, text/plain; level=2');   
        $this->assertEquals('Test: text/html,text/xml;q=0.4,text/plain;level=2',$header->toString());

    }
    
    public function testFromString()
    {
        $header = MultiValueHeader::fromString('Test: text/html,text/xml;q=0.4,text/plain;level=2');
        $this->assertTrue($header->hasValue('text/html')); 
        $this->assertTrue($header->hasValue('text/xml'));  
        $this->assertTrue($header->hasValue('text/plain'));
    }
}