<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ResourceRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Resource;
use App\Entity\ResourceSearch;
use App\Form\ResourceSearchType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use App\Form\ResourceType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;



class AdminResourceController extends AbstractController {
    
    private $repository;
    private $em;
    private $token;
    private $user;
    /**
     * @var ResourceRepository
     */
    
    public function __construct(ResourceRepository $repository, ObjectManager $em, TokenStorageInterface $tokenStorage)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->token = $tokenStorage;
    }
    
    
    /**
     * @Route("/admin", name="admin.resource.index")
     * @return Response
     */
    
    public function index(Request $request, Security $container, PaginatorInterface $paginator)
    {
        $query = $this->repository->findAll();
        $search = new ResourceSearch;
            // On récupère le username via la variable globale session
            $session = new Session();
            $username = $session->get('username');
        $form = $this->createForm(ResourceSearchType::class, $search);
        $form->handleRequest($request);
        //$query = $this->repository->findAllVisibleQuery($search);
        $resources = $paginator->paginate(
                        $query,
                        $request->query->getInt('page', 1)/*page number*/,
                        20/*limit per page*/
                      );
        return $this->render('admin/resource/index.html.twig', [
            'current_page' => 'resources',
            'resources' => $resources,
            'form' => $form->createView()
        ]);
            /** NE FONCTIONNE PAS **/
            //dump($this->token->getToken()->getUser());
            //dump($this->token->getToken()->getUsername());
    }

    /**
     * @Route("/adminSearch", name="admin.resource.search")
     * @return Response
     */
    
    public function adminSearch(Request $request, Security $container, PaginatorInterface $paginator)
    {
        //$query = $this->repository->findAll();
        $search = new ResourceSearch;
            // On récupère le username via la variable globale session
            $session = new Session();
            $username = $session->get('username');
        $form = $this->createForm(ResourceSearchType::class, $search);
        $form->handleRequest($request);
        $query = $this->repository->findAllVisibleQuery($search);
        $resources = $paginator->paginate(
                        $query,
                        $request->query->getInt('page', 1)/*page number*/,
                        20/*limit per page*/
                      );
        return $this->render('admin/resource/index.html.twig', [
            'current_page' => 'resources',
            'resources' => $resources,
            'form' => $form->createView()
        ]);
            /** NE FONCTIONNE PAS **/
            //dump($this->token->getToken()->getUser());
            //dump($this->token->getToken()->getUsername());
    }
    
    
    /**
     * @Route("admin/resource/edit/{id}", name="admin.resource.edit", methods="GET|POST")
     * @param  Resource $resource
     */
    
    public function edit(Resource $resource, Request $request, CacheManager $cacheManager, UploaderHelper $helper)
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
    
    public function delete(Resource $resource, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $resource->getId(), $request->get('_token')))
        {
           // Supprimer le fichier dans le cache
           
            //$caheManager->remove($helper->asset($resource, 'imageFile'));
            $this->em->remove($resource);
            $this->em->flush();
            $this->addFlash('success', 'Notice supprimée avec succès');
            
        }
        return $this->redirectToRoute('admin.resource.index');
    }
}