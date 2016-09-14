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
use Temp\MediaConverter\Extractor\RawVideoExtractor;
use Temp\MediaConverter\Format\Audio;
use Temp\MediaConverter\Format\Video;

/**
 * Raw video extractor test
 *
 * @covers RawVideoExtractor
 */
class RawVideoExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportsReturnsTrueForMatchingInput()
    {
        $extractor = new RawVideoExtractor();
        $result = $extractor->supports('input', new MediaType('mp4', 'video'), new Video());

        $this->assertTrue($result);
    }

    public function testSupportsReturnsFalseForUnsupportedSpecification()
    {
        $extractor = new RawVideoExtractor();
        $result = $extractor->supports('input', new MediaType('mp4', 'video'), new Audio());

        $this->assertFalse($result);
    }

    public function testSupportsReturnsFalseForUnsupportedMediaType()
    {
        $extractor = new RawVideoExtractor();
        $result = $extractor->supports('input', new MediaType('mp3', 'audio'), new Video());

        $this->assertFalse($result);
    }

    public function testExtractReturnsInput()
    {
        $extractor = new RawVideoExtractor();
        $result = $extractor->extract('input', new Video());

        $this->assertSame('input', $result);
    }
}
