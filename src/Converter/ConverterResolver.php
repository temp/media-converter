<?php

/*
 * This file is part of the Media Converter package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MediaConverter\Converter;

use Temp\MediaConverter\Format\Specification;

/**
 * Video converter
 */
class ConverterResolver
{
    /**
     * @var ConverterInterface[]
     */
    private $converters;

    /**
     * @param ConverterInterface[] $converters
     */
    public function __construct(array $converters)
    {
        foreach ($converters as $converter) {
            $this->addConverter($converter);
        }
    }

    /**
     * @param ConverterInterface $converter
     *
     * @return $this
     */
    private function addConverter(ConverterInterface $converter)
    {
        $this->converters[] = $converter;

        return $this;
    }

    /**
     * @param Specification $spec
     *
     * @return ConverterInterface
     */
    public function resolve(Specification $spec)
    {
        foreach ($this->converters as $converter) {
            if ($converter->accept($spec)) {
                return $converter;
            }
        }

        return null;
    }
}
