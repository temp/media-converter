<?php

/*
 * This file is part of the Media Converter package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MediaConverter\Converter\Tests;

use Prophecy\Argument;
use Temp\MediaConverter\Converter\ConverterResolver;
use Temp\MediaConverter\Format\Image;

/**
 * Converter resolver test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 */
class ConverterResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveReturnsNullOnNonMatchingConverters()
    {
        $converter1 = $this->prophesize('Temp\MediaConverter\Converter\ConverterInterface');

        $resolver = new ConverterResolver(
            array(
                $converter1->reveal()
            )
        );

        $converter1->accept(Argument::cetera())->willReturn(false);

        $converter = $resolver->resolve(new Image());

        $this->assertNull($converter);
    }

    public function testResolveReturnsAcceptingConverter()
    {
        $converter1 = $this->prophesize('Temp\MediaConverter\Converter\ConverterInterface');

        $resolver = new ConverterResolver(
            array(
                $converter1->reveal()
            )
        );

        $converter1->accept(Argument::cetera())->willReturn(true);

        $converter = $resolver->resolve(new Image());

        $this->assertSame($converter1->reveal(), $converter);
    }

    public function testResolveReturnsOnlyAcceptingConverter()
    {
        $converter1 = $this->prophesize('Temp\MediaConverter\Converter\ConverterInterface');
        $converter2 = $this->prophesize('Temp\MediaConverter\Converter\ConverterInterface');

        $resolver = new ConverterResolver(
            array(
                $converter1->reveal(),
                $converter2->reveal()
            )
        );

        $converter1->accept(Argument::cetera())->willReturn(false);
        $converter2->accept(Argument::cetera())->willReturn(true);

        $extractor = $resolver->resolve(new Image());

        $this->assertSame($converter2->reveal(), $extractor);
    }
}
