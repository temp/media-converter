<?php

/*
 * This file is part of the Media Converter package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MediaConverter\Extractor\Tests;

use Prophecy\Argument;
use Temp\MediaClassifier\Model\MediaType;
use Temp\MediaConverter\Extractor\ExtractorResolver;
use Temp\MediaConverter\Format\Image;

/**
 * Extractor resolver test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ExtractorResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveReturnsNullOnNonMatchingExtractors()
    {
        $extractor1 = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorInterface');

        $resolver = new ExtractorResolver(
            array(
                $extractor1->reveal()
            )
        );

        $extractor1->supports('input', Argument::cetera())->willReturn(false);

        $extractor = $resolver->resolve('input', new MediaType('jpg', 'image'), new Image());

        $this->assertNull($extractor);
    }

    public function testResolveReturnsMatchingExtractor()
    {
        $extractor1 = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorInterface');

        $resolver = new ExtractorResolver(
            array(
                $extractor1->reveal()
            )
        );

        $extractor1->supports('input', Argument::cetera())->willReturn(true);

        $extractor = $resolver->resolve('input', new MediaType('jpg', 'image'), new Image());

        $this->assertSame($extractor1->reveal(), $extractor);
    }

    public function testResolveReturnsOnlyMatchingExtractor()
    {
        $extractor1 = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorInterface');
        $extractor2 = $this->prophesize('Temp\MediaConverter\Extractor\ExtractorInterface');

        $resolver = new ExtractorResolver(
            array(
                $extractor1->reveal(),
                $extractor2->reveal()
            )
        );

        $extractor1->supports('input', Argument::cetera())->willReturn(false);
        $extractor2->supports('input', Argument::cetera())->willReturn(true);

        $extractor = $resolver->resolve('input', new MediaType('jpg', 'image'), new Image());

        $this->assertSame($extractor2->reveal(), $extractor);
    }
}
