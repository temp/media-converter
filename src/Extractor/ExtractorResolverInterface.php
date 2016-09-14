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
 * Extractor resolver interface
 */
interface ExtractorResolverInterface
{
    /**
     * Resolve extractor
     *
     * @param string          $filename
     * @param MediaType       $mediaType
     * @param Specification $targetFormat
     *
     * @return ExtractorInterface
     */
    public function resolve($filename, MediaType $mediaType, Specification $targetFormat);
}
