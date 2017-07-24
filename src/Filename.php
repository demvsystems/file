<?php

namespace DEMV\File;

use Mimey\MimeTypes;
use function Dgame\Ensurance\enforce;

/**
 * Class Filename
 * @package DEMV\File
 */
final class Filename
{
    /**
     * @var string
     */
    private $basename;
    /**
     * @var string
     */
    private $extension;

    /**
     * Filename constructor.
     *
     * @param string $filename
     * @param array  $replace
     */
    public function __construct(string $filename, array $replace = [])
    {
        $info = pathinfo($filename);
        if (array_key_exists('extension', $info)) {
            $this->setExtension($info['extension']);
        }

        unset($info['extension']);
        unset($info['basename']);

        if (empty($replace)) {
            $replace = [
                'Ä' => 'Ae',
                'Ö' => 'Oe',
                'Ü' => 'Ue',
                'ä' => 'ae',
                'ö' => 'oe',
                'ü' => 'ue',
                'ß' => 'ss'
            ];
        }

        foreach ($info as $key => $value) {
            $info[$key] = self::clean($info[$key], $replace);
        }

        $info = array_filter($info);
        $info = array_map('strtolower', $info);

        $this->basename = implode('_', $info);
    }

    /**
     * @param int $length
     *
     * @throws \Exception
     */
    public function limitLength(int $length)
    {
        enforce($length > 0)->orThrow('Invalide Länge: %d', $length);

        if (strlen($this->basename) > $length) {
            $this->basename = substr($this->basename, 0, $length);
        }
    }

    /**
     * @param string $filename
     * @param array  $replace
     *
     * @return string
     */
    public static function clean(string $filename, array $replace = []): string
    {
        $filename = trim($filename);
        $filename = trim($filename, '_-');

        if (!empty($replace)) {
            $filename = str_replace(array_keys($replace), array_values($replace), $filename);
        }

        // Leerzeichen durch Unterstrich
        $filename = preg_replace('/\s+/', '_', $filename);
        // Alle Nicht-Alphanummerischen Zeichen, Binde- oder Unterstriche und Klammern zu Unterstrich
        $filename = preg_replace('/[^\w\-_\(\)]+/i', '_', $filename);
        // Mehrere aufeinanderfolgende Unterstriche zu einem Unterstrich
        $filename = preg_replace('/_+/', '_', $filename);
        // Mehrere aufeinanderfolgende Bindestriche zu einem Bindestrich
        $filename = preg_replace('/-+/', '-', $filename);
        // Aufeinanderfolgende Unterstrich/Bindestrich Sequenzen zu einem Bindestrich
        $filename = preg_replace('/(?:_-_|-_-|-_|_-)/', '-', $filename);

        return trim($filename, '_-');
    }

    /**
     * @return null|string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return bool
     */
    public function hasExtension(): bool
    {
        return $this->extension !== null;
    }

    /**
     * @param string $extension
     */
    public function setExtension(string $extension)
    {
        $extension = strtolower($extension);
        $mime      = new MimeTypes();
        if ($mime->getMimeType($extension) !== null) {
            $this->extension = $extension;
        }
    }

    /**
     * @return string
     */
    public function getBasename(): string
    {
        return $this->basename;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return strlen($this->getBasename()) !== 0 && $this->hasExtension();
    }

    /**
     * @return string
     */
    public function assemble(): string
    {
        if ($this->hasExtension()) {
            return sprintf('%s.%s', $this->getBasename(), $this->getExtension());
        }

        return $this->getBasename();
    }
}