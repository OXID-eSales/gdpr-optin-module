<?php

namespace OxidEsales\GdprOptinModule\Tests\Unit\UserData\Service;

use org\bovigo\vfs\vfsStream;
use OxidEsales\GdprOptinModule\UserData\Exception\ZipCreationException;
use OxidEsales\GdprOptinModule\UserData\Service\ZipArchiveFactory;
use PHPUnit\Framework\TestCase;
use ZipArchive;

class ZipArchiveFactoryTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        $this->createTempDirectory();
    }

    public function testCreateZipArchive(): void
    {
        vfsStream::setup('outputDir');
        $filePath = vfsStream::url('outputDir/test.zip');

        $sut = new ZipArchiveFactory();
        $zipArchive1 = $sut->create(filePath: $filePath);
        $zipArchive2 = $sut->create(filePath: $filePath);

        $this->assertInstanceOf(ZipArchive::class, $zipArchive1);

        $this->assertNotSame($zipArchive1, $zipArchive2);
    }

    public function testCreateZipArchiveThrowsAnException()
    {
        $this->expectException(ZipCreationException::class);
        $this->expectExceptionMessage("Unable to open zip file at {$this->tempDir}");

        $sut = new ZipArchiveFactory();
        $sut->create(filePath: $this->tempDir);
    }

    private function createTempDirectory(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/testDir';

        mkdir($this->tempDir);
        chmod($this->tempDir, 0444);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempDir)) {
            rmdir($this->tempDir);
        }
    }
}
