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
use Temp\MediaConverter\Format\Image;

/**
 * Delegating extractor test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class DelegatingExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportsReturnsFalseOnUnresolvedExtractor()
    {
        $resolver = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorResolver');
        $mediaClassifier = $this->prophesize('Temp\MediaClassifier\MediaClassifier');

        $extractor = new DelegatingExtractor($resolver->reveal(), $mediaClassifier->reveal());

        $mediaClassifier->classify('input')->willReturn(new MediaType('jpg', 'image'));
        $resolver->resolve('input', Argument::cetera())->willReturn(null);

        $result = $extractor->supports('input', new MediaType('jpg', 'image'), new Image());

        $this->assertFalse($result);
    }

    public function testSupportsReturnsTrueOnResolvedExtractor()
    {
        $resolver = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorResolver');
        $mediaClassifier = $this->prophesize('Temp\MediaClassifier\MediaClassifier');
        $nestedExtractor = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorInterface');

        $extractor = new DelegatingExtractor($resolver->reveal(), $mediaClassifier->reveal());

        $mediaClassifier->classify('input')->willReturn(new MediaType('jpg', 'image'));
        $resolver->resolve('input', Argument::cetera())->willReturn($nestedExtractor->reveal());
        $nestedExtractor->supports('input', Argument::cetera())->willReturn(true);

        $result = $extractor->supports('input', new MediaType('jpg', 'image'), new Image());

        $this->assertTrue($result);
    }

    public function testExtractReturnsNullOnUnresolvedExtractor()
    {
        $resolver = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorResolver');
        $mediaClassifier = $this->prophesize('Temp\MediaClassifier\MediaClassifier');

        $extractor = new DelegatingExtractor($resolver->reveal(), $mediaClassifier->reveal());

        $mediaClassifier->classify('input')->willReturn(new MediaType('jpg', 'image'));
        $resolver->resolve('input', Argument::cetera())->willReturn(null);

        $result = $extractor->extract('input', new Image());

        $this->assertNull($result);
    }

    public function testExtract()
    {
        $resolver = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorResolver');
        $mediaClassifier = $this->prophesize('Temp\MediaClassifier\MediaClassifier');
        $nestedExtractor = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorInterface');

        $extractor = new DelegatingExtractor($resolver->reveal(), $mediaClassifier->reveal());

        $mediaClassifier->classify('input')->willReturn(new MediaType('jpg', 'image'));
        $resolver->resolve('input', Argument::cetera())->willReturn($nestedExtractor->reveal());
        $nestedExtractor->extract('input', Argument::cetera())->willReturn('extracted');

        $result = $extractor->extract('input', new Image());

        $this->assertSame('extracted', $result);
    }
}
