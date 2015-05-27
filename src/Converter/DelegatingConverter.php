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
 * Audio converter
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class DelegatingConverter implements ConverterInterface
{
    /**
     * @var ConverterResolver
     */
    private $resolver;

    /**
     * @param ConverterResolver $resolver
     */
    public function __construct(ConverterResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(Specification $spec)
    {
        return true;
    }

    /**
     * @param string        $inFilename
     * @param Specification $spec
     * @param string        $outFilename
     *
     * @return string
     * @throws \Exception
     */
    public function convert($inFilename, Specification $spec, $outFilename)
    {
        $converter = $this->resolver->resolve($spec);

        if (!$converter) {
            throw new \Exception("No converter found for specification " . get_class($spec));
        }

        return $converter->convert($inFilename, $spec, $outFilename);
    }
}
