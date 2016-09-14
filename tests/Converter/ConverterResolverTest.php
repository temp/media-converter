<?php

/*
 * This file is part of the Media Converter package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MediaConverter\Tests\Converter;

use Prophecy\Argument;
use Temp\MediaConverter\Converter\ConverterInterface;
use Temp\MediaConverter\Converter\ConverterResolver;
use Temp\MediaConverter\Format\Image;

/**
 * Converter resolver test
 *
 * @covers ConverterResolver
 */
class ConverterResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolveReturnsNullOnNonMatchingConverters()
    {
        $converter1 = $this->prophesize(ConverterInterface::class);

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
        $converter1 = $this->prophesize(ConverterInterface::class);

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
        $converter1 = $this->prophesize(ConverterInterface::class);
        $converter2 = $this->prophesize(ConverterInterface::class);

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
