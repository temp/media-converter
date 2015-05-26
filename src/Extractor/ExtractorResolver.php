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
 * Extractor resolver
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ExtractorResolver implements ExtractorResolverInterface
{
    /**
     * @var ExtractorInterface[]
     */
    private $extractors;

    /**
     * @param ExtractorInterface[] $extractors
     */
    public function __construct(array $extractors = [])
    {
        $this->extractors = $extractors;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($filename, MediaType $mediaType, Specification $targetFormat)
    {
        foreach ($this->extractors as $extractor) {
            if ($extractor->supports($filename, $mediaType, $targetFormat)) {
                return $extractor;
            }
        }

        return null;
    }
}
