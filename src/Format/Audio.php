<?php

/*
 * This file is part of the Media Converter package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MediaConverter\Format;

/**
 * Audio specification
 */
class Audio implements Specification
{
    /**
     * @var string
     */
    private $audioFormat;

    /**
     * @var string
     */
    private $audioCodec;

    /**
     * @var int
     */
    private $audioBitrate;

    /**
     * @var int
     */
    private $audioSamplerate;

    /**
     * @var int
     */
    private $audioChannels;

    /**
     * @return string
     */
    public function getAudioFormat()
    {
        return $this->audioFormat;
    }

    /**
     * @param string $audioFormat
     *
     * @return $this
     */
    public function setAudioFormat($audioFormat)
    {
        $this->audioFormat = $audioFormat;

        return $this;
    }

    /**
     * @return string
     */
    public function getAudioCodec()
    {
        return $this->audioCodec;
    }

    /**
     * @param string $audioCodec
     *
     * @return $this
     */
    public function setAudioCodec($audioCodec)
    {
        $this->audioCodec = $audioCodec;

        return $this;
    }

    /**
     * @return int
     */
    public function getAudioBitrate()
    {
        return $this->audioBitrate;
    }

    /**
     * @param int $audioBitrate
     *
     * @return $this
     */
    public function setAudioBitrate($audioBitrate)
    {
        $this->audioBitrate = $audioBitrate;

        return $this;
    }

    /**
     * @return int
     */
    public function getAudioSamplerate()
    {
        return $this->audioSamplerate;
    }

    /**
     * @param int $audioSamplerate
     *
     * @return $this
     */
    public function setAudioSamplerate($audioSamplerate)
    {
        $this->audioSamplerate = $audioSamplerate;

        return $this;
    }

    /**
     * @return int
     */
    public function getAudioChannels()
    {
        return $this->audioChannels;
    }

    /**
     * @param int $audioChannels
     *
     * @return $this
     */
    public function setAudioChannels($audioChannels)
    {
        $this->audioChannels = $audioChannels;

        return $this;
    }
}
