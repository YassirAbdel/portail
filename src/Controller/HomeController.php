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
use Elastica\Query;
use Elastica\Query\BoolQuery as BoolQuery;
use Elastica\QueryBuilder\DSL\Query as DSLQuery;
use Elastica\Util;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Elastica\Aggregation\Terms;
use Elastica\Client;
use Elastica\Query\Terms as TermsSearch;
use App\Repository\CategoryRepository;
use Elastica\Query\Wildcard;

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
    public function index(Request $request, Session $session, TransformedFinder $resourcesFinder, PaginatorInterface $paginator, CategoryRepository $categoryRepository): Response
    {
        $q = (string) $request->query->get('q', '*');
        //$q = $request->get('q', '*');
        $query = $this->repository->findLast();
        $lastresources = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
          );
        $collections = $categoryRepository->findAll();
        //$pagination = $this->findHybridPaginated($resourcesFinder, Util::escapeTerm($q));
        $pagination = $this->findHybridPaginated($resourcesFinder, $q);
        //$pagination = $resourcesFinder->findHybridPaginated(Util::escapeTerm($q));
        $pagination->setCurrentPage($request->query->getInt('page', 1));
        $pagination->setMaxPerPage(12);
        $nbresult = $pagination->getNbResults();
        $session->set('nbresultall', $nbresult);
        $front = $this->repository->findFront();
        $foldersFront = $this->repository->findFolderFront();
        
        //dump($foldersFront);
        //$session->set('q', $q);
        //dump($pagination);die();

        return $this->render('pages/home.html.twig',[
            'current_page' => 'home',
            'pagination' => $lastresources,
            'nbresultall' => $nbresult,
            'front' => $front,
            'collections' => $collections,
            'foldersfront' => $foldersFront
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
        foreach($resources as $ressource) {
            $data[] = [
                'title' => $ressource->getTitle(),
                'type' => $ressource->gettype(),
                //'person' => $resource->getPerson(),
            ];
        }
        //dump($data);die();
        return new JsonResponse($data);
        

    }


    /**
     * @route("/searchok", name="resources.searchok")
     */

    public function searchok(Request $request, SessionInterface $session, TransformedFinder $resourcesFinder): Response
    {
        //$q = (string) $request->query->get('q', '**');
        $q = $request->get('q', null);
        //$q = '*' . $q . "*";
        $chacacters = array("/","La","Le","'",",","0","1","2","3","4","5","6","7","8","9","(",")",".");
        $chacacters2 = array("-");
        //$chacacters = array(",");
        //$q = str_replace($chacacters, "", $q);
        //$q = str_replace($chacacters2, " ", $q);
        $q = rtrim($q);
        $q = '.' . $q . ".";
        dump($q);
        $pagination = $this->findHybridPaginated($resourcesFinder, Util::escapeTerm($q));
        //$pagination = $this->findHybridPaginated($resourcesFinder, $q);
        //$facets = $this->getFacets($q);
        $typesFacet = $this->getFacet('types', 'type', $q);
        $personsFacet = $this->getFacet('persons', 'person', $q);
        $oeuvresFacet = $this->getFacet('oeuvres', 'oeuvre', $q);
        $organismesFacet = $this->getFacet('organismes', 'organisme', $q);
        //$pagination = $resourcesFinder->findHybridPaginated(Util::escapeTerm($q));
        $pagination->setCurrentPage($request->query->getInt('page', 1));
        $pagination->setMaxPerPage(12);
        $session->set('q', $q);
        $nbresult = $pagination->getNbResults();
        
        dump($typesFacet);
        dump($personsFacet);
        dump($oeuvresFacet);
        dump($organismesFacet);
        #return $this->render('pages/results.html.twig', compact('pagination', 'q'));
        return $this->render('pages/results.html.twig',[
            'current_page' => 'results',
            'pagination' => $pagination,
            'nbresult' => $nbresult
            //'facets' => $facets 
            //'form' => $form->createView()
        ]);
    }

    /**
     * @route("/searchfacet1", name="resources.searchfacet1")
     */
    public function searchFacet1(Request $request, SessionInterface $session, TransformedFinder  $resourcesFinder)
    {
        $session->remove('q');
        $q = $request->get('q', null); 
        $field = $request->get('field', null);
        $facet = $request->get('facet', null);
       
        $session->set('field', $field);
        $session->set('facet', $facet);
        
        //$q = rtrim($q);
        if ($field == '_all') {
            if (strlen($q) >= 100) {
                $q = substr($q, 0 , 100);
            }
        }
         
        $chacacters = array("\"","/","L'",",","0","1","2","3","4","5","6","7","8","9","(",")",".","&","quot;","[","]","(Le)","(La)","(L')","»","«",":","?","!");
         //$chacacters = array("\"","/","L'",",",".","&","quot;","[","]","(Le)","(La)","(L')","»","«",":","?","!");
         $chacacters2 = array("'","-"," ","'");
         //$chacacters2 = array("'"," ","'");
         // $q = self::cleanStr($q);
         $q = self::enleverCaracteresSpeciaux($q);
         $q = preg_replace('/[^A-Za-z0-9\-]/', '*', $q);
         //$q = str_replace($chacacters, "*", $q);
         //$q = str_replace($chacacters2, "*", $q);
         $q = strtolower($q);
         if ($field == '_all'){
            $q = '*'.$q."*";
         }else{
            $q = '*'.$q."*";
         }
         
        $session->set('q', $q); 
        $allresults = $this->queryAll($q, $field, $facet, $resourcesFinder, $request);
        
        $pagination = $allresults[0];
        $nbresult = $allresults[1];
        $typesFacet = $allresults[2];
        $personsFacet = $allresults[3];
        $oeuvresFacet = $allresults[4];
        $organismesFacet = $allresults[5];
        $tagsFacet = $allresults[6];
        $geosFacet = $allresults[7];

        /**
        $query = $this->repository->searchFullElastic($q, $field, $facet, $resourcesFinder, $request);

        $paginatorAdapter = $resourcesFinder->createHybridPaginatorAdapter($query);
        $pagination = new Pagerfanta(new FantaPaginatorAdapter($paginatorAdapter));

        $pagination->setCurrentPage($request->query->getInt('page', 1));
        $pagination->setMaxPerPage(12);
       
        $nbresult = $pagination->getNbResults();
        
        
        if ($field == '_all'){
            $typesFacet = $this->getSearchFacet1('types', 'type', $q, 200);
            $personsFacet = $this->getSearchFacet1('persons', 'person', $q, 15);
            $oeuvresFacet = $this->getSearchFacet1('oeuvres', 'oeuvre', $q, 15);
            $organismesFacet = $this->getSearchFacet1('organismes', 'organisme', $q, 10);
            $tagsFacet = $this->getSearchFacet1('tags', 'tag', $q, 15);
            $geosFacet = $this->getSearchFacet1('geos', 'geo', $q, 15);
         }
         elseif ($field == 'person' || $field == 'oeuvre' || $field == 'organisme' || $field == 'type' || $field == 'tag' || $field == 'geo') {
            $typesFacet = $this->getSearchFacet1('types', 'type', $q, 20);
            $personsFacet = $this->getSearchFacet1('persons', 'person', $q, 15);
            $oeuvresFacet = $this->getSearchFacet1('oeuvres', 'oeuvre', $q, 15);
            $organismesFacet = $this->getSearchFacet1('organismes', 'organisme', $q, 10);
            $tagsFacet = $this->getSearchFacet1('tags', 'tag', $q, 15);
            $geosFacet = $this->getSearchFacet1('geos', 'geo', $q, 15);
         }
          */
   
         return $this->render('pages/results.html.twig',[
            'current_page' => 'results',
            'pagination' => $pagination,
            'nbresult' => $nbresult,
            'typesFacet' => $typesFacet,
            'personsFacet' => $personsFacet,
            'oeuvresFacet' => $oeuvresFacet,
            'organismesFacet' => $organismesFacet,
            'tagsFacet' => $tagsFacet,
            'geosFacet' => $geosFacet
        ]);
    }


    /**
     * @route("/searchfacet", name="resources.searchfacet")
     */

     public function searchFacet(SessionInterface $session, Request $request, TransformedFinder  $resourcesFinder)
     {
        /*** DEBUT
          * AUTRE METHODE A TESTER AVEC LES TRIS 
          * Voir https://elastica.io/getting-started/search-documents.html
          */
         /**
         $client = new \Elastica\Client();
         $search = new \Elastica\Search($client);
         $search
            ->addIndex('app')
            ->addType('resource');
         $query = new \Elastica\Query();
         $query
            ->setFieldDataFields(['tag']);
        $term = new \Elastica\Query\Term(['tag' => '**danse**']);
        $query = new \Elastica\Query($term);
        $search->setQuery($query);
        $resultSet = $search->search();
         
         $query = new \Elastica\Query([
            'query' => [
                'term' => ['_all' => '* danse *'],
            ],
        ]);
        
        $term = new \Elastica\Query\Term(['_all' => '** danse **']);
        $query = new \Elastica\Query($term);
        $search->setQuery($query);
        $resultSet = $search->search();
        
        $results = $resultSet->getResults();
        dump($results);
        $totalResults = $resultSet->getTotalHits(); 
        dump($totalResults);die();
        **/
        /** FIN */
        
        $session->remove('q');
        
        $q = $request->get('q', null); 
        $session->set('q', $q);
        $field = $request->get('fields', null);

        $q = self::enleverCaracteresSpeciaux($q);
        $q = rtrim($q);
        if ($field == '_all') {
            if (strlen($q) >= 100) {
                $q = substr($q, 0 , 100);
            }
        }
        
         $chacacters = array("\"","/","L'",",","0","1","2","3","4","5","6","7","8","9","(",")",".","&","quot;","[","]","(Le)","(La)","(L')","»","«",":","?","!");
         //$chacacters = array("\"","/","L'",",",".","&","quot;","[","]","(Le)","(La)","(L')","»","«",":","?","!");
         $chacacters2 = array("'","-"," ","'");
         //$chacacters2 = array("'"," ","'");
         $q = str_replace($chacacters, "*", $q);
         $q = str_replace($chacacters2, "* *", $q);
         if ($field == '_all'){
            $q = '*'.$q."*";
         }else{
            $q = '*'.$q."*";
         }

         
        /*** 2ème méthode */
        /**
        
        $query = new Query\BoolQuery();
        if ($field == '_all'){
        $query->addShould((new Query\Match())
            ->setFieldQuery('title', $q)
            ->setFieldBoost('title', 9)
        );
        $query->addShould((new Query\Match())
             ->setFieldQuery('tag', $q)
             ->setFieldBoost('tag', 9)
        );
        $query->addShould((new Query\Match())
             ->setFieldQuery('person', $q)
             ->setFieldBoost('person', 8)
        );
        $query->addShould((new Query\Match())
             ->setFieldQuery('oeuvre', $q)
             ->setFieldBoost('oeuvre', 8)
        );
        $query->addShould((new Query\Match())
             ->setFieldQuery('organisme', $q)
             ->setFieldBoost('organisme', 8)
        );
        $query->addShould((new Query\Match())
             ->setFieldQuery('allIndex', $q)
             ->setFieldBoost('allIndex', 7)
        );
        }
        if ($field == 'type'){
            $query->addShould((new Query\Match())
                ->setFieldQuery('type', $field)
                ->setFieldBoost('type', 2)
            );
         }

         dump($query);die();
        json_encode($q);die();
         */
        /** Fin 2ème méthode */
        /** 3 ème méthode */
        
         $searchQuery = new \Elastica\Query\QueryString();
         
         $searchQuery->setDefaultOperator('AND');
         $searchQuery->setAllowLeadingWildcard('true');
         $searchQuery->setAnalyzeWildcard('true');
         $searchQuery->setPhraseSlop(0);
         
         if ($field == '_all') {
            $searchQuery->setDefaultField('*');
         }
         elseif ($field == 'person'){
            $searchQuery->setFields(['person']);
         }
         elseif ($field == 'oeuvre'){
            $searchQuery->setFields(['oeuvre']);
         }
         elseif ($field == 'organisme'){
            $searchQuery->setFields(['organisme']);
         }
         elseif ($field == 'type'){
            //$searchQuery->setFields(['type','person','organisme','oeuvre','tag']);
            $searchQuery->setDefaultField('*');
         }
         elseif ($field == 'tag'){
            $searchQuery->setFields(['tag']);
         }
         $searchQuery->setQuery($q);
         
        
         $query = new Query();
         $query->setQuery($searchQuery);

        
         
         $query->addSort(array('type' => array('order' => 'desc')));
        
         //$query->addSort(array('type' => array('order' => 'asc')));

         /** Fin 3ème méthode */
         
         

         $paginatorAdapter = $resourcesFinder->createHybridPaginatorAdapter($query);
         $pagination = new Pagerfanta(new FantaPaginatorAdapter($paginatorAdapter));
        
         /** Facets */
         if ($field == '_all'){
            $typesFacet = $this->getSearchFacet('types', 'type', $q, 20);
            $personsFacet = $this->getSearchFacet('persons', 'person', $q, 15);
            $oeuvresFacet = $this->getSearchFacet('oeuvres', 'oeuvre', $q, 15);
            $organismesFacet = $this->getSearchFacet('organismes', 'organisme', $q, 10);
            $tagsFacet = $this->getSearchFacet('tags', 'tag', $q, 15);
            $geosFacet = $this->getSearchFacet('geos', 'geo', $q, 15);
         }
         elseif ($field == 'person' || $field == 'oeuvre' || $field == 'organisme' || $field == 'type' || $field == 'tag') {
            $typesFacet = $this->getSearchFacet('types', 'type', $q, 20);
            $personsFacet = $this->getSearchFacet('persons', 'person', $q, 15);
            $oeuvresFacet = $this->getSearchFacet('oeuvres', 'oeuvre', $q, 15);
            $organismesFacet = $this->getSearchFacet('organismes', 'organisme', $q, 10);
            $tagsFacet = $this->getSearchFacet('tags', 'tag', $q, 15);
            $geosFacet = $this->getSearchFacet('geos', 'geo', $q, 15);
         /**
         $typesFacet = $this->getFacet('types', 'type', $q, $field);
         $personsFacet = $this->getFacet('persons', 'person', $q, $field);
         $oeuvresFacet = $this->getFacet('oeuvres', 'oeuvre', $q, $field);
         $organismesFacet = $this->getFacet('organismes', 'organisme', $q, $field);
         $tagsFacet = $this->getFacet('tags', 'tag', $q, $field);
         $geosFacet = $this->getFacet('geos', 'geo', $q, $field);
          */
        }
        /**
         dump($typesFacet);
         dump($personsFacet);
         dump($oeuvresFacet);
         dump($organismesFacet);
         dump($tagsFacet);
          */
          dump($q);

         $pagination->setCurrentPage($request->query->getInt('page', 1));
         $pagination->setMaxPerPage(12);
        
         $nbresult = $pagination->getNbResults();
         
         return $this->render('pages/results.html.twig',[
            'current_page' => 'results',
            'pagination' => $pagination,
            'nbresult' => $nbresult,
            'typesFacet' => $typesFacet,
            'personsFacet' => $personsFacet,
            'oeuvresFacet' => $oeuvresFacet,
            'organismesFacet' => $organismesFacet,
            'tagsFacet' => $tagsFacet,
            'geosFacet' => $geosFacet

            //'form' => $form->createView()
        ]);

     }    

    

     /**
     * @route("/search", name="resources.search")
     */

    public function search(SessionInterface $session, Request $request, TransformedFinder  $resourcesFinder)
    {
        $q = $request->get('q', null);
        $field = $request->get('fields', null);

       
        //$q = self::convert_from_latin1_to_utf8_recursively($q);
        $q = self::enleverCaracteresSpeciaux($q);
        $q = htmlspecialchars($q);
        
        
        if (strlen($q) >= 40) {
            $q = substr($q, 0 , 40);
        }
        //dump($q);die();
        //parse_str($q, $result);
       

        //$chacacters = array("/","L'","'",",","0","1","2","3","4","5","6","7","8","9","(",")",".","-","\"");
        $chacacters2 = array(" ");
        $chacacters = array("&","quot;","[","]","(Le)","(La)","(L')","»","«",":","?","!","'");
        $q = str_replace($chacacters, "", $q);
        $q = str_replace($chacacters2, "%", $q);
        $q = rtrim($q);
        //dump($q);die();
        //$q = '*%'.$q.'%*';
       // $q = 'Gopal ram';
        
        $searchQuery = new \Elastica\Query\QueryString();
        $searchQuery->setDefaultOperator('AND');
        $searchQuery->setDefaultField('*');
        $searchQuery->setAllowLeadingWildcard('true');
        $searchQuery->setAnalyzeWildcard('true');
        $searchQuery->setPhraseSlop(0);
        
        
        //$searchQuery->setFields(['auteur','title','person','oeuvre','organisme','type','tag','collection','comment']);
        //$searchQuery->setFields(["title","person","comment"]);
        //$searchQuery->setFields(["resource.*"]);
        //$searchQuery->setParam('query', '.'.$q.'*');
       
        $searchQuery->setQuery($q.'*');
        
        
        $paginatorAdapter = $resourcesFinder->createHybridPaginatorAdapter($searchQuery);
        $pagination = new Pagerfanta(new FantaPaginatorAdapter($paginatorAdapter));
        /** Facets */
        $typesFacet = $this->getSearchFacet('types', 'type', $q);
        $personsFacet = $this->getSearchFacet('persons', 'person', $q);
        $oeuvresFacet = $this->getSearchFacet('oeuvres', 'oeuvre', $q);
        $organismesFacet = $this->getSearchFacet('organismes', 'organisme', $q);
        $tagsFacet = $this->getSearchFacet('tags', 'tag', $q);
        //dump($field);
        dump($q);
        dump("Type : ");
        dump($typesFacet);
        dump("Personnes : ");
        dump($personsFacet);
        dump("Oeuvres : ");
        dump($oeuvresFacet);
        dump("Organismes ");
        dump($organismesFacet);
        dump("Tags ");
        dump($tagsFacet);

        $pagination->setCurrentPage($request->query->getInt('page', 1));
        $pagination->setMaxPerPage(12);
        $session->set('q', $q);
        $nbresult = $pagination->getNbResults();
        
        return $this->render('pages/results.html.twig',[
           'current_page' => 'results',
           'pagination' => $pagination,
           'nbresult' => $nbresult,
           
           //'form' => $form->createView()
       ]);

    }

    /**
     * @route("/searchfitres", name="resources.searchfiltres")
     */

    public function searchFiltres(SessionInterface $session, Request $request, TransformedFinder  $resourcesFinder)
    {
        $q = $request->get('q', null);
        $field = $request->get('fields', null);

       
        //$q = self::convert_from_latin1_to_utf8_recursively($q);
        $q = self::enleverCaracteresSpeciaux($q);
        $q = htmlspecialchars($q);
        
        
        if (strlen($q) >= 40) {
            $q = substr($q, 0 , 40);
        }
        //dump($q);die();
        //parse_str($q, $result);
       

        //$chacacters = array("/","L'","'",",","0","1","2","3","4","5","6","7","8","9","(",")",".","-","\"");
        $chacacters2 = array(" ");
        $chacacters = array("&","quot;","[","]","(Le)","(La)","(L')","»","«",":","?","!");
        $q = str_replace($chacacters, "", $q);
        $q = str_replace($chacacters2, "%", $q);
        $q = rtrim($q);
        //dump($q);die();
        //$q = '*%'.$q.'%*';
       // $q = 'Gopal ram';
        
        $searchQuery = new \Elastica\Query\QueryString();
        $searchQuery->setDefaultOperator('AND');
        $searchQuery->setDefaultField('*');
        $searchQuery->setAllowLeadingWildcard('true');
        $searchQuery->setAnalyzeWildcard('true');
        $searchQuery->setPhraseSlop(0);
        //$searchQuery->setParams([
          //  'type' => $facet,
           // 'person' => $q
        //]);
        
        //$searchQuery->setFields(['auteur','title','person','oeuvre','organisme','type','tag','collection','comment']);
        //$searchQuery->setFields(["title","person","comment"]);
        //$searchQuery->setFields(["resource.*"]);
        //$searchQuery->setParam('query', $q);

        $searchQuery->setQuery($q.'*');
        
        
        $paginatorAdapter = $resourcesFinder->createHybridPaginatorAdapter($searchQuery);
        $pagination = new Pagerfanta(new FantaPaginatorAdapter($paginatorAdapter));
        /** Facets */
        $typesFacet = $this->getSearchFacet('types', 'type', $q, 40);
        $personsFacet = $this->getSearchFacet('persons', 'person', $q, 40);
        $oeuvresFacet = $this->getSearchFacet('oeuvres', 'oeuvre', $q, 40);
        $organismesFacet = $this->getSearchFacet('organismes', 'organisme', $q, 40);
        $tagsFacet = $this->getSearchFacet('tags', 'tag', $q, 40);
        $geosFacet = $this->getSearchFacet('geos', 'geo', $q, 40);
        //dump($field);
        dump($q);
        dump("Type : ");
        dump($typesFacet);
        dump("Personnes : ");
        dump($personsFacet);
        dump("Oeuvres : ");
        dump($oeuvresFacet);
        dump("Organismes ");
        dump($organismesFacet);
        dump("Tags ");
        dump($tagsFacet);

        $pagination->setCurrentPage($request->query->getInt('page', 1));
        $pagination->setMaxPerPage(12);
        $session->set('q', $q);
        $nbresult = $pagination->getNbResults();
        
        return $this->render('pages/results.html.twig',[
           'current_page' => 'results',
            'pagination' => $pagination,
            'nbresult' => $nbresult,
            'typesFacet' => $typesFacet,
            'personsFacet' => $personsFacet,
            'oeuvresFacet' => $oeuvresFacet,
            'organismesFacet' => $organismesFacet,
            'tagsFacet' => $tagsFacet,
            'geosFacet' => $geosFacet
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
        //$query = "histoire AND de AND la AND danse";
        $query_tab = explode(' ', $query);
        $query = implode(' AND ', $query_tab);
        $searchQuery->setParam('query', $query);
        $searchQuery->setDefaultOperator('OR');
        $paginatorAdapter = $resourcesFinder->createHybridPaginatorAdapter($searchQuery);

        return new Pagerfanta(new FantaPaginatorAdapter($paginatorAdapter));
       
    }

    /**
     * @route("/facettests", name="resources.facettest") 
     */
    
     public function getFacetsTest($queryfacet)
     {
         //dump($queryfacet);die();
         $termAgg = new Terms("types");
         $termAgg->setField("type");
         $termAgg->setSize(30);
         //$termAgg->setOrder("height_stats.avg", "desc");

         $query = new Query();
         $q = new BoolQuery;
         
         $query->addAggregation($termAgg);
         $elasticaQuery = new \Elastica\Query\QueryString();
         $elasticaQuery->setQuery($queryfacet);
         $elasticaQuery->setDefaultOperator('AND');
         $q->addMust($elasticaQuery);
         $query->setQuery($q);

         $elasticaClient = new Client();
         $index = $elasticaClient->getIndex('app');
         $docTypes = $index->search($query)->getAggregation("types");
        
         return $docTypes;

         //return new JsonResponse($docTypes);
     }

     /**
     * @route("/facets", name="resources.facets") 
     */
    
    public function getFacet($facet, $index, $queryfacet, $field)
    {
        //dump($queryfacet);die();
        $termAgg = new Terms($facet);
        $termAgg->setField($index);
        $termAgg->setSize(30);
        
        //$termAgg->setOrder("height_stats.avg", "desc");

        $query = new Query();
        $q = new BoolQuery;
        
        $query->addAggregation($termAgg);
        $elasticaQuery = new \Elastica\Query\QueryString();
        $elasticaQuery->setQuery($queryfacet);
        $elasticaQuery->setDefaultOperator('AND');
        
        $elasticaQuery->setFields([$field]);
        
        $q->addMust($elasticaQuery);
        //$query->setSource($index);
        $query->setQuery($q);
        
        
        
        $elasticaClient = new Client();
        $index = $elasticaClient->getIndex('app');
        $getFacet = $index->search($query)->getAggregation($facet);
        
        return $getFacet;

        //return new JsonResponse($docTypes);
    }

    /**
     * @route("/searchfacets", name="resources.searchfacets") 
     */
    
    public function getSearchFacet($facet, $index, $queryfacet, $size)
    {
        //dump($queryfacet);die();
        $termAgg = new Terms($facet);
        $termAgg->setField($index);
        $termAgg->setSize($size);
        //dump($index);die();
        /**
        if ($index == 'type') {
            $termAgg->setSize(1);
        }else{
            $termAgg->setSize($size);
        }
         */
        //$termAgg->setOrder("height_stats.avg", "desc");

        $query = new Query();
        $q = new BoolQuery;
        
        $query->addAggregation($termAgg);
        $elasticaQuery = new \Elastica\Query\QueryString();
        $elasticaQuery->setDefaultField('*');
        //$elasticaQuery->setFields([$facet,'tag','type']);
        $elasticaQuery->setQuery($queryfacet);
        $elasticaQuery->setDefaultOperator('AND');
        
        $elasticaQuery->setAnalyzeWildcard('true');
        

        $q->addMust($elasticaQuery);
        $query->setQuery($q);
        
        $elasticaClient = new Client();
        $index = $elasticaClient->getIndex('app');
        $resultSet = $index->search($query);
        $facets = $resultSet->getAggregation($facet);
        //$test3 = array_column($resultSet->getAggregations()[$facet]['buckets'],'doc_count','key');
        //$test2 = $resultSet->getTotalHits();
        //$test3 = $resultSet->getResults();
        //dump($facets);die();
        return $facets;
    }

    /**
     * @route("/searchfacets1", name="resources.searchfacets1") 
     */
    
    public function getSearchFacet1($facet, $index, $queryfacet, $size)
    {
        //dump($queryfacet);die();
        $termAgg = new Terms($facet);
        $termAgg->setField($index);
        $termAgg->setSize($size);
        //dump($index);die();
        /**
        if ($index == 'type') {
            $termAgg->setSize(1);
        }else{
            $termAgg->setSize($size);
        }
         */
        //$termAgg->setOrder("height_stats.avg", "desc");
         $boolQuery = new BoolQuery();
         $boolTermQuery = new BoolQuery();

         $termAllIndex = new Wildcard();
         $termAllIndex->setParams(['allIndex' => $queryfacet]);
         $boolTermQuery->addShould($termAllIndex);
        /**
         $termTitle = new Wildcard();
         $termTitle->setParams(['type' => $queryfacet]);
         $boolTermQuery->addShould($termTitle);
        */
         $query = new Query();
         $q = new BoolQuery;
        
        $query->addAggregation($termAgg);
        $query->setQuery($boolQuery);
        
        

        $q->addMust($boolTermQuery);
        $query->setQuery($q);
        
        $elasticaClient = new Client();
        $index = $elasticaClient->getIndex('app');
        $resultSet = $index->search($query);
        $facets = $resultSet->getAggregation($facet);
        //$test3 = array_column($resultSet->getAggregations()[$facet]['buckets'],'doc_count','key');
        //$test2 = $resultSet->getTotalHits();
        //$test3 = $resultSet->getResults();
        //dump($facets);die();
        return $facets;
    }

/**
 * Encode array from latin1 to utf8 recursively
 * @param $text
 * @return string
 */
    public static function enleverCaracteresSpeciaux(String $text) {
        $utf8 = array(
        '/[áàâãªä]/u' => 'a',
        '/[ÁÀÂÃÄ]/u' => 'A',
        '/[ÍÌÎÏ]/u' => 'I',
        '/[íìîï]/u' => 'i',
        '/[éèêë]/u' => 'e',
        '/[ÉÈÊË]/u' => 'E',
        '/[óòôõºö]/u' => 'o',
        '/[ÓÒÔÕÖ]/u' => 'O',
        '/[úùûü]/u' => 'u',
        '/[ÚÙÛÜ]/u' => 'U',
        '/ç/' => 'c',
        '/Ç/' => 'C',
        '/ñ/' => 'n',
        '/Ñ/' => 'N',
        '/\s\s+/' => ' ',
        #'/",/u' => '*',
        #'/0123456789/u' => '*',
        #'/().&/u' => '*',
        #'/quot;/u' => '*',
        #'/[]/u' => '*',
        #'/(Le)L(L\')/u' => '*',
        #'/»«:?!/u' => '*',
        #'//' => '-', // conversion d'un tiret UTF-8 en un tiret simple
        #'/[]/u' => ' ', // guillemet simple
        #'/\[«»]/u' => '*', // guillemet double
        #'/ /' => ' ', // espace insécable (équiv. à 0x160)
        );
        $text = preg_replace(array_keys($utf8), array_values($utf8), $text);
        return $text;
        }
    
        
    public static function cleanStr($string){
        // Replaces all spaces with hyphens.
        //$string = str_replace(' ', '*', $string);
    
        // Removes special chars.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '*', $string);
        // Replaces multiple hyphens with single one.
        //$string = preg_replace('/-+/', '', $string);
        
        return $string;
    }

 /**
 * Encode array from latin1 to utf8 recursively
 * @param $dat
 * @return array|string
 */
   public static function convert_from_latin1_to_utf8_recursively($dat)
   {
      if (is_string($dat)) {
         return utf8_encode($dat);
      } elseif (is_array($dat)) {
         $ret = [];
         foreach ($dat as $i => $d) $ret[ $i ] = self::convert_from_latin1_to_utf8_recursively($d);

         return $ret;
      } elseif (is_object($dat)) {
         foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);

         return $dat;
      } else {
         return $dat;
      }
   }
   
   public  function queryAll($q, $field, $facet, TransformedFinder $resourcesFinder, Request $request)
   {
    $query = $this->repository->searchFullElastic($q, $field, $facet, $resourcesFinder, $request);

    $paginatorAdapter = $resourcesFinder->createHybridPaginatorAdapter($query);
    $pagination = new Pagerfanta(new FantaPaginatorAdapter($paginatorAdapter));

    $pagination->setCurrentPage($request->query->getInt('page', 1));
    $pagination->setMaxPerPage(12);
    $nbresult = $pagination->getNbResults();

    /** Facets */
    if ($field == '_all'){
        $typesFacet = $this->getSearchFacet1('types', 'type', $q, 200);
        $personsFacet = $this->getSearchFacet1('persons', 'person', $q, 15);
        $oeuvresFacet = $this->getSearchFacet1('oeuvres', 'oeuvre', $q, 15);
        $organismesFacet = $this->getSearchFacet1('organismes', 'organisme', $q, 10);
        $tagsFacet = $this->getSearchFacet1('tags', 'tag', $q, 15);
        $geosFacet = $this->getSearchFacet1('geos', 'geo', $q, 15);
     }
     elseif ($field == 'person' || $field == 'oeuvre' || $field == 'organisme' || $field == 'type' || $field == 'tag' || $field == 'geo') {
        $typesFacet = $this->getSearchFacet1('types', 'type', $q, 20);
        $personsFacet = $this->getSearchFacet1('persons', 'person', $q, 15);
        $oeuvresFacet = $this->getSearchFacet1('oeuvres', 'oeuvre', $q, 15);
        $organismesFacet = $this->getSearchFacet1('organismes', 'organisme', $q, 10);
        $tagsFacet = $this->getSearchFacet1('tags', 'tag', $q, 15);
        $geosFacet = $this->getSearchFacet1('geos', 'geo', $q, 15);
     }
     
     $allResults = array($pagination, $nbresult, $typesFacet, $personsFacet, $oeuvresFacet, $organismesFacet, $tagsFacet, $geosFacet);
     return ($allResults);

   }
}


