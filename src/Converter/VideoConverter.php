<?php

/*
 * This file is part of the Media Converter package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MediaConverter\Converter;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\FrameRate;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Filters\Audio\AudioResamplableFilter;
use FFMpeg\Filters\Video\FrameRateFilter;
use FFMpeg\Filters\Video\ResizeFilter;
use FFMpeg\Filters\Video\SynchronizeFilter;
use FFMpeg\Format\Video\DefaultVideo;
use FFMpeg\Format\Video\Ogg;
use FFMpeg\Format\Video\ThreeGP;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\WMV;
use FFMpeg\Format\Video\WMV3;
use FFMpeg\Format\Video\X264;
use Psr\Log\LoggerInterface;
use Temp\MediaConverter\Ffmpeg\Format\Video\Flv;
use Temp\MediaConverter\Format\Specification;
use Temp\MediaConverter\Format\Video;

/**
 * Video converter
 *
 * @author Stephan Wentz <sw@brainbits.net>
 */
class VideoConverter implements ConverterInterface
{
    /**
     * @var FFMpeg
     */
    private $ffmpeg;

    /**
     * @param FFMpeg $converter
     */
    public function __construct(FFMpeg $converter)
    {
        $this->ffmpeg = $converter;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(Specification $spec)
    {
        return $spec instanceof Video;
    }

    /**
     * @param string $videoFormat
     *
     * @return DefaultVideo
     */
    private function createFormat($videoFormat)
    {
        switch ($videoFormat) {
            case 'flv';
                $format = new Flv();
                break;

            case 'ogg';
                $format = new Ogg();
                break;

            case 'webm';
                $format = new WebM();
                break;

            case 'wmv';
                $format = new WMV();
                break;

            case 'wmv3';
                $format = new WMV3();
                break;

            case '3gp';
                $format = new ThreeGP();
                break;

            case 'mp4';
            case 'x264';
            default:
                $format = new X264();
                break;
        }

        return $format;
    }

    /**
     * @param string $inFilename
     * @param Video  $spec
     * @param string $outFilename
     */
    public function convert($inFilename, Specification $spec, $outFilename)
    {
        $video = $this->ffmpeg->open($inFilename);

        $format = $this->createFormat($spec->getVideoFormat());

        $resizeMode = ResizeFilter::RESIZEMODE_FIT;
        if ($spec->getResizeMode()) {
            $resizeMode = $spec->getResizeMode();
        }

        $video->addFilter(new SynchronizeFilter());
        //if ($source->getWidth() > $spec->getWidth() || $source->getHeight() > $spec->getHeight()) {
        if ($spec->getWidth() && $spec->getHeight()) {
            $video->addFilter(
                new ResizeFilter(
                    new Dimension($spec->getWidth(), $spec->getHeight()),
                    $resizeMode
                )
            );
        }

        if ($spec->getVideoCodec()) {
            $format->setVideoCodec($spec->getVideoCodec());
        }

        if ($spec->getAudioCodec()) {
            $format->setAudioCodec($spec->getAudioCodec());
        }

        if ($spec->getVideoBitrate()) {
            $format->setKiloBitrate($spec->getVideoBitrate());
        }

        if ($spec->getVideoFramerate() && $spec->getVideoGop()) {
            $video->addFilter(new FrameRateFilter(new FrameRate($spec->getVideoFramerate()), $spec->getVideoGop()));
        }

        if ($spec->getAudioBitrate()) {
            $format->setAudioKiloBitrate($spec->getAudioBitrate());
        }

        if ($spec->getAudioSamplerate()) {
            $video->addFilter(new AudioResamplableFilter($spec->getAudioSamplerate()));
        }

        if ($spec->getAudioChannels()) {
            $format->setAudioChannels($spec->getAudioChannels());
        }

        $video->save($format, $outFilename);
    }
}
