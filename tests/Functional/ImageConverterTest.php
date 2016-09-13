<?php

/*
 * This file is part of the Media Converter package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MediaConverter\Tests\Functional;

use Imagine\Imagick\Imagine;
use Prophecy\Argument;
use Temp\MediaConverter\Converter\ImageConverter;
use Temp\MediaConverter\Format\Audio;
use Temp\MediaConverter\Format\Image;
use Temp\MediaConverter\Format\Video;

/**
 * Image converter test
 *
 * @author Stephan Wentz <stephan@wentz.it>
 *
 * @group functional
 */
class ImageConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testAccept()
    {
        $imagine = new Imagine();
        $converter = new ImageConverter($imagine);

        $this->assertTrue($converter->accept(new Image()));
        $this->assertFalse($converter->accept(new Video()));
        $this->assertFalse($converter->accept(new Audio()));
    }

    public function testConvertSimple()
    {
        $image = $this->createImage();
        $resultFile = $this->convertImage(__DIR__ . '/../fixture/test_100x100.gif', $image, __METHOD__, 'gif');
        $this->assertImage($resultFile, 100, 100, IMAGETYPE_GIF);
    }

    public function testConvertToJpg()
    {
        $image = $this->createImage('jpg');
        $resultFile = $this->convertImage(__DIR__ . '/../fixture/test_100x100.gif', $image, __METHOD__, 'jpg');
        $this->assertImage($resultFile, 100, 100, IMAGETYPE_JPEG);
    }

    public function testConvertResizeMethodWidthWithLargerImage()
    {
        $image = $this->createImage('jpg', Image::RESIZE_METHOD_WIDTH, 50);
        $resultFile = $this->convertImage(__DIR__ . '/../fixture/test_100x100.gif', $image, __METHOD__, 'jpg');
        $this->assertImage($resultFile, 50, 50, IMAGETYPE_JPEG);
    }

    public function testConvertResizeMethodWidthWithSmallerImage()
    {
        $image = $this->createImage('jpg', Image::RESIZE_METHOD_WIDTH, 200);
        $resultFile = $this->convertImage(__DIR__ . '/../fixture/test_100x100.gif', $image, __METHOD__, 'jpg');
        $this->assertImage($resultFile, 200, 200, IMAGETYPE_JPEG);
    }

    public function testConvertResizeMethodHeightWithLargerImage()
    {
        $image = $this->createImage('jpg', Image::RESIZE_METHOD_HEIGHT, null, 50);
        $resultFile = $this->convertImage(__DIR__ . '/../fixture/test_100x100.gif', $image, __METHOD__, 'jpg');
        $this->assertImage($resultFile, 50, 50, IMAGETYPE_JPEG);
    }

    public function testConvertResizeMethodHeightWithSmallerImage()
    {
        $image = $this->createImage('jpg', Image::RESIZE_METHOD_HEIGHT, null, 200);
        $resultFile = $this->convertImage(__DIR__ . '/../fixture/test_100x100.gif', $image, __METHOD__, 'jpg');
        $this->assertImage($resultFile, 200, 200, IMAGETYPE_JPEG);
    }

    public function testConvertResizeMethodFitWithLargerImage()
    {
        $image = $this->createImage('jpg', Image::RESIZE_METHOD_FIT, 20, 80);
        $resultFile = $this->convertImage(__DIR__ . '/../fixture/test_100x100.gif', $image, __METHOD__, 'jpg');
        $this->assertImage($resultFile, 20, 20, IMAGETYPE_JPEG);
    }

    public function testConvertResizeMethodFitWithSmallerImage()
    {
        $image = $this->createImage('jpg', Image::RESIZE_METHOD_FIT, 200, 300);
        $resultFile = $this->convertImage(__DIR__ . '/../fixture/test_100x100.gif', $image, __METHOD__, 'jpg');
        $this->assertImage($resultFile, 100, 100, IMAGETYPE_JPEG);
    }

    public function testConvertResizeMethodExactWithLargerImage()
    {
        $image = $this->createImage('jpg', Image::RESIZE_METHOD_EXACT, 20, 80);
        $resultFile = $this->convertImage(__DIR__ . '/../fixture/test_100x100.gif', $image, __METHOD__, 'jpg');
        $this->assertImage($resultFile, 20, 80, IMAGETYPE_JPEG);
    }

    public function testConvertResizeMethodExactWithSmallerImage()
    {
        $image = $this->createImage('jpg', Image::RESIZE_METHOD_EXACT, 200, 300);
        $resultFile = $this->convertImage(__DIR__ . '/../fixture/test_100x100.gif', $image, __METHOD__, 'jpg');
        $this->assertImage($resultFile, 200, 300, IMAGETYPE_JPEG);
    }

    public function testConvertResizeMethodExactFitWithLargerImage()
    {
        $image = $this->createImage('jpg', Image::RESIZE_METHOD_EXACT_FIT, 60, 70);
        $resultFile = $this->convertImage(__DIR__ . '/../fixture/test_100x100.gif', $image, __METHOD__, 'jpg');
        $this->assertImage($resultFile, 60, 70, IMAGETYPE_JPEG);
    }

    public function testConvertResizeMethodExactFitWithSmallerImage()
    {
        $image = $this->createImage('jpg', Image::RESIZE_METHOD_EXACT_FIT, 110, 120);
        $resultFile = $this->convertImage(__DIR__ . '/../fixture/test_100x100.gif', $image, __METHOD__, 'jpg');
        $this->assertImage($resultFile, 110, 120, IMAGETYPE_JPEG);
    }

    public function testConvertResizeMethodCropWithLargerImage()
    {
        $image = $this->createImage('jpg', Image::RESIZE_METHOD_CROP, 60, 70);
        $resultFile = $this->convertImage(__DIR__ . '/../fixture/test_100x100.gif', $image, __METHOD__, 'jpg');
        $this->assertImage($resultFile, 60, 70, IMAGETYPE_JPEG);
    }

    public function testConvertResizeMethodCropWithSmallerImage()
    {
        $image = $this->createImage('jpg', Image::RESIZE_METHOD_CROP, 110, 120);
        $resultFile = $this->convertImage(__DIR__ . '/../fixture/test_100x100.gif', $image, __METHOD__, 'jpg');
        $this->assertImage($resultFile, 100, 100, IMAGETYPE_JPEG);
    }

    /**
     * @param string $format
     * @param string $resizeMethod
     * @param int    $width
     * @param int    $height
     *
     * @return Image
     */
    private function createImage($format = null, $resizeMethod = null, $width = null, $height = null)
    {
        $image = new Image();

        if ($format) {
            $image->setFormat($format);
        }
        if ($resizeMethod) {
            $image->setResizeMode($resizeMethod);
        }
        if ($width) {
            $image->setWidth($width);
        }
        if ($height) {
            $image->setHeight($height);
        }

        return $image;
    }

    /**
     * @param string $file
     * @param Image  $image
     * @param string $method
     * @param string $ext
     *
     * @return string
     */
    private function convertImage($file, Image $image, $method, $ext)
    {
        $imagine = new Imagine();
        $converter = new ImageConverter($imagine);

        $resultFile = sys_get_temp_dir() . '/' . str_replace('testConvert', '', explode('::', $method)[1]) . '.' . $ext;

        $converter->convert($file, $image, $resultFile);

        $this->assertTrue(file_exists($resultFile));

        return $resultFile;
    }

    /**
     * @param string $resultFile
     * @param int    $width
     * @param int    $height
     * @param string $type
     */
    private function assertImage($resultFile, $width, $height, $type)
    {
        $info = getimagesize($resultFile);
        $this->assertSame($width, $info[0]);
        $this->assertSame($height, $info[1]);
        $this->assertSame($type, $info[2]);

        if (file_exists($resultFile)) {
            unlink($resultFile);
        }
    }
}
