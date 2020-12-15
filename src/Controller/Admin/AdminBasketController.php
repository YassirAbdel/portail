<?php

namespace App\Controller\Admin;
use App\Entity\Basket;
use App\Form\BasketType;
use App\Repository\BasketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Resource;
use App\Controller\HomeController;
use FOS\ElasticaBundle\Finder\TransformedFinder;

/**
 * @Route("admin/basket")
 */
class AdminBasketController extends AbstractController
{
    /**
     * @Route("/", name="admin.basket.index", methods={"GET"})
     */
    public function index(BasketRepository $basketRepository): Response
    {
        return $this->render('admin/basket/index.html.twig', [
            'baskets' => $basketRepository->findAll(),
        ]);
    }
   
    /**
     * @Route("/new", name="admin.basket.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $basket = new Basket();
        $basket->setCreatAt( new \DateTime());
        
        $form = $this->createForm(BasketType::class, $basket);
        /**
        $form = $this->createFormBuilder($basket)
            ->add('title')
            ->add('resources')
           //->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();
        **/
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($basket);
            $entityManager->flush();
            $session->remove('panier');
            
            return $this->redirectToRoute('admin.basket.index');
        }
        
        return $this->render('admin/basket/new.html.twig', [
            'basket' => $basket,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/vider", name="admin.basket.vider", methods={"GET","POST"})
     */
    
    public function viderBasket(Request $request): Response
    {
        $session = $request->getSession();
        
        if($session->has('panier')) {
            $this->addFlash('basket.message.delete', "Votre sélection a été supprimée !");
            $session->remove('panier');
        }
        
        
        return $this->redirectToRoute('resource.index');
        
    }
    

    /**
     * @Route("/{id}/show", name="admin.basket.show", methods={"GET"})
     */
    public function show(Basket $basket): Response
    {
        dump($basket);
        $id_basket = $basket->getId();
        dump($id_basket);
        return $this->render('admin/basket/show.html.twig', [
            'basket' => $basket
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.basket.edit", methods={"GET","POST"})
     */
    public function edit(Basket $basket, Request $request): Response
    {
        //$form = $this->createForm(BasketType::class, $basket);
    
        $form = $this->createFormBuilder($basket)
            ->add('title')
            ->getForm();
   
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('basket.index', [
                'id' => $basket->getId(),
            ]);
        }

        return $this->render('admin/basket/edit.html.twig', [
            'basket' => $basket,
            'form' => $form->createView(),
            
        ]);
    }

    /**
     * @Route("/{id}", name="admin.basket.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Basket $basket): Response
    {
        if ($this->isCsrfTokenValid('delete'.$basket->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($basket);
            $entityManager->flush();
        }
        return $this->redirectToRoute('basket.index');
    }
    
    
    
    /**
     * @route("/addressource/{slug}-{id}" , name="admin.resource.basketadd", requirements={"slug" : "^[a-z0-9]+(?:-[a-z0-9]+)*$", "id" : "\d+"},  methods="GET|POST")
     * @return Response
     * @param Resource resource
     * @param string slug
     * @param int $id
     */
    public function addBasket(Resource $resource, String $slug, Int $id, Request $request)
    {
        $resource_slug = $resource->getSlug();
        $resource_id = $resource->getId();
        if ($resource_slug !== $slug){
            return $this->redirectToRoute('resource.show', [
                'id' => $resource_id,
                'slug' => $resource_slug
            ],  301);
        }
        
        $session = $request->getSession();
        //$session->remove('panier');die();
        if(!$session->has('panier')) {
            $panier = new ArrayCollection();
            
            $session->set('panier', $panier);
        }
        $panier = $session->get('panier');
        
        
        if(\array_key_exists('id', $panier)) {
            
        } else {
            $panier[$resource->getId()] = $resource;
        }
        
        $session->set('panier', $panier);
        
        return $this->redirectToRoute('resource.index');
        
    }
    
   /**
     * @route("/addtoselection/{slug}-{id}" , name="admin.resource.selectionadd", requirements={"slug" : "^[a-z0-9]+(?:-[a-z0-9]+)*$", "id" : "\d+"},  methods="GET|POST")
     * @return Response
     * @param Resource resource
     * @param string slug
     * @param int $id
     */
    public function addtoselection(Resource $resource, String $slug, Request $request, TransformedFinder  $resourcesFinder, HomeController $homeController)
    {
        $resource_slug = $resource->getSlug();
        $resource_id = $resource->getId();
        if ($resource_slug !== $slug){
            return $this->redirectToRoute('resource.show', [
                'id' => $resource_id,
                'slug' => $resource_slug
            ],  301);
        }
        
        $session = $request->getSession();
        $q = $session->get('q');
        $field = $session->get('field');
        $facet = $session->get('facet');
        
        
        $allresults = $homeController->queryAll($q, $field, $facet, $resourcesFinder, $request);
        $pagination = $allresults[0];
    
        dump($pagination);
        $nbresult = $allresults[1];
        $typesFacet = $allresults[2];
        $personsFacet = $allresults[3];
        $oeuvresFacet = $allresults[4];
        $organismesFacet = $allresults[5];
        $tagsFacet = $allresults[6];
        $geosFacet = $allresults[7];

        if(!$session->has('panier')) {
            $panier = new ArrayCollection();
            $session->set('panier', $panier);
        }
        $panier = $session->get('panier');
        if(\array_key_exists('id', $panier)) {
            
        } else {
            $panier[$resource->getId()] = $resource;
        }
        $session->set('panier', $panier);
        
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
}
