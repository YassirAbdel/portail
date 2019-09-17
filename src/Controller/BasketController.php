<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Form\BasketType;
use App\Repository\BasketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use App\Entity\Resource;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/basket")
 */
class BasketController extends AbstractController
{
    /**
     * @Route("/", name="basket.index", methods={"GET"})
     */
    public function index(BasketRepository $basketRepository): Response
    {
        return $this->render('basket/index.html.twig', [
            'baskets' => $basketRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="basket.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $session = $request->getSession();
        $basket = new Basket();
        $basket->setCreatAt( new \DateTime());
       
        $form = $this->createForm(BasketType::class, $basket);
        $resources = $session->get('panier');
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($basket);
            $entityManager->flush();
            
            return $this->redirectToRoute('basket.index');
        }
        
        return $this->render('basket/new.html.twig', [
            'basket' => $basket,
            'form' => $form->createView(),
            'csrf_protection' => false
        ]);
    }

    /**
     * @Route("/{id}", name="basket.show", methods={"GET"})
     */
    public function show(Basket $basket): Response
    {
        return $this->render('basket/show.html.twig', [
            'basket' => $basket,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="basket.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Basket $basket): Response
    {
        $form = $this->createForm(BasketType::class, $basket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('basket.index', [
                'id' => $basket->getId(),
            ]);
        }

        return $this->render('basket/edit.html.twig', [
            'basket' => $basket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="basket.delete", methods={"DELETE"})
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
     * @route("/addressource/{slug}-{id}" , name="resource.basketadd", requirements={"slug" : "^[a-z0-9]+(?:-[a-z0-9]+)*$", "id" : "\d+"},  methods="GET|POST")
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
            dump($panier);
        }
        
        $session->set('panier', $panier);
        
        return $this->redirectToRoute('basket.new');
        
    }
    
    
}
