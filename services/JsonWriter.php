<?php

namespace App\Services;

class JsonWriter
{
    /**
     * Write json file
     *
     * @param string $fileName
     * @param array $data
     * @return void
     */
    public function write(string $fileName, array $data): void
    {
        file_put_contents($fileName, json_encode($data));
    }
}