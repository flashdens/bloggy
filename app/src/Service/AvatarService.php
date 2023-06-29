<?php
/**
 * Avatar service.
 */

namespace App\Service;

use App\Entity\Avatar;
use App\Entity\User;
use App\Repository\AvatarRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Avatar service.
 */
class AvatarService implements AvatarServiceInterface
{
    private string $targetDirectory;
    /**
     * Avatar repository.
     */
    private AvatarRepository $avatarRepository;

    /**
     * File upload service.
     */
    private FileUploadServiceInterface $fileUploadService;


    private Filesystem $filesystem;

    /**
     * Constructor.
     *
     * @param AvatarRepository           $avatarRepository  Avatar repository
     * @param FileUploadServiceInterface $fileUploadService File upload service
     */
    public function __construct(string $targetDirectory, AvatarRepository $avatarRepository, FileUploadServiceInterface $fileUploadService, Filesystem $filesystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->avatarRepository = $avatarRepository;
        $this->fileUploadService = $fileUploadService;
        $this->filesystem = $filesystem;
    }

    /**
     * Create avatar.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param Avatar $avatar Avatar entity
     * @param User $user User interface
     */
    public function create(UploadedFile $uploadedFile, Avatar $avatar, UserInterface $user): void
    {
        $avatarFilename = $this->fileUploadService->upload($uploadedFile);

        $avatar->setUser($user);
        $avatar->setFilename($avatarFilename);
        $this->avatarRepository->save($avatar);
    }

    public function update(UploadedFile $uploadedFile, Avatar $avatar, UserInterface $user): void
    {
        $filename = $avatar->getFilename();
        if (null !== $filename) {
            $this->filesystem->remove(
                $this->targetDirectory.'/'.$filename
            );

            $this->create($uploadedFile, $avatar, $user);
        }
    }

    public function delete(Avatar $avatar, UserInterface $user): void
    {
        $filename = $avatar->getFilename();

        if (null !== $filename) {
            /** @var User $user */
            $this->avatarRepository->remove($avatar);
            $this->filesystem->remove(
                $this->targetDirectory.'/'.$filename
            );
        }
    }
}
