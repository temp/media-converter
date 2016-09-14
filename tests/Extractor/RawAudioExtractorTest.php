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
use Temp\MediaConverter\Extractor\RawAudioExtractor;
use Temp\MediaConverter\Format\Audio;
use Temp\MediaConverter\Format\Image;

/**
 * Raw audio extractor test
 *
 * @covers RawAudioExtractor
 */
class RawAudioExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testSupportsReturnsTrueForMatchingInput()
    {
        $extractor = new RawAudioExtractor();
        $result = $extractor->supports('input', new MediaType('mp3', 'audio'), new Audio());

        $this->assertTrue($result);
    }

    public function testSupportsReturnsFalseForUnsupportedSpecification()
    {
        $extractor = new RawAudioExtractor();
        $result = $extractor->supports('input', new MediaType('mp3', 'audio'), new Image());

        $this->assertFalse($result);
    }

    public function testSupportsReturnsFalseForUnsupportedMediaType()
    {
        $extractor = new RawAudioExtractor();
        $result = $extractor->supports('input', new MediaType('jpg', 'image'), new Audio());

        $this->assertFalse($result);
    }

    public function testExtractReturnsInput()
    {
        $extractor = new RawAudioExtractor();
        $result = $extractor->extract('input', new Audio());

        $this->assertSame('input', $result);
    }
}
