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
use Temp\MediaConverter\Converter\VideoConverter;
use Temp\MediaConverter\Format\Audio;
use Temp\MediaConverter\Format\Image;
use Temp\MediaConverter\Format\Video;

/**
 * Video converter test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class VideoConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testAccept()
    {
        $ffmpeg = $this->prophesize('FFMpeg\FFMpeg');
        $mp4box = $this->prophesize('MP4Box\MP4Box');
        $converter = new VideoConverter($ffmpeg->reveal(), $mp4box->reveal());

        $this->assertTrue($converter->accept(new Video()));
        $this->assertFalse($converter->accept(new Image()));
        $this->assertFalse($converter->accept(new Audio()));
    }

    public function testConvert()
    {
        $ffmpeg = $this->prophesize('FFMpeg\FFMpeg');
        $mp4box = $this->prophesize('MP4Box\MP4Box');
        $video = $this->prophesize('FFMpeg\Media\Video');
        $converter = new VideoConverter($ffmpeg->reveal(), $mp4box->reveal());

        $ffmpeg->open('input')->willReturn($video->reveal());
        $video->save(Argument::any(), 'output')->shouldBeCalled();
        $video->addFilter(Argument::cetera())->shouldBeCalled();

        $converter->convert('input', new Video(), 'output');
    }

    public function testConvertFull()
    {
        $ffmpeg = $this->prophesize('FFMpeg\FFMpeg');
        $mp4box = $this->prophesize('MP4Box\MP4Box');
        $video = $this->prophesize('FFMpeg\Media\Audio');
        $converter = new VideoConverter($ffmpeg->reveal(), $mp4box->reveal());

        $ffmpeg->open('input')->willReturn($video->reveal());
        $video->save(Argument::any(), 'output')->shouldBeCalled();
        $video->addFilter(Argument::cetera())->shouldBeCalled();

        $spec = new Video();
        $spec
            ->setHeight(100)
            ->setWidth(100)
            ->setResizeMode('xxx')
            ->setVideoFramerate(1000)
            ->setVideoBitrate(1000)
            ->setVideoGop(25)
            ->setVideoCodec('libx264')
            ->setVideoFormat('mp4')
            ->setAudioFormat('aac')
            ->setAudioBitrate(100)
            ->setAudioChannels(2)
            ->setAudioCodec('libfdk_aac')
            ->setAudioSamplerate(1000);

        $converter->convert('input', $spec, 'output');
    }
}
