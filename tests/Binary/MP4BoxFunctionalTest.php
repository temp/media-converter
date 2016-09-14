<?php

namespace Temp\MediaConverter\Tests\Binary;

use Temp\MediaConverter\Binary\MP4Box;

/**
 * MP4Box functional test
 */
class MP4BoxFunctionalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group functional
     */
    public function testProcess()
    {
        $mp4box = MP4Box::create();
        $mp4box->process(__DIR__ . '/../files/Video.mp4');
    }

    /**
     * @group functional
     */
    public function testProcessWithCustomOutput()
    {
        $out = sys_get_temp_dir().'/OutVideo.mp4';

        if (file_exists($out)) {
            unlink($out);
        }

        $mp4box = MP4Box::create();
        $mp4box->process(__DIR__ . '/../files/Video.mp4', $out);
        $this->assertTrue(file_exists($out));
        unlink($out);
    }

    /**
     * @group functional
     * @expectedException \Temp\MediaConverter\Binary\Exception\RuntimeException
     */
    public function testProcessFail()
    {
        $mp4box = MP4Box::create();
        $mp4box->process(__DIR__ . '/../files/WrongFile.mp4');
    }
}
