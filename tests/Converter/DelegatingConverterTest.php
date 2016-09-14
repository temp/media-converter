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
use Temp\MediaConverter\Converter\ConverterInterface;
use Temp\MediaConverter\Converter\ConverterResolver;
use Temp\MediaConverter\Converter\DelegatingConverter;
use Temp\MediaConverter\Format\Image;
use Temp\MediaConverter\Format\Specification;

/**
 * Delegating converter test
 *
 * @covers DelegatingConverter
 */
class DelegatingExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testAcceptReturnsFalseOnUnresolvedExtractor()
    {
        $resolver = $this->prophesize(ConverterResolver::class);

        $extractor = new DelegatingConverter($resolver->reveal());

        $resolver->resolve(Argument::type(Specification::class))->willReturn(null);

        $result = $extractor->accept(new Image());

        $this->assertFalse($result);
    }

    public function testSupportsReturnsTrueOnResolvedExtractor()
    {
        $resolver = $this->prophesize(ConverterResolver::class);
        $nestedConverter = $this->prophesize(ConverterInterface::class);

        $extractor = new DelegatingConverter($resolver->reveal());

        $resolver->resolve(Argument::type(Specification::class))->willReturn($nestedConverter->reveal());
        $nestedConverter->accept(Argument::type(Specification::class))->willReturn(true);

        $result = $extractor->accept(new Image());

        $this->assertTrue($result);
    }

    /**
     * @expectedException \Exception
     */
    public function testConvertThrowsExceptionOnUnresolvedConverter()
    {
        $resolver = $this->prophesize(ConverterResolver::class);

        $converter = new DelegatingConverter($resolver->reveal());

        $resolver->resolve(Argument::type(Specification::class))->willReturn(null);

        $result = $converter->convert('input', new Image(), 'output');
    }

    public function testConvertReturnsExtractedFilename()
    {
        $resolver = $this->prophesize(ConverterResolver::class);
        $nestedConverter = $this->prophesize(ConverterInterface::class);

        $extractor = new DelegatingConverter($resolver->reveal());

        $resolver->resolve(Argument::cetera())->willReturn($nestedConverter->reveal());
        $nestedConverter->convert('input', Argument::type(Specification::class), 'output')->willReturn('output');

        $result = $extractor->convert('input', new Image(), 'output');

        $this->assertSame('output', $result);
    }
}
