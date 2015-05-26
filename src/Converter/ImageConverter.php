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
use Psr\Log\LoggerInterface;
use Temp\MediaConverter\Format\Image;

/**
 * Image cache worker
 *
 * @author Stephan Wentz <sw@brainbits.net>
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
     * @param string $inFilename
     * @param Image  $spec
     * @param string $outFilename
     */
    public function convert($inFilename, Image $spec, $outFilename)
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

        /*
        if ($template->hasParameter('tiffcompression', true)) {
            $image->setTiffCompression($template->getParameter('tiffcompression'));
        }
        */

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

        $method = $template->getResizeMode();

        if ($method === 'width') {
            $size = $image->getSize()->widen($template->getParameter('width'));
            $image->resize($size);
        } elseif ($method === 'height') {
            $size = $image->getSize()->heighten($template->getParameter('height'));
            $image->resize($size);
        } elseif ($method === 'exact') {
            $size = new Box($template->getParameter('width'), $template->getParameter('height'));
            $image->resize($size);
        } elseif ($method === 'fit') {
            $size = new Box($template->getParameter('width'), $template->getParameter('height'));
            $image = $image->thumbnail($size, ImageInterface::THUMBNAIL_INSET);
        } elseif ($method === 'exactFit') {
            $size = new Box($template->getParameter('width'), $template->getParameter('height'));
            $layer = $image->thumbnail($size, ImageInterface::THUMBNAIL_INSET);
            $layerSize = $layer->getSize();

            $palette = new RGB();
            if ($template->hasParameter('backgroundcolor', true)) {
                $color = $palette->color($template->getParameter('backgroundcolor'), 100);
            } else {
                $color = $palette->color('#fff', 0);
            }
            $image = $this->imagine->create($size, $color);
            $image->paste($layer, new Point(
                floor(($size->getWidth() - $layerSize->getWidth()) / 2),
                floor(($size->getHeight() - $layerSize->getHeight()) / 2)
            ));
        } elseif ($method === 'crop') {
            $size = new Box($template->getParameter('width'), $template->getParameter('height'));
            $imageSize = $image->getSize();

            if (!$size->contains($imageSize)) {
                $ratios = array(
                    $size->getWidth() / $imageSize->getWidth(),
                    $size->getHeight() / $imageSize->getHeight()
                );
                $ratio = max($ratios);
                if (!$imageSize->contains($size)) {
                    $imageSize = new Box(
                        min($imageSize->getWidth(), $size->getWidth()),
                        min($imageSize->getHeight(), $size->getHeight())
                    );
                } else {
                    $imageSize = $imageSize->scale($ratio);
                    $image->resize($imageSize);
                }

                if ($spec->getCenterX() && $spec->getCenterY()) {
                    // TODO: correct?
                    $point = new Point($spec->getCenterX(), $spec->getCenterY());
                } else {
                    $point = new Point(
                        max(0, round(($imageSize->getWidth() - $size->getWidth()) / 2)),
                        max(0, round(($imageSize->getHeight() - $size->getHeight()) / 2))
                    );
                }

                $image->crop($point, $size);
            }
        }

        $image->save($outFilename, $options);
    }
}
