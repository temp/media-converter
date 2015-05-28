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
use Temp\MediaConverter\Converter\AudioConverter;
use Temp\MediaConverter\Format\Audio;
use Temp\MediaConverter\Format\Image;
use Temp\MediaConverter\Format\Video;

/**
 * Audio converter test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class AudioConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testAccept()
    {
        $ffmpeg = $this->prophesize('FFMpeg\FFMpeg');
        $converter = new AudioConverter($ffmpeg->reveal());

        $this->assertTrue($converter->accept(new Audio()));
        $this->assertFalse($converter->accept(new Image()));
        $this->assertFalse($converter->accept(new Video()));
    }

    public function testConvert()
    {
        $ffmpeg = $this->prophesize('FFMpeg\FFMpeg');
        $audio = $this->prophesize('FFMpeg\Media\Audio');
        $converter = new AudioConverter($ffmpeg->reveal());

        $ffmpeg->open('input')->willReturn($audio->reveal());
        $audio->save(Argument::any(), 'output')->shouldBeCalled();

        $converter->convert('input', new Audio(), 'output');
    }

    public function testConvertFull()
    {
        $ffmpeg = $this->prophesize('FFMpeg\FFMpeg');
        $audio = $this->prophesize('FFMpeg\Media\Audio');
        $converter = new AudioConverter($ffmpeg->reveal());

        $ffmpeg->open('input')->willReturn($audio->reveal());
        $audio->save(Argument::any(), 'output')->shouldBeCalled();
        $audio->addFilter(Argument::cetera())->shouldBeCalled();

        $spec = new Audio();
        $spec->setAudioFormat('aac')
            ->setAudioBitrate(100)
            ->setAudioChannels(2)
            ->setAudioCodec('libfdk_aac')
            ->setAudioSamplerate(1000);

        $converter->convert('input', $spec, 'output');
    }
}
