<?php

/*
 * This file is part of the Media Converter package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MediaConverter\Extractor;

use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Symfony\Component\Filesystem\Filesystem;
use Temp\MediaClassifier\Model\MediaType;
use Temp\MediaConverter\Format\Specification;
use Temp\MediaConverter\Format\Image;

/**
 * Video converter extractor
 */
class FfmpegImageExtractor implements ExtractorInterface
{
    /**
     * @var FFMpeg
     */
    private $converter;

    /**
     * @var string
     */
    private $tempDir;

    /**
     * @param FFMpeg $converter
     * @param string $tempDir
     */
    public function __construct(FFMpeg $converter, $tempDir)
    {
        $this->converter = $converter;
        $this->tempDir = $tempDir;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($filename, MediaType $mediaType, Specification $targetFormat)
    {
        return $targetFormat instanceof Image && $mediaType->getCategory() === 'video';
    }

    /**
     * {@inheritdoc}
     */
    public function extract($filename, Specification $targetFormat)
    {
        if (!file_exists($filename)) {
            return null;
        }

        $imageFilename = null;

        try {
            $filesystem = new Filesystem();
            if (!$filesystem->exists($this->tempDir)) {
                $filesystem->mkdir($this->tempDir);
            }

            $imageFilename = $this->tempDir . '/' . uniqid() . '.jpg';

            $this->converter
                ->open($filename)
                ->frame(TimeCode::fromSeconds(3))
                ->save($imageFilename);
        } catch (\Exception $e) {
            $imageFilename = null;
        }

        return $imageFilename;
    }
}
