<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 15/02/2017
 * Time: 12:43
 */
namespace ModelBundle\EventListener;


use ModelBundle\Entity\User;
use ModelBundle\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BrochureUploadListener
{
    private $uploader;
 

    /**
     * BrochureUploadListener constructor.
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;

    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        $this->uploadFile($entity);
        $em->flush();
        

    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {

    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof User) {
            try {
                $oldPicture = $args->getOldValue('photo');
                $newPicture = $args->getNewValue('photo');


                if ($newPicture == null) {
                    $entity->setPhoto($oldPicture);

                } else {
                    /*if(!strpos($oldPicture,'images/') && $oldPicture!=null && file_exists ($this->img_path.'/p/'.$oldPicture))
                        unlink($this->img_path.'/p/'.$oldPicture);*/
                    $this->uploadFile($entity);
                }
            } catch (\Exception $ex) {

            }

        }
       

    }


    /**
     * @param $entity
     */
    private function uploadFile($entity)
    {
        // upload only works for Article entities
        if ($entity instanceof User) {
            $file = $entity->getPhoto();

            // only upload new files
            if (!$file instanceof UploadedFile) {
                return;
            }
            //$path_s =$request->getSchemeAndHttpHost().$request->getBasePath();
            $fileName = $this->uploader->upload($file, 'images', $entity->getId() . 'user');
            $entity->setPhoto($fileName);
        }
       


    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $fileName = $entity->getPhoto();

        $entity->setPhoto(new File($this->targetPath . '/' . $fileName));
    }

}