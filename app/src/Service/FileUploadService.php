<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * File upload service class.
 */
class FileUploadService implements FileUploadServiceInterface
{
    private string $targetDirectory;
    private SluggerInterface $slugger;

    /**
     * Constructor.
     *
     * @param string           $targetDirectory Target directory
     * @param SluggerInterface $slugger         Slugger interface
     */
    public function __construct(string $targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    /**
     * Upload a file.
     *
     * @param UploadedFile|null $file File to upload
     *
     * @return string Filename of the uploaded file
     */
    public function upload(?UploadedFile $file): string
    {
        $fileName = '';
        $extension = $file->guessExtension();

        if (null !== $extension) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $fileName = $safeFilename.'-'.uniqid().'.'.$extension;
        }

        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    /**
     * Get the target directory.
     *
     * @return string Target directory
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
