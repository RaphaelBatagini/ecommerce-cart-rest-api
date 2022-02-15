<?php

namespace App\Helpers;

use App\Exceptions\JsonException;

class JsonConsumer
{
    private $fileData;

    public function __construct($filePath)
    {
        if (!$this->fileExists($filePath)) {
            throw new JsonException("Arquivo não encontrado");
        }
        $this->fileData = json_decode(file_get_contents($filePath));
    }

    public function getFileData()
    {
        return $this->fileData;
    }

    private function fileExists($filePath)
    {
        return file_exists($filePath)
            && filesize($filePath);
    }
}
