<?php

namespace Temp\MediaConverter\Tests\Binary;

use Alchemy\BinaryDriver\BinaryDriverTestCase;
use Temp\MediaConverter\Binary\MP4Box;

/**
 * MP4Box test
 */
class MP4BoxTest extends BinaryDriverTestCase
{
    public function testProcessOutputWithCustomOutput()
    {
        $input = __DIR__ . '/../files/Video.mp4';
        $out = 'Output.mp4';

        $mp4box = MP4Box::create();

        $factory = $this->createProcessBuilderFactoryMock();
        $process = $this->createProcessMock(1, true);

        $factory
            ->expects($this->once())
            ->method('create')
            ->with(array(
                '-quiet',
                '-inter',
                '0.5',
                '-tmp',
                dirname($input),
                $input,
                '-out',
                $out,
            ))
            ->will($this->returnValue($process));

        $mp4box->setProcessBuilderFactory($factory);

        $mp4box->process($input, $out);
    }

    /**
     * @expectedException \Temp\MediaConverter\Binary\Exception\InvalidFileArgumentException
     */
    public function testProcessOnNonUnexistingFile()
    {
        $mp4box = MP4Box::create();
        $mp4box->process(__DIR__ . '/../files/Unknown');
    }
}
