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

use Temp\MediaClassifier\Model\MediaType;
use Temp\MediaConverter\Extractor\RawImageExtractor;
use Temp\MediaConverter\Format\Image;
use Temp\MediaConverter\Format\Video;

/**
 * Raw image extractor
 *
 * @covers RawImageExtractor
 */
class RawImageExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportsReturnsTrueForMatchingInput()
    {
        $extractor = new RawImageExtractor();
        $result = $extractor->supports('input', new MediaType('jpg', 'image'), new Image());

        $this->assertTrue($result);
    }

    public function testSupportsReturnsFalseForUnsupportedSpecification()
    {
        $extractor = new RawImageExtractor();
        $result = $extractor->supports('input', new MediaType('jpg', 'image'), new Video());

        $this->assertFalse($result);
    }

    public function testSupportsReturnsFalseForUnsupportedMediaType()
    {
        $extractor = new RawImageExtractor();
        $result = $extractor->supports('input', new MediaType('mp4', 'video'), new Image());

        $this->assertFalse($result);
    }

    public function testExtractReturnsInput()
    {
        $extractor = new RawImageExtractor();
        $result = $extractor->extract('input', new Image());

        $this->assertSame('input', $result);
    }
}
