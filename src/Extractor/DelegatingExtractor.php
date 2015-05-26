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

use Temp\MediaClassifier\MediaClassifier;
use Temp\MediaClassifier\Model\MediaType;
use Temp\MediaConverter\Format\Specification;

/**
 * Delegating extractor
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class DelegatingExtractor implements ExtractorInterface
{
    /**
     * @var ExtractorResolverInterface
     */
    private $resolver;

    /**
     * @var MediaClassifier
     */
    private $mediaClassifier;

    /**
     * @param ExtractorResolverInterface $resolver
     * @param MediaClassifier            $mediaClassifier
     */
    public function __construct(ExtractorResolverInterface $resolver, MediaClassifier $mediaClassifier)
    {
        $this->resolver = $resolver;
        $this->mediaClassifier = $mediaClassifier;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($filename, MediaType $mediaType, Specification $targetFormat)
    {
        return null !== $this->resolver->resolve($filename, $mediaType, $targetFormat);
    }

    /**
     * {@inheritdoc}
     */
    public function extract($filename, Specification $targetFormat)
    {
        $mediaType = $this->mediaClassifier->classify($filename);

        $extractor = $this->resolver->resolve($filename, $mediaType, $targetFormat);

        if (!$extractor) {
            return null;
        }

        return $extractor->extract($filename, $targetFormat);
    }
}
