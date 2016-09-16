<?php

namespace Droid\Test\Lib\Plugin\Model\File;

use PHPUnit_Framework_TestCase;

use Droid\Lib\Plugin\Model\File\NameValueLine;

class NameValueLineTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Droid\Lib\Plugin\Model\File\NameValueLine::getMappingValues
     */
    public function testGetMappingValuesWillReturnTheValueOfTheMappingField()
    {
        $line = new NameValueLine;

        $line
            ->setFieldValue(NameValueLine::FIELD_NAME, 'some-option-name')
            ->setFieldValue(NameValueLine::FIELD_VALUE, 'some-option-value')
        ;

        $this->assertSame(
            array('some-option-name'),
            $line->getMappingValues()
        );
    }

    /**
     * @covers \Droid\Lib\Plugin\Model\File\NameValueLine::setMappingFields
     */
    public function testSetMappingFieldWillAlterTheFieldUsedForMapping()
    {
        $line = new NameValueLine;

        $line
            ->setFieldValue(NameValueLine::FIELD_NAME, 'some-option-name')
            ->setFieldValue(NameValueLine::FIELD_VALUE, 'some-option-value')
        ;

        $line->setMappingFields(array(NameValueLine::FIELD_NAME));
        $this->assertSame(
            array('some-option-name'),
            $line->getMappingValues()
        );

        $line->setMappingFields(array(NameValueLine::FIELD_VALUE));
        $this->assertSame(
            array('some-option-value'),
            $line->getMappingValues()
        );
    }

    /**
     * @dataProvider provideInvalidOriginalLines
     * @covers \Droid\Lib\Plugin\Model\File\NameValueLine::parse
     * @param string $originalLine
     * @param string $exceptionMessage
     */
    public function testSetWithInvalidOriginalLineWillThrowException(
        $separator,
        $originalLine,
        $exceptionMessage
    ) {
        $line = new NameValueLine;

        $this->setExpectedException(
            '\DomainException',
            $exceptionMessage
        );

        $line
            ->setFieldSeparator($separator)
            ->set($originalLine)
        ;
    }

    public function provideInvalidOriginalLines()
    {
        return array(
            'Insufficient fields' => array(
                ' ',
                'SomeOptionName',
                'Expected a well-formed line of two fields "name value"'
            ),
            'Unexepected Separator' => array(
                '=',
                'SomeOptionName SomeValue',
                'Expected a well-formed line of two fields "name=value"'
            ),
        );
    }

    /**
     * @dataProvider provideValidOriginalLines
     * @covers \Droid\Lib\Plugin\Model\File\NameValueLine::parse
     * @covers \Droid\Lib\Plugin\Model\File\NameValueLine::normaliseWhitespace
     * @param string $separator
     * @param string $originalLine
     * @param string $expectedValues
     */
    public function testSetWithValidOriginalLineWillParseCorrectly(
        $separator,
        $originalLine,
        $expectedValues
    ) {
        $line = new NameValueLine;

        $line
            ->setFieldSeparator($separator)
            ->set($originalLine)
        ;

        $this->assertSame(
            $expectedValues,
            array(
                $line->getFieldValue(NameValueLine::FIELD_NAME),
                $line->getFieldValue(NameValueLine::FIELD_VALUE),
            )
        );
    }

    public function provideValidOriginalLines()
    {
        return array(
            'Space separated Name Value pair' => array(
                ' ',
                'SomeOption SomeValue',
                array('SomeOption', 'SomeValue')
            ),
            'Much space separated Name Value pair' => array(
                ' ',
                'SomeOption                      SomeValue',
                array('SomeOption', 'SomeValue')
            ),
            'Tab separated Name Value pair' => array(
                "\t",
                "SomeOption\tSomeValue",
                array('SomeOption', 'SomeValue')
            ),
            'Colon separated Name Value pair' => array(
                ':',
                'SomeOption:SomeValue',
                array('SomeOption', 'SomeValue')
            ),
            'Equals sign separated Name Value pair' => array(
                '=',
                'SomeOption=SomeValue',
                array('SomeOption', 'SomeValue')
            ),
            'Equals sign and space separated Name Value pair' => array(
                ' = ',
                'SomeOption = SomeValue',
                array('SomeOption', 'SomeValue')
            ),
            'Equals sign and much space separated Name Value pair' => array(
                ' = ',
                "SomeOption\t\t \t\t=          \t        SomeValue",
                array('SomeOption', 'SomeValue')
            ),
            'Leading white space is stripped' => array(
                ' ',
                '         SomeOption SomeValue',
                array('SomeOption', 'SomeValue')
            ),
            'Trailing white space is stripped' => array(
                ' ',
                'SomeOption SomeValue         ',
                array('SomeOption', 'SomeValue')
            ),
            'Space separated value (e.g. SSH AcceptEnv) is legal' => array(
                ' ',
                'SomeOption Some Values',
                array('SomeOption', 'Some Values')
            ),
            'White space is permitted within a double quoted value' => array(
                ' ',
                'SomeOption "Some Value"',
                array('SomeOption', 'Some Value')
            ),
            'White space within a quoted value IS NOT preserved exactly' => array(
                ' ',
                "SomeOption \"  Some \t Value  \"",
                array('SomeOption', ' Some Value ')
            ),
            'Improperly double-quoted value' => array(
                ' ',
                'SomeOption "Some Value',
                array('SomeOption', '"Some Value')
            ),
            'Another improperly double-quoted value' => array(
                ' ',
                'SomeOption Some Value"',
                array('SomeOption', 'Some Value"')
            ),
            'Another improperly quoted value' => array(
                ' ',
                'SomeOption \'Some Value\'',
                array('SomeOption', '\'Some Value\'')
            )
        );
    }
}
