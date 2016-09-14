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
 * Converter interface
 */
interface ConverterInterface
{
    /**
     * @param Specification $spec
     *
     * @return bool
     */
    public function accept(Specification $spec);

    /**
     * @param string        $inFilename
     * @param Specification $spec
     * @param string        $outFilename
     */
    public function convert($inFilename, Specification $spec, $outFilename);
}
