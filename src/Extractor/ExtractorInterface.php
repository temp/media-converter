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

/**
 * Extractor interface
 */
interface ExtractorInterface
{
    /**
     * Check if extractor supports the given asset
     *
     * @param string          $filename
     * @param MediaType       $mediaType
     * @param Specification $targetFormat
     *
     * @return bool
     */
    public function supports($filename, MediaType $mediaType, Specification $targetFormat);

    /**
     * Extract from file
     *
     * @param string          $filename
     * @param Specification $targetFormat
     *
     * @return string
     */
    public function extract($filename, Specification $targetFormat);
}
