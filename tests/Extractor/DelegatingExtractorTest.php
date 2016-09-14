<?php

/*
 * This file is part of the Media Converter package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MediaConverter\Extractor\Tests;

use Prophecy\Argument;
use Temp\MediaClassifier\MediaClassifier;
use Temp\MediaClassifier\Model\MediaType;
use Temp\MediaConverter\Extractor\DelegatingExtractor;
use Temp\MediaConverter\Extractor\ExtractorInterface;
use Temp\MediaConverter\Extractor\ExtractorResolver;
use Temp\MediaConverter\Format\Image;

/**
 * Delegating extractor test
 *
 * @covers DelegatingExtractor
 */
class DelegatingExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportsReturnsFalseOnUnresolvedExtractor()
    {
        $resolver = $this->prophesize(ExtractorResolver::class);
        $mediaClassifier = $this->prophesize(MediaClassifier::class);

        $extractor = new DelegatingExtractor($resolver->reveal(), $mediaClassifier->reveal());

        $mediaClassifier->classify('input')->willReturn(new MediaType('jpg', 'image'));
        $resolver->resolve('input', Argument::cetera())->willReturn(null);

        $result = $extractor->supports('input', new MediaType('jpg', 'image'), new Image());

        $this->assertFalse($result);
    }

    public function testSupportsReturnsTrueOnResolvedExtractor()
    {
        $resolver = $this->prophesize(ExtractorResolver::class);
        $mediaClassifier = $this->prophesize(MediaClassifier::class);
        $nestedExtractor = $this->prophesize(ExtractorInterface::class);

        $extractor = new DelegatingExtractor($resolver->reveal(), $mediaClassifier->reveal());

        $mediaClassifier->classify('input')->willReturn(new MediaType('jpg', 'image'));
        $resolver->resolve('input', Argument::cetera())->willReturn($nestedExtractor->reveal());
        $nestedExtractor->supports('input', Argument::cetera())->willReturn(true);

        $result = $extractor->supports('input', new MediaType('jpg', 'image'), new Image());

        $this->assertTrue($result);
    }

    public function testExtractReturnsNullOnUnclassifiedFile()
    {
        $resolver = $this->prophesize(ExtractorResolver::class);
        $mediaClassifier = $this->prophesize(MediaClassifier::class);

        $extractor = new DelegatingExtractor($resolver->reveal(), $mediaClassifier->reveal());

        $mediaClassifier->classify('input')->willReturn(false);
        $resolver->resolve('input', Argument::cetera())->shouldNotBeCalled();

        $result = $extractor->extract('input', new Image());

        $this->assertNull($result);
    }

    public function testExtractReturnsNullOnUnresolvedExtractor()
    {
        $resolver = $this->prophesize(ExtractorResolver::class);
        $mediaClassifier = $this->prophesize(MediaClassifier::class);

        $extractor = new DelegatingExtractor($resolver->reveal(), $mediaClassifier->reveal());

        $mediaClassifier->classify('input')->willReturn(new MediaType('jpg', 'image'));
        $resolver->resolve('input', Argument::cetera())->willReturn(null);

        $result = $extractor->extract('input', new Image());

        $this->assertNull($result);
    }

    public function testExtractReturnsExtractedFilename()
    {
        $resolver = $this->prophesize(ExtractorResolver::class);
        $mediaClassifier = $this->prophesize(MediaClassifier::class);
        $nestedExtractor = $this->prophesize(ExtractorInterface::class);

        $extractor = new DelegatingExtractor($resolver->reveal(), $mediaClassifier->reveal());

        $mediaClassifier->classify('input')->willReturn(new MediaType('jpg', 'image'));
        $resolver->resolve('input', Argument::cetera())->willReturn($nestedExtractor->reveal());
        $nestedExtractor->extract('input', Argument::cetera())->willReturn('extracted');

        $result = $extractor->extract('input', new Image());

        $this->assertSame('extracted', $result);
    }
}
