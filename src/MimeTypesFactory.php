<?php

namespace DEMV\File;

use Elephox\Mimey\MimeMappingBuilder;
use Elephox\Mimey\MimeTypes;

/**
 * Class MimeExtensions
 * @package DEMV\File
 */
final class MimeTypesFactory
{
    /**
     * @return MimeTypes
     */
    public static function create(): MimeTypes
    {
        $builder = MimeMappingBuilder::create();
        $builder->add('application/gzip', 'gzip');
        $builder->add('application/gzip', 'gz');
        $builder->add('application/vnd.ms-outlook', 'msg');
        $builder->add('application/dat', 'dat');

        return new MimeTypes($builder->getMapping());
    }
}
