<?php

namespace DEMV\File;

use Mimey\MimeMappingBuilder;
use Mimey\MimeTypes;

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
