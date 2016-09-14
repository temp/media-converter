<?php

/*
 * This file is part of the Media Converter package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MediaConverter\Tests;

use Prophecy\Argument;
use Temp\MediaConverter\Converter\ConverterInterface;
use Temp\MediaConverter\Extractor\ExtractorInterface;
use Temp\MediaConverter\Format;
use Temp\MediaConverter\Transmuter;

/**
 * Transmuter test
 *
 * @covers Transmuter
 */
class TransmuterTest extends \PHPUnit_Framework_TestCase
{
    public function testTransmuterWillNotCallConvertWithoutExtractedFile()
    {
        $extractor = $this->prophesize(ExtractorInterface::class);
        $converter = $this->prophesize(ConverterInterface::class);
        $transmuter = new Transmuter($extractor->reveal(), $converter->reveal());

        $spec = new Format\Image();

        $extractor->extract('input', $spec)->willReturn(null);
        $converter->convert(Argument::cetera())->shouldNotBeCalled();

        $transmuter->transmute('input', new Format\Image(), 'output');
    }

    public function testTransmuteCallsConvertWithExtractedFile()
    {
        $extractor = $this->prophesize(ExtractorInterface::class);
        $converter = $this->prophesize(ConverterInterface::class);
        $transmuter = new Transmuter($extractor->reveal(), $converter->reveal());

        $spec = new Format\Image();

        $extractor->extract('input', $spec)->willReturn('extracted');
        $converter->convert('extracted', $spec, 'output')->shouldBeCalled();

        $result = $transmuter->transmute('input', new Format\Image(), 'output');

        $this->assertSame('output', $result);
    }

    public function testTransmuteToImage()
    {
        $extractor = $this->prophesize(ExtractorInterface::class);
        $converter = $this->prophesize(ConverterInterface::class);
        $transmuter = new Transmuter($extractor->reveal(), $converter->reveal());

        $extractor->extract('input', Argument::type('Temp\MediaConverter\Format\Image'))->willReturn('extracted');
        $converter->convert('extracted', Argument::type('Temp\MediaConverter\Format\Image'), 'output')->shouldBeCalled();

        $result = $transmuter->transmuteToImage('input', 'output');

        $this->assertSame('output', $result);
    }

    public function testTransmuteToAudio()
    {
        $extractor = $this->prophesize(ExtractorInterface::class);
        $converter = $this->prophesize(ConverterInterface::class);
        $transmuter = new Transmuter($extractor->reveal(), $converter->reveal());

        $extractor->extract('input', Argument::type(Format\Audio::class))->willReturn('extracted');
        $converter->convert('extracted', Argument::type(Format\Audio::class), 'output')->shouldBeCalled();

        $result = $transmuter->transmuteToAudio('input', 'output');

        $this->assertSame('output', $result);
    }

    public function testTransmuteToVideo()
    {
        $extractor = $this->prophesize(ExtractorInterface::class);
        $converter = $this->prophesize(ConverterInterface::class);
        $transmuter = new Transmuter($extractor->reveal(), $converter->reveal());

        $extractor->extract('input', Argument::type(Format\Video::class))->willReturn('extracted');
        $converter->convert('extracted', Argument::type(Format\Video::class), 'output')->shouldBeCalled();

        $result = $transmuter->transmuteToVideo('input', 'output');

        $this->assertSame('output', $result);
    }
}
