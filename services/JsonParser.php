<?php

namespace App\Services;

use Exception;

class JsonParser
{
    /**
     * Parse JSON file and return data as array
     *
     * @param string $filename
     * @return array
     * @throws Exception
     */
    public static function parse(string $filename): array
    {
        $jsonData = self::readFile($filename);

        return json_decode($jsonData, true);
    }

    /**
     * Read file by parts
     *
     * @param string $filename
     * @return string
     * @throws Exception
     */
    private static function readFile(string $filename): string
    {
        $fileContents = '';
        if (($handle = fopen($filename, 'r')) !== false) {
            while (!feof($handle)) {
                $fileContents .= fread($handle, 1024);
            }
            fclose($handle); // Закрытие файла
        } else {
            throw new Exception('Can not read file');
        }

        return $fileContents;
    }
}