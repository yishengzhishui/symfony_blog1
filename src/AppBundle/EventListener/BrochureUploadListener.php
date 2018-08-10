<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\Blog;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use AppBundle\Service\FileUploader;

class BrochureUploadListener
{
    private $uploader;
    private $brochuresDirectory;
    private $photoDirectory;

    public function __construct(FileUploader $uploader, $brochuresDirectory, $photoDirectory)
    {
        $this->uploader = $uploader;
        $this->brochuresDirectory = $brochuresDirectory;
        $this->photoDirectory = $photoDirectory;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {

        $entity = $args->getEntity();
        dump($entity);

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        // upload only works for Blog entities
        if (!$entity instanceof Blog) {
            return;
        }

        $filePdf = $entity->getBrochure();
        $filePhoto = $entity->getPhoto();


        // only upload new files
        if ($filePdf instanceof UploadedFile) {
            $fileName = $this->uploader->upload($this->brochuresDirectory, $filePdf);
            $entity->setBrochureName($fileName);
        }

        if ($filePhoto instanceof UploadedFile) {
            $fileName = $this->uploader->upload($this->photoDirectory, $filePhoto);
            $entity->setPhotoName($fileName);
        }
    }
}