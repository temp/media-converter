<?php

/*
 * This file is part of the Media Converter package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MediaConverter\Tests\Converter;

use Prophecy\Argument;
use Temp\MediaClassifier\MediaClassifier;
use Temp\MediaClassifier\Model\MediaType;
use Temp\MediaConverter\Converter\DelegatingConverter;
use Temp\MediaConverter\Extractor\DelegatingExtractor;
use Temp\MediaConverter\Format\Image;

/**
 * Delegating converter test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class DelegatingExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testAcceptReturnsFalseOnUnresolvedExtractor()
    {
        $resolver = $this->prophesize('Temp\MediaConverter\Converter\ConverterResolver');

        $extractor = new DelegatingConverter($resolver->reveal());

        $resolver->resolve(Argument::type('Temp\MediaConverter\Format\Specification'))->willReturn(null);

        $result = $extractor->accept(new Image());

        $this->assertFalse($result);
    }

    public function testSupportsReturnsTrueOnResolvedExtractor()
    {
        $resolver = $this->prophesize('Temp\MediaConverter\Converter\ConverterResolver');
        $nestedConverter = $this->prophesize('Temp\MediaConverter\Converter\ConverterInterface');

        $extractor = new DelegatingConverter($resolver->reveal());

        $resolver->resolve(Argument::type('Temp\MediaConverter\Format\Specification'))->willReturn($nestedConverter->reveal());
        $nestedConverter->accept(Argument::type('Temp\MediaConverter\Format\Specification'))->willReturn(true);

        $result = $extractor->accept(new Image());

        $this->assertTrue($result);
    }

    /**
     * @expectedException \Exception
     */
    public function testConvertThrowsExceptionOnUnresolvedConverter()
    {
        $resolver = $this->prophesize('Temp\MediaConverter\Converter\ConverterResolver');

        $converter = new DelegatingConverter($resolver->reveal());

        $resolver->resolve(Argument::type('Temp\MediaConverter\Format\Specification'))->willReturn(null);

        $result = $converter->convert('input', new Image(), 'output');
    }

    public function testConvertReturnsExtractedFilename()
    {
        $resolver = $this->prophesize('Temp\MediaConverter\Converter\ConverterResolver');
        $nestedConverter = $this->prophesize('Temp\MediaConverter\Converter\ConverterInterface');

        $extractor = new DelegatingConverter($resolver->reveal());

        $resolver->resolve(Argument::cetera())->willReturn($nestedConverter->reveal());
        $nestedConverter->convert('input', Argument::type('Temp\MediaConverter\Format\Specification'), 'output')->willReturn('output');

        $result = $extractor->convert('input', new Image(), 'output');

        $this->assertSame('output', $result);
    }
}
