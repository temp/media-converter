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

use Temp\MediaClassifier\Model\MediaType;
use Temp\MediaConverter\Format\Specification;
use Temp\MediaConverter\Format\Image;

/**
 * Raw image extractor
 */
class RawImageExtractor implements ExtractorInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($filename, MediaType $mediaType, Specification $targetFormat)
    {
        return $targetFormat instanceof Image &&
            ($mediaType->getCategory() === 'image' || $mediaType->getName() === 'pdf');
    }

    /**
     * {@inheritdoc}
     */
    public function extract($filename, Specification $targetFormat)
    {
        return $filename;
    }
}
