<?php

namespace Droid\Test\Lib\Plugin\Model\File;

use PHPUnit_Framework_TestCase;

use Droid\Lib\Plugin\Model\File\LineFactory;
use Droid\Lib\Plugin\Model\File\NameValueLine;

class LineFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Droid\Lib\Plugin\Model\File\LineFactory::__construct
     * @covers \Droid\Lib\Plugin\Model\File\LineFactory::makeLine
     */
    public function testMakeLineWillReturnInstanceofTheSuppliedLineClass()
    {
        $fac = new LineFactory(NameValueLine::class, '=');
        $fac->setMappingFields(array('foo'));
        $this->assertInstanceof(
            NameValueLine::class,
            $fac->makeLine(),
            'Factory produces instances of the supplied line class name.'
        );
    }

    /**
     * @covers \Droid\Lib\Plugin\Model\File\LineFactory::__construct
     * @covers \Droid\Lib\Plugin\Model\File\LineFactory::getFieldSeparator
     * @covers \Droid\Lib\Plugin\Model\File\LineFactory::setFieldSeparator
     */
    public function testFieldSeparatorAccessAndMutation()
    {
        $fac = new LineFactory(NameValueLine::class, 'some-sep');
        $this->assertSame('some-sep', $fac->getFieldSeparator());
        $fac->setFieldSeparator('other-sep');
        $this->assertSame('other-sep', $fac->getFieldSeparator());
    }

    /**
     * @covers \Droid\Lib\Plugin\Model\File\LineFactory::getMappingFields
     * @covers \Droid\Lib\Plugin\Model\File\LineFactory::setMappingFields
     */
    public function testMappingFieldsAccessAndMutation()
    {
        $fac = new LineFactory(NameValueLine::class, 'some-sep');
        $this->assertNull($fac->getMappingFields());
        $fac->setMappingFields(array('foo'));
        $this->assertSame(array('foo'), $fac->getMappingFields());
    }
}
