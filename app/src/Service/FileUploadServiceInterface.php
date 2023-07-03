<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface FileUploadServiceInterface.
 */
interface FileUploadServiceInterface
{
    /**
     * Upload a file.
     *
     * @param UploadedFile|null $file File to upload
     *
     * @return string Filename of the uploaded file
     */
    public function upload(?UploadedFile $file): string;

    /**
     * Get the target directory.
     *
     * @return string Target directory
     */
    public function getTargetDirectory(): string;
}
