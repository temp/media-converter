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

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Palette\CMYK;
use Imagine\Image\Palette\Grayscale;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use Temp\MediaConverter\Format\Image;
use Temp\MediaConverter\Format\Specification;

/**
 * Image cache worker
 */
class ImageConverter implements ConverterInterface
{
    /**
     * @var ImagineInterface
     */
    private $imagine;

    /**
     * @param ImagineInterface $imagine
     */
    public function __construct(ImagineInterface $imagine)
    {
        $this->imagine = $imagine;
    }

    /**
     * {@inheritdoc}
     */
    public function accept(Specification $spec)
    {
        return $spec instanceof Image;
    }

    /**
     * @param string $inFilename
     * @param Image  $spec
     * @param string $outFilename
     */
    public function convert($inFilename, Specification $spec, $outFilename)
    {
        $image = $this->imagine->open($inFilename);

        $options = array();

        if ($spec->getFormat()) {
            $options['format'] = $spec->getFormat();
        }

        if ($spec->getQuality()) {
            if (!empty($options['format']) && $options['format'] === 'png') {
                $options['png_compression_level'] = floor($spec->getQuality() / 10);
                $options['png_compression_filter'] = $spec->getQuality() % 10;
            } elseif (!empty($options['format']) && $options['format'] === 'jpg') {
                $options['jpeg_quality'] = $spec->getQuality();
            }
        }

        if ($spec->getColorspace()) {
            $image->strip();

            switch ($spec->getColorspace()) {
                case 'cmyk':
                    $image->usePalette(new CMYK());
                    break;

                case 'grayscale':
                    $image->usePalette(new Grayscale());
                    break;

                default:
                    $image->usePalette(new RGB());
            }
        }

        if ($spec->getScale()) {
            if ($spec->getScale() === 'up') {
                // only scale up
            } elseif ($spec->getScale() === 'down') {
                // only scale down
            }
        }

        $method = $spec->getResizeMode();

        if ($method === Image::RESIZE_METHOD_WIDTH) {
            $specSize = $image->getSize()->widen($spec->getWidth());
            $image->resize($specSize);
        } elseif ($method === Image::RESIZE_METHOD_HEIGHT) {
            $specSize = $image->getSize()->heighten($spec->getHeight());
            $image->resize($specSize);
        } elseif ($method === Image::RESIZE_METHOD_EXACT) {
            $specSize = new Box($spec->getWidth(), $spec->getHeight());
            $image->resize($specSize);
        } elseif ($method === Image::RESIZE_METHOD_FIT) {
            $specSize = new Box($spec->getWidth(), $spec->getHeight());
            $image = $image->thumbnail($specSize, ImageInterface::THUMBNAIL_INSET);
        } elseif ($method === Image::RESIZE_METHOD_EXACT_FIT) {
            $specSize = new Box($spec->getWidth(), $spec->getHeight());
            $layer = $image->thumbnail($specSize, ImageInterface::THUMBNAIL_INSET);
            $layerSize = $layer->getSize();

            $palette = new RGB();
            if ($spec->getBackgroundColor()) {
                $color = $palette->color($spec->getBackgroundColor(), 100);
            } else {
                $color = $palette->color('#fff', 0);
            }
            $image = $this->imagine->create($specSize, $color);
            $image->paste($layer, new Point(
                floor(($specSize->getWidth() - $layerSize->getWidth()) / 2),
                floor(($specSize->getHeight() - $layerSize->getHeight()) / 2)
            ));
        } elseif ($method === Image::RESIZE_METHOD_CROP) {
            $specSize = new Box($spec->getWidth(), $spec->getHeight());
            $imageSize = $image->getSize();

            if (!$specSize->contains($imageSize)) {
                $ratios = array(
                    $specSize->getWidth() / $imageSize->getWidth(),
                    $specSize->getHeight() / $imageSize->getHeight()
                );
                $ratio = max($ratios);
                if (!$imageSize->contains($specSize)) {
                    $imageSize = new Box(
                        min($imageSize->getWidth(), $specSize->getWidth()),
                        min($imageSize->getHeight(), $specSize->getHeight())
                    );
                } else {
                    $imageSize = $imageSize->scale($ratio);
                    $image->resize($imageSize);
                }

                if ($spec->getCenterX() && $spec->getCenterY()) {
                    $point = new Point($spec->getCenterX(), $spec->getCenterY());
                } else {
                    $point = new Point(
                        max(0, round(($imageSize->getWidth() - $specSize->getWidth()) / 2)),
                        max(0, round(($imageSize->getHeight() - $specSize->getHeight()) / 2))
                    );
                }

                $image->crop($point, $specSize);
            }
        }

        $image->save($outFilename, $options);
    }
}
