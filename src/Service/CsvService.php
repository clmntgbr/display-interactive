<?php

namespace App\Service;

use App\Exception\CsvServiceException;

class CsvService
{
    const CSV_DIRECTORY = "public/csv";
    
    public function open(string $path, string $name)
    {
        $files = scandir($path, SCANDIR_SORT_ASCENDING);

        $files = array_flip($files);

        if (!array_key_exists($name, $files)) {
            throw new CsvServiceException(sprintf('`%s` file is missing in `%s` directory.', $name, $path));
        }

        $handle = fopen(sprintf('%s/%s', $path, $name), "r");

        if (false === $handle) {
            throw new CsvServiceException(sprintf('`%s` file can\'t be open.', $name));
        }

        return $handle;
    }
}