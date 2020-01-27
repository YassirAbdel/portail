<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Resource;
use App\Repository\ResourceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\Id;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\ResourceSearch;
use App\Form\ResourceSearchType;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Form\ResourceType;
use App\Form\ResourceBasketType;
use App\Entity\Basket;
use App\Form\BasketType;
use App\Form\ResourceSearchType1;
use App\Model\ResourceModel;
use App\Repository\SearchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use phpDocumentor\Reflection\Types\Resource_;




class ResourceController extends AbstractController {
    
    private $repository;
    private $em;
    
    public function __construct(ResourceRepository $repository, ObjectManager $em)
    {
        // On initialise la variable $repository
        $this->repository = $repository;
        
        // On initialise $em
        $this->em = $em;

        #$this->manager = $manager;
    }
    
    /**
     * 
     * @return Response
     * @route("/ressources" , name="resource.index", options={"utf8": true})
     */
    
    public function index(PaginatorInterface $paginator, Request $request) : Response
    {
        // 1. Gestion du traitement et affichage du formulaire
        // 1.1. On crée une nouvelle recherche
        $search = new ResourceSearch();
        // 1.2. on crée le formulaire, en second paraméter entity ResourceSearch
        $form = $this->createForm(ResourceSearchType::class, $search);
        // 1.3. Gestion de la requete : on passe en paramètre la requête
        $form->handleRequest($request);
        
        // 1.4. On passe en paramétre la searchdata $search = ResourceSearch
        $query = $this->repository->findAllVisibleQuery($search);

        $resources = $paginator->paginate(
                        $query,
                        $request->query->getInt('page', 1)/*page number*/,
                        12/*limit per page*/
                      );
        
        return $this->render('resource/index.html.twig', [
            'current_page' => 'resources',
            'resources' => $resources,
            // 1.4. on envoie le formulaire à la vue
            'form' => $form->createView()
            
            
        ]);
    }
    
    /**
     * @route("/ressource/{slug}-{id}" , name="resource.show", requirements={"slug" : "^[a-z0-9]+(?:-[a-z0-9]+)*$", "id" : "\d+"},  methods="GET|POST")
     * @return Response
     * @param Resource resource
     * @param string slug
     * @param int $id
     */
    public function show(Resource $resource, String $slug, Int $id, Request $request, ContactNotification $notification) : Response
    {
        $resource_slug = $resource->getSlug();
        $resource_id = $resource->getId();
        if ($resource_slug !== $slug){
            return $this->redirectToRoute('resource.show', [
                'id' => $resource_id,
                'slug' => $resource_slug
            ],  301);
        }
        
        $contact = new Contact();
        $contact->setResource($resource);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        
        return $this->render('resource/show.html.twig', [
            'resource' => $resource,
            'current_menu' => 'ressource',
            'form' => $form->createView()
            
            
        ]);
    }

/**
 * @Route("/search", name="search_resource")
 */
public function searchResource(Request $request)
{
    // create Contact model
      $ResourceSearch = new ResourceModel();
      // create form
      $ResourceSearchForm = $this->createForm(ResourceSearchType1::class, $ResourceSearch );
        // bind data
      $ResourceSearchForm->handleRequest($request);
      $ResourceSearch = $ResourceSearchForm->getData();
      $results = $this->manager->getRepository(SearchRepository::class)->searchFull($ResourceSearch);
      //searchFull($ResourceSearch);
      

}
}