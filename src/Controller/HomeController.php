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
    public function index(Request $request, Session $session, TransformedFinder $resourcesFinder): Response
    {
        $q = (string) $request->query->get('q', '*');
        //$q = $request->get('q', '*');

        //$pagination = $this->findHybridPaginated($resourcesFinder, Util::escapeTerm($q));
        $pagination = $this->findHybridPaginated($resourcesFinder, $q);
        //$pagination = $resourcesFinder->findHybridPaginated(Util::escapeTerm($q));
        $pagination->setCurrentPage($request->query->getInt('page', 1));
        $pagination->setMaxPerPage(12);
        $nbresult = $pagination->getNbResults();
        $session->set('nbresultall', $nbresult);
        //$session->set('q', $q);

        //dump($pagination);die();

        //return $this->render('pages/results.html.twig', compact('pagination', 'q'));
        return $this->render('pages/home.html.twig',[
            'current_page' => 'home',
            'pagination' => $pagination,
            'nbresultall' => $nbresult
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
     * @route("/searchfacet", name="resources.searchfacet")
     */

     public function searchFacet(SessionInterface $session, Request $request, TransformedFinder  $resourcesFinder)
     {
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
         
       
         $q = '*'.$q."*";
         

         $searchQuery = new \Elastica\Query\QueryString();
         
         $searchQuery->setDefaultOperator('AND');
         $searchQuery->setAllowLeadingWildcard('true');
         $searchQuery->setAnalyzeWildcard('true');
         #$searchQuery->setPhraseSlop(0);
         $query = new \Elastica\Query\BoolQuery();
         if ($field == '_all') {
            $searchQuery->setDefaultField('*');
            
            dump("F : ".$field);
            dump('Q: ' . $q);
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
            $searchQuery->setFields(['type']);
         }
         elseif ($field == 'tag'){
            $searchQuery->setFields(['tag']);
         }
        
        $searchQuery->setQuery($q);
    

         $paginatorAdapter = $resourcesFinder->createHybridPaginatorAdapter($searchQuery);
         $pagination = new Pagerfanta(new FantaPaginatorAdapter($paginatorAdapter));
        
         /** Facets */
         if ($field == '_all'){
            $typesFacet = $this->getSearchFacet('types', 'type', $q, 10);
            $personsFacet = $this->getSearchFacet('persons', 'person', $q, 15);
            $oeuvresFacet = $this->getSearchFacet('oeuvres', 'oeuvre', $q, 15);
            $organismesFacet = $this->getSearchFacet('organismes', 'organisme', $q, 10);
            $tagsFacet = $this->getSearchFacet('tags', 'tag', $q, 15);
         }
         elseif ($field == 'person' || $field == 'oeuvre' || $field == 'organisme' || $field == 'type' || $field == 'tag') {
         $typesFacet = $this->getFacet('types', 'type', $q, $field);
         $personsFacet = $this->getFacet('persons', 'person', $q, $field);
         $oeuvresFacet = $this->getFacet('oeuvres', 'oeuvre', $q, $field);
         $organismesFacet = $this->getFacet('organismes', 'organisme', $q, $field);
         $tagsFacet = $this->getFacet('tags', 'tag', $q, $field);
        }
         dump($typesFacet);
         dump($personsFacet);
         dump($oeuvresFacet);
         dump($organismesFacet);
         dump($tagsFacet);

         $pagination->setCurrentPage($request->query->getInt('page', 1));
         $pagination->setMaxPerPage(12);
        
         $nbresult = $pagination->getNbResults();
         
         return $this->render('pages/results.html.twig',[
            'current_page' => 'results',
            'pagination' => $pagination,
            'nbresult' => $nbresult,
            'typesFacet' => $typesFacet,
            'personsFacet' => $personsFacet
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
           'nbresult' => $nbresult
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
        $searchQuery->setParams([
            'type' => $facet,
            'person' => $q
        ]);
        
        //$searchQuery->setFields(['auteur','title','person','oeuvre','organisme','type','tag','collection','comment']);
        //$searchQuery->setFields(["title","person","comment"]);
        //$searchQuery->setFields(["resource.*"]);
        //$searchQuery->setParam('query', $q);

        //$searchQuery->setQuery($q.'*');
        
        
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
           'nbresult' => $nbresult
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
        $termAgg = new Terms($facet);
        $termAgg->setField($index);
        $termAgg->setSize($size);
        
        //$termAgg->setOrder("height_stats.avg", "desc");

        $query = new Query();
        $q = new BoolQuery;
        //$q = $q . '*';
        
        $query->addAggregation($termAgg);
        $elasticaQuery = new \Elastica\Query\QueryString();
        $elasticaQuery->setQuery($queryfacet.'*');
        $elasticaQuery->setDefaultOperator('AND');
        $elasticaQuery->setAnalyzeWildcard('true');
        
        //$elasticaQuery->setFields([$field]);
        
        $q->addMust($elasticaQuery);
        //$query->setSource($index);
        $query->setQuery($q);
        
        
        
        $elasticaClient = new Client();
        $index = $elasticaClient->getIndex('app');
    
        $facets = $index->search($query)->getAggregation($facet);
        
        //$results = $index->search($query)->getResults();
        return $facets;

        //return new JsonResponse($facets);
    }

/**
 * Encode array from latin1 to utf8 recursively
 * @param $text
 * @return array|string
 */
    public function enleverCaracteresSpeciaux($text) {
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
        #'//' => '-', // conversion d'un tiret UTF-8 en un tiret simple
        #'/[]/u' => ' ', // guillemet simple
        #'/\[«»]/u' => ' ', // guillemet double
        #'/ /' => ' ', // espace insécable (équiv. à 0x160)
        );
        $text = preg_replace(array_keys($utf8), array_values($utf8), $text);
        return $text;
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

}


