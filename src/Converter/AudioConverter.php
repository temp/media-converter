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

use FFMpeg\FFMpeg;
use FFMpeg\Filters\Audio\AudioResamplableFilter;
use FFMpeg\Format\Audio\Aac;
use FFMpeg\Format\Audio\DefaultAudio;
use FFMpeg\Format\Audio\Flac;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Format\Audio\Vorbis;
use Temp\MediaConverter\Format\Audio;
use Temp\MediaConverter\Format\Specification;

/**
 * Audio converter
 */
class AudioConverter implements ConverterInterface
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
        return $spec instanceof Audio;
    }

    /**
     * @param string $audioFormat
     *
     * @return DefaultAudio
     */
    private function createFormat($audioFormat)
    {
        switch ($audioFormat) {
            case 'aac':
                $format = new Aac();
                break;

            case 'flac':
                $format = new Flac();
                break;

            case 'vorbis':
                $format = new Vorbis();
                break;

            case 'mp3':
            default:
                $format = new Mp3();
                break;
        }

        return $format;
    }

    /**
     * @param string $inFilename
     * @param Audio  $spec
     * @param string $outFilename
     *
     * @return string
     */
    public function convert($inFilename, Specification $spec, $outFilename)
    {
        $audio = $this->ffmpeg->open($inFilename);

        $format = $this->createFormat($spec->getAudioFormat());

        if ($spec->getAudioCodec()) {
            $format->setAudioCodec($spec->getAudioCodec());
        }

        if ($spec->getAudioBitrate()) {
            $format->setAudioKiloBitrate($spec->getAudioBitrate());
        }

        if ($spec->getAudioSamplerate()) {
            $audio->addFilter(new AudioResamplableFilter($spec->getAudioSamplerate()));
        }

        if ($spec->getAudioChannels()) {
            $format->setAudioChannels($spec->getAudioChannels());
        }

        $audio->save($format, $outFilename);
    }
}
