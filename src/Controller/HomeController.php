<?php

/**
 * @file
 * Page d'accueil du front
 */

namespace App\Controller;

use App\Entity\Resource;
use App\Entity\ResourceSearch;
use App\Form\ResourceSearchType;
use App\Model\ResourceModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\ResourceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use App\Form\ResourceSearchType1;
use App\Repository\FullSearchRepository;
use App\Service\ResourceService;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
#use App\SearchRepository\ResourceRepository;
#use App\SearchRepository\SearchRepository;
#use Elastica\Query;
use Elastica\QueryBuilder\DSL\Query as DSLQuery;
use Elastica\Util;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class HomeController  extends AbstractController {
    
    private $repository;
    private $em;
    //private $manager;
    private $resourceService;

    public function __construct(ResourceRepository $repository, ObjectManager $em, ResourceService $resourceService)
    {
        // On initialise la variable $repository
        $this->repository = $repository;
        
        // On initialise $em
        $this->em = $em;

        $this->resourceService = $resourceService;
        // $this->manager = $manager;
        
    }

    /**
     * @Route("/test", name="hometest")
     * @param ResourceRepository $repository
     * @return Response
     */
    public function indextest(PaginatorInterface $paginator, Request $request)
    {
        //$finder = $this->container->get('fos_elastica.finder.app.resource');
        // nouvelle recherche
        $search = new ResourceSearch();
        // nouveau formulaire qu'on lui passe la nouvelle recherche
        $form = $this->createForm(ResourceSearchType::class, $search);
        // gestion de la requette : on fait passer les parametres de la requete
        $form->handleRequest($request);
        //On exexute la requete et on récupère les résultats
        $query = $this->repository->findAllVisibleQuery($search);
        //dump($query);die();
        $resources = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );
        
        return $this->render('pages/results.html.twig',[
            'current_page' => 'home',
            'resources' => $resources,
            'form' => $form->createView()
        ]);
    }

    /**
     * @route("/homeok", name="homeok")
     */

    public function indexok(PaginatorInterface $paginator, Request $request, TransformedFinder  $resourcesFinder)
    {
        $q = $request->get('q', '*');

        $searchQuery = new \Elastica\Query\QueryString();
        $searchQuery->setParam('query', $q);
        $searchQuery->setDefaultOperator('AND');
        
        $resources = !empty($q) ? $resourcesFinder->findHybrid($searchQuery, 12) : [];
        
        return $this->render('pages/results.html.twig',[
           'current_page' => 'home',
           'resources' => $resources,
           //'form' => $form->createView()
       ]);

    }

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, SessionInterface $session, TransformedFinder $resourcesFinder): Response
    {
        $q = (string) $request->query->get('q', '*');
        //$q = $request->get('q', '*');

        //$pagination = $this->findHybridPaginated($resourcesFinder, Util::escapeTerm($q));
        $pagination = $this->findHybridPaginated($resourcesFinder, $q);
        //$pagination = $resourcesFinder->findHybridPaginated(Util::escapeTerm($q));
        $pagination->setCurrentPage($request->query->getInt('page', 1));
        $pagination->setMaxPerPage(12);
        $nbresult = $pagination->getNbResults();
        //$session->set('q', $q);

        //dump($pagination);die();

        //return $this->render('pages/results.html.twig', compact('pagination', 'q'));
        return $this->render('pages/results.html.twig',[
            'current_page' => 'home',
            'pagination' => $pagination,
            'nbresult' => $nbresult
            //'form' => $form->createView()
        ]);
    }
/**
* @route("/searchtest", name="resources.searchtest")
*/

public function searchtest(Request $request, Session $session, TransformedFinder  $resourcesFinder, PaginatorInterface $paginator): Response
    {
        $q = (string) $request->query->get('q', '');
        
        $resources = !empty($q) ? $resourcesFinder->findHybrid($q) : [];
        //dump($resources);
        $session->set('q', $q);

        //return $this->render('search/search_41.html.twig', compact('results', 'q'));
        return $this->render('pages/results.html.twig',[
            'current_page' => 'home',
            'resources' => $resources,
            //'form' => $form->createView()
        ]);
    }


/**
* @route("/fullsearch", name="resources.fullsearch")
*/  

    public function fullsearch(RepositoryManagerInterface $manager, Request $request)
    {
        $query = $request->query->all();
        $search = isset($query['q']) && !empty($query['q']) ? $query['q'] : null;
        
        /** @var SearchRepository $repository */
        $repository = $manager->getRepository(Resource::class);
        
        $resources = $repository->fullsearch($search);
        
        /** @var Resource $resource */
        foreach($resources as $resource) {
            $data[] = [
                'title' => $resource->getTitle(),
                'type' => $resource->gettype(),
                //'person' => $resource->getPerson(),
            ];
        }
        //dump($data);die();
        return new JsonResponse($data);
        

    }


    /**
     * @route("/search", name="resources.search")
     */

    public function search(Request $request, SessionInterface $session, TransformedFinder $resourcesFinder): Response
    {
        //$q = (string) $request->query->get('q', '**');
        $q = $request->get('q', null);
        //$pagination = $this->findHybridPaginated($resourcesFinder, Util::escapeTerm($q));
        $pagination = $this->findHybridPaginated($resourcesFinder, Util::escapeTerm($q));
        //$pagination = $resourcesFinder->findHybridPaginated(Util::escapeTerm($q));
        $pagination->setCurrentPage($request->query->getInt('page', 1));
        $pagination->setMaxPerPage(12);
        $session->set('q', $q);
        $nbresult = $pagination->getNbResults();

        //dump($pagination);die();

        #return $this->render('pages/results.html.twig', compact('pagination', 'q'));
        return $this->render('pages/results.html.twig',[
            'current_page' => 'home',
            'pagination' => $pagination,
            'nbresult' => $nbresult
            //'form' => $form->createView()
        ]);
    }


    /**
     * @route("/searchok", name="resources.searchok")
     */

     public function searchok(Request $request, TransformedFinder  $resourcesFinder)
     {
         $q = $request->get('q', null);
        
         $searchQuery = new \Elastica\Query\QueryString();
         $searchQuery->setParam('query', $q);
         $searchQuery->setDefaultOperator('AND');
         
         $resources = !empty($q) ? $resourcesFinder->findHybrid($searchQuery, 2000) : [];
         
         return $this->render('pages/results.html.twig',[
            'current_page' => 'home',
            'resources' => $resources,
            //'form' => $form->createView()
        ]);

     }

     /**
     * I made a PR to have this function in the bundle.
     *
     * @see https://github.com/FriendsOfSymfony/FOSElasticaBundle/pull/1567/files
     */
    private function findHybridPaginated(TransformedFinder  $resourcesFinder, string $query): Pagerfanta
    {
        $searchQuery = new \Elastica\Query\QueryString();
        $searchQuery->setParam('query', $query);
        $searchQuery->setDefaultOperator('AND');
        $paginatorAdapter = $resourcesFinder->createHybridPaginatorAdapter($searchQuery);

        return new Pagerfanta(new FantaPaginatorAdapter($paginatorAdapter));
       
    }
}


