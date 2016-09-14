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

use FFMpeg\FFMpeg;
use FFMpeg\Media;
use Prophecy\Argument;
use Temp\MediaConverter\Converter\AudioConverter;
use Temp\MediaConverter\Format;

/**
 * Audio converter test
 *
 * @covers AudioConverter
 */
class AudioConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testAccept()
    {
        $ffmpeg = $this->prophesize(FFMpeg::class);
        $converter = new AudioConverter($ffmpeg->reveal());

        $this->assertTrue($converter->accept(new Format\Audio()));
        $this->assertFalse($converter->accept(new Format\Image()));
        $this->assertFalse($converter->accept(new Format\Video()));
    }

    public function testConvert()
    {
        $ffmpeg = $this->prophesize(FFMpeg::class);
        $audio = $this->prophesize(Media\Audio::class);
        $converter = new AudioConverter($ffmpeg->reveal());

        $ffmpeg->open('input')->willReturn($audio->reveal());
        $audio->save(Argument::any(), 'output')->shouldBeCalled();

        $converter->convert('input', new Format\Audio(), 'output');
    }

    public function testConvertFull()
    {
        $ffmpeg = $this->prophesize(FFMpeg::class);
        $audio = $this->prophesize(Media\Audio::class);
        $converter = new AudioConverter($ffmpeg->reveal());

        $ffmpeg->open('input')->willReturn($audio->reveal());
        $audio->save(Argument::any(), 'output')->shouldBeCalled();
        $audio->addFilter(Argument::cetera())->shouldBeCalled();

        $spec = new Format\Audio();
        $spec->setAudioFormat('aac')
            ->setAudioBitrate(100)
            ->setAudioChannels(2)
            ->setAudioCodec('libfdk_aac')
            ->setAudioSamplerate(1000);

        $converter->convert('input', $spec, 'output');
    }
}
