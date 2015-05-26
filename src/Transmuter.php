<?php

/*
 * This file is part of the Media Converter package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MediaConverter;

use Temp\MediaConverter\Converter\ConverterInterface;
use Temp\MediaConverter\Extractor\ExtractorInterface;
use Temp\MediaConverter\Format\Audio;
use Temp\MediaConverter\Format\Specification;
use Temp\MediaConverter\Format\Image;
use Temp\MediaConverter\Format\Video;

/**
 * Transmuter
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class Transmuter
{
    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * @var ConverterInterface
     */
    private $converter;

    /**
     * @param ExtractorInterface $extractor
     */
    public function __construct(ExtractorInterface $extractor, ConverterInterface $converter)
    {
        $this->extractor = $extractor;
        $this->converter = $converter;
    }

    /**
     * Transmute file to target format
     *
     * @param string        $inFilename
     * @param Specification $targetFormat
     * @param string        $outFilename
     *
     * @return string
     */
    public function transmute($inFilename, Specification $targetFormat, $outFilename)
    {
        $extractedFile = $this->extractor->extract($inFilename, $targetFormat);

        return $this->converter->convert($extractedFile, $targetFormat, $outFilename);
    }

    /**
     * Transmute file to image
     *
     * @param string $inFilename
     * @param string $outFilename
     *
     * @return string
     */
    public function transmuteToImage($inFilename, $outFilename)
    {
        return $this->transmute($inFilename, new Image, $outFilename);
    }

    /**
     * Transmute file to audio
     *
     * @param string $inFilename
     * @param string $outFilename
     *
     * @return string
     */
    public function transmuteToAudio($inFilename, $outFilename)
    {
        return $this->transmute($inFilename, new Audio(), $outFilename);
    }

    /**
     * Transmute file to video
     *
     * @param string $inFilename
     * @param string $outFilename
     *
     * @return string
     */
    public function transmuteToVideo($inFilename, $outFilename)
    {
        return $this->transmute($inFilename, new Video(), $outFilename);
    }
}
