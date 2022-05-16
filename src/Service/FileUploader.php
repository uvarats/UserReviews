<?php

namespace App\Service;

use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private CloudService $cloud;
    private SluggerInterface $slugger;

    /**
     * @param CloudService $cloud
     * @param SluggerInterface $slugger
     */
    public function __construct(CloudService $cloud, SluggerInterface $slugger)
    {
        $this->cloud = $cloud;
        $this->slugger = $slugger;
    }
    public function upload(UploadedFile $file): ?string
    {
        $filesystem = $this->cloud->getFilesystem();

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid();
        try {
            $filesystem->write('users/' . $fileName, file_get_contents($file->getPathname()));
            $iterator = $filesystem->listContents("")->getIterator();
            /** @var FileAttributes $file */
            foreach($iterator as $file){
                if($file->path() === 'users/' . $fileName){
                    return $file->extraMetadata()["secure_url"];
                }
            }
            return $fileName;
        } catch (FilesystemException $e) {

        }
        return null;
    }
}