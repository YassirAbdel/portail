<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ResourceRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Resource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use App\Form\ResourceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;



class AdminResourceController extends AbstractController {
    
    private $repository;
    private $em;
    
    /**
     * @var ResourceRepository
     */
    
    public function __construct(ResourceRepository $repository, ObjectManager $em)
    {
        
        $this->repository = $repository;
        $this->em = $em;
    }
    
    
    /**
     * @Route("/admin", name="admin.resource.index")
     * @return Response
     */
    
    public function index() : Response
    {
        $resources = $this->repository->findAll();
        return $this->render('admin/resource/index.html.twig', compact('resources'));
    }
    
    
    /**
     * @Route("admin/resource/edit/{id}", name="admin.resource.edit", methods="GET|POST")
     * @param  Resource $resource
     */
    
    public function edit(Resource $resource, Request $request)
    { 
        $form = $this->createForm(ResourceType::class, $resource);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
           $this->em->flush();
           $this->addFlash('success', 'Notice mise à jour avec succès');
           return $this->redirectToRoute('admin.resource.index');
        }
        return $this->render('admin/resource/edit.html.twig', [
            'resource' => $resource,
            'form' => $form->createView()
            
        ]);
    }
    
    /**
     * @Route("admin/resource/create", name="admin.resource.add")
     * @param Request $request 
     */
    public function add(Request $request) 
    {
        $resource = new Resource();
        $form = $this->createForm(ResourceType::class, $resource);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($resource);
            $this->em->flush();
            $this->addFlash('success', 'Notice créée avec succès');
            return $this->redirectToRoute('admin.resource.index');
        }
        
        return $this->render('admin/resource/new.html.twig', [
            'resource' => $resource,
            'form' => $form->createView()
            
        ]);
    }
    
    /**
     * @Route("admin/resource/edit/{id}", name="admin.resource.delete", methods="DELETE")
     * @param  Resource $resource
     */
    
    public function delete(Resource $resource, Request $request) {
        if ($this->isCsrfTokenValid('delete' . $resource->getId(), $request->get('_token')))
        {
            $this->em->remove($resource);
            $this->em->flush();
            $this->addFlash('success', 'Notice supprimée avec succès');
            
        }
        return $this->redirectToRoute('admin.resource.index');
    }
}