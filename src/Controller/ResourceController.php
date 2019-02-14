<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Resource;
use App\Repository\ResourceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\Id;




class ResourceController extends AbstractController {
    
    private $repository;
    private $em;
    
    public function __construct(ResourceRepository $repository, ObjectManager $em)
    {
        // On initialise la variable $repository
        $this->repository = $repository;
        
        // On initialise $em
        $this->em = $em;
    }
    
    /**
     * 
     * @return Response
     * @route("/ressources" , name="resource.index", options={"utf8": true})
     */
    
    public function index() : Response
    {
        //$resource = $this->repository->find(3);
        // $resource = $this->repository->findBy(['doc_analyse' => 1, 'doc_oai' => 0]);
        
        return $this->render('resource/index.html.twig', [
            'current_page' => 'resources'
        ]);
    }
    
    /**
     * @route("/ressources/{slug}-{id}" , name="resource.show", requirements={"slug" : "[a-zA-Z1-9\-_\/]+", "id" : "\d+"})
     * @return Response
     * @param Resource resource
     * @param string slug
     * @param int $id
     */
    public function show(Resource $resource, String $slug, Int $id) : Response
    {
        $resource_slug = $resource->getSlug();
        $resource_id = $resource->getId();
        if ($resource_slug !== $slug){
            return $this->redirectToRoute('resource.show', [
                'id' => $resource_id,
                'slug' => $resource_slug
            ], 301);
        }
        dump($resource);
        return $this->render('resource/show.html.twig', [
            'resource' => $resource
        ]);
        
    }
}