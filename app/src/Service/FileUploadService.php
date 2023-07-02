<?php
/**
 * File upload service.
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class FileUploadService.
 */
class FileUploadService implements FileUploadServiceInterface
{
    /**
     * Target directory.
     */
    private string $targetDirectory;

    /**
     * Slugger.
     */
    private SluggerInterface $slugger;

    /**
     * Constructor.
     *
     * @param string           $targetDirectory Target directory
     * @param SluggerInterface $slugger         Slugger
     */
    public function __construct(string $targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    /**
     * Upload file.
     *
     * @param UploadedFile|null $file File to upload
     *
     * @return string Filename of uploaded file
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
     * Getter for target directory.
     *
     * @return string Target directory
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
