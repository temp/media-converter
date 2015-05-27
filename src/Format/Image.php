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
 * Image specification
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class Image implements Specification
{
    /**
     * @var string
     */
    private $format;

    /**
     * @var int
     */
    private $quality;

    /**
     * @var string
     */
    private $colorspace;

    /**
     * @var string
     */
    private $scale;

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
    private $backgroundColor;

    /**
     * @var int
     */
    private $centerX;

    /**
     * @var int
     */
    private $centerY;

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @param int $quality
     *
     * @return $this
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;

        return $this;
    }

    /**
     * @return string
     */
    public function getColorspace()
    {
        return $this->colorspace;
    }

    /**
     * @param string $colorspace
     *
     * @return $this
     */
    public function setColorspace($colorspace)
    {
        $this->colorspace = $colorspace;

        return $this;
    }

    /**
     * @return string
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * @param string $scale
     *
     * @return $this
     */
    public function setScale($scale)
    {
        $this->scale = $scale;

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
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     *
     * @return $this
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * @return int
     */
    public function getCenterX()
    {
        return $this->centerX;
    }

    /**
     * @param int $centerX
     *
     * @return $this
     */
    public function setCenterX($centerX)
    {
        $this->centerX = $centerX;

        return $this;
    }

    /**
     * @return int
     */
    public function getCenterY()
    {
        return $this->centerY;
    }

    /**
     * @param int $centerY
     *
     * @return $this
     */
    public function setCenterY($centerY)
    {
        $this->centerY = $centerY;

        return $this;
    }
}
