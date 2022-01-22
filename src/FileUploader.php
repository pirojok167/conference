<?php

namespace App;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploader
{
    public function __construct(private string $photoDir) {}

    /**
     * @throws \Exception
     */
    public function getFilename($photo): string
    {
        return sprintf('%s.%s', bin2hex(random_bytes(6)), $photo->guessExtension());
    }

    public function moveFile($photo, string $filename): void
    {
        try {
            $photo->move($this->photoDir, $filename);
        } catch (FileException $e) {
            // unable to upload the photo, give up
        }
    }
}