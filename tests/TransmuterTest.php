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
use Temp\MediaConverter\Format\Image;
use Temp\MediaConverter\Transmuter;

/**
 * Transmuter test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class TransmuterTest extends \PHPUnit_Framework_TestCase
{
    public function testTransmuterWillNotCallConvertWithoutExtractedFile()
    {
        $extractor = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorInterface');
        $converter = $this->prophesize('Temp\MediaConverter\Converter\ConverterInterface');
        $transmuter = new Transmuter($extractor->reveal(), $converter->reveal());

        $spec = new Image();

        $extractor->extract('input', $spec)->willReturn(null);
        $converter->convert(Argument::cetera())->shouldNotBeCalled();

        $transmuter->transmute('input', new Image(), 'output');
    }

    public function testTransmuteCallsConvertWithExtractedFile()
    {
        $extractor = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorInterface');
        $converter = $this->prophesize('Temp\MediaConverter\Converter\ConverterInterface');
        $transmuter = new Transmuter($extractor->reveal(), $converter->reveal());

        $spec = new Image();

        $extractor->extract('input', $spec)->willReturn('extracted');
        $converter->convert('extracted', $spec, 'output')->shouldBeCalled();

        $result = $transmuter->transmute('input', new Image(), 'output');

        $this->assertSame('output', $result);
    }

    public function testTransmuteToImage()
    {
        $extractor = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorInterface');
        $converter = $this->prophesize('Temp\MediaConverter\Converter\ConverterInterface');
        $transmuter = new Transmuter($extractor->reveal(), $converter->reveal());

        $extractor->extract('input', Argument::type('Temp\MediaConverter\Format\Image'))->willReturn('extracted');
        $converter->convert('extracted', Argument::type('Temp\MediaConverter\Format\Image'), 'output')->shouldBeCalled();

        $result = $transmuter->transmuteToImage('input', 'output');

        $this->assertSame('output', $result);
    }

    public function testTransmuteToAudio()
    {
        $extractor = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorInterface');
        $converter = $this->prophesize('Temp\MediaConverter\Converter\ConverterInterface');
        $transmuter = new Transmuter($extractor->reveal(), $converter->reveal());

        $extractor->extract('input', Argument::type('Temp\MediaConverter\Format\Audio'))->willReturn('extracted');
        $converter->convert('extracted', Argument::type('Temp\MediaConverter\Format\Audio'), 'output')->shouldBeCalled();

        $result = $transmuter->transmuteToAudio('input', 'output');

        $this->assertSame('output', $result);
    }

    public function testTransmuteToVideo()
    {
        $extractor = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorInterface');
        $converter = $this->prophesize('Temp\MediaConverter\Converter\ConverterInterface');
        $transmuter = new Transmuter($extractor->reveal(), $converter->reveal());

        $extractor->extract('input', Argument::type('Temp\MediaConverter\Format\Video'))->willReturn('extracted');
        $converter->convert('extracted', Argument::type('Temp\MediaConverter\Format\Video'), 'output')->shouldBeCalled();

        $result = $transmuter->transmuteToVideo('input', 'output');

        $this->assertSame('output', $result);
    }
}
