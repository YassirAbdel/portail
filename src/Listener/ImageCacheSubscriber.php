<?php
namespace App\Listener;

use Doctrine\Common\EventSubscriber;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Resource;

class ImageCacheSubscriber implements EventSubscriber
{
    /**
     * @var CacheManager
     */
    private $cacheManager;
    
    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;
    
    public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper) {
        
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
        
    }
    
    public function getSubscribedEvents() {
        return [
            //'preRemove',
            //'preUpdate'
            Events::preRemove,
            Events::preUpdate,
        ];
    }
    
    public function preRemove(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if(!$entity instanceof Resource) {
            return;
        }
        $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
    }
    
    public function preUpdate(PreUpdateEventArgs $args) {
        $entity = $args->getEntity();
        // LA SUPPRESSION DU CACHE NE FONCTIONNE PAS ICI
        /** POUR TESTER 
        dump($args->getEntity());
        dump($args->getObject());
        **/
        
        if(!$entity instanceof Resource) {
            return;
        }
        if ($entity->getImageFile() instanceof UploadedFile){
            $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
        }
    }
}