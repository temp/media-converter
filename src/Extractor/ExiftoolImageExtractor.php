<?php

/*
 * This file is part of the Media Converter package.
 *
 * (c) Stephan Wentz <stephan@wentz.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Temp\MediaConverter\Extractor;

use Temp\MediaClassifier\Model\MediaType;
use PHPExiftool\Driver\Metadata\Metadata;
use PHPExiftool\Driver\Value\ValueInterface;
use PHPExiftool\Reader;
use Symfony\Component\Filesystem\Filesystem;
use Temp\MediaConverter\Format\Specification;
use Temp\MediaConverter\Format\Image;

/**
 * Exiftool image extractor
 */
class ExiftoolImageExtractor implements ExtractorInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var string
     */
    private $tempDir;

    /**
     * @param Reader $reader
     * @param string $tempDir
     */
    public function __construct(Reader $reader, $tempDir)
    {
        $this->reader = $reader;
        $this->tempDir = $tempDir;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($filename, MediaType $mediaType, Specification $targetFormat)
    {
        return $targetFormat instanceof Image && $mediaType->getCategory() === 'audio';
    }

    /**
     * {@inheritdoc}
     */
    public function extract($filename, Specification $targetFormat)
    {
        if (!file_exists($filename)) {
            return null;
        }

        $metadatas = $this->reader->files($filename)->first();

        $imageFilename = $this->tempDir . '/' . uniqid() . '.jpg';

        foreach ($metadatas->getMetadatas() as $metadata) {
            if ($metadata->getTag()->getName() !== 'Picture' &&
                    ValueInterface::TYPE_BINARY !== $metadata->getValue()->getType()) {
                continue;
            }

            $filesystem = new Filesystem();
            if (!$filesystem->exists($this->tempDir)) {
                $filesystem->mkdir($this->tempDir);
            }

            $content = (string) $metadata->getValue()->asString();

            $filesystem->dumpFile($imageFilename, $content);

            return $imageFilename;
        }

        return null;
    }
}
