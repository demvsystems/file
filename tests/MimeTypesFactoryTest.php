<?php

namespace DEMV\File\Test;

use DEMV\File\MimeTypesFactory;
use PHPUnit\Framework\TestCase;

final class MimeTypesFactoryTest extends TestCase
{
    public function testCustomMimeMappings(): void
    {
        $mimeTypes = MimeTypesFactory::create();

        $this->assertSame('application/gzip', $mimeTypes->getMimeType('gzip'));
        $this->assertSame('application/gzip', $mimeTypes->getMimeType('gz'));
        $this->assertSame('application/vnd.ms-outlook', $mimeTypes->getMimeType('msg'));
        $this->assertSame('application/dat', $mimeTypes->getMimeType('dat'));
    }

    public function testTextPlainPrefersTxtExtension(): void
    {
        $mimeTypes = MimeTypesFactory::create();

        $this->assertSame('txt', $mimeTypes->getExtension('text/plain'));
        $this->assertSame('text/plain', $mimeTypes->getMimeType('env'));
        $this->assertSame('text/plain', $mimeTypes->getMimeType('txt'));
    }
}
