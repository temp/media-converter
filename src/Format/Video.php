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
 * Video specification
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class Video extends Audio
{
    /**
     * @var string
     */
    private $videoFormat;

    /**
     * @var string
     */
    private $resizeMode;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var string
     */
    private $videoCodec;

    /**
     * @var int
     */
    private $videoBitrate;

    /**
     * @var int
     */
    private $videoFramerate;

    /**
     * @var int
     */
    private $videoGop;

    /**
     * @return string
     */
    public function getVideoFormat()
    {
        return $this->videoFormat;
    }

    /**
     * @param string $videoFormat
     *
     * @return $this
     */
    public function setVideoFormat($videoFormat)
    {
        $this->videoFormat = $videoFormat;

        return $this;
    }

    /**
     * @return string
     */
    public function getResizeMode()
    {
        return $this->resizeMode;
    }

    /**
     * @param string $resizeMode
     *
     * @return $this
     */
    public function setResizeMode($resizeMode)
    {
        $this->resizeMode = $resizeMode;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     *
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return string
     */
    public function getVideoCodec()
    {
        return $this->videoCodec;
    }

    /**
     * @param string $videoCodec
     *
     * @return $this
     */
    public function setVideoCodec($videoCodec)
    {
        $this->videoCodec = $videoCodec;

        return $this;
    }

    /**
     * @return int
     */
    public function getVideoBitrate()
    {
        return $this->videoBitrate;
    }

    /**
     * @param int $videoBitrate
     *
     * @return $this
     */
    public function setVideoBitrate($videoBitrate)
    {
        $this->videoBitrate = $videoBitrate;

        return $this;
    }

    /**
     * @return int
     */
    public function getVideoFramerate()
    {
        return $this->videoFramerate;
    }

    /**
     * @param int $videoFramerate
     *
     * @return $this
     */
    public function setVideoFramerate($videoFramerate)
    {
        $this->videoFramerate = $videoFramerate;

        return $this;
    }

    /**
     * @return int
     */
    public function getVideoGop()
    {
        return $this->videoGop;
    }

    /**
     * @param int $videoGop
     *
     * @return $this
     */
    public function setVideoGop($videoGop)
    {
        $this->videoGop = $videoGop;

        return $this;
    }
}
