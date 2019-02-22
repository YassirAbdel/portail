<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\ResourceRepository;

class HomeController  extends AbstractController {

   
    /**
     * @Route("/", name="home")
     * @param ResourceRepository $repository
     * @return Response
     */
    
    public function index(ResourceRepository $repository) : Response
    {
        $resources = $repository->findLast(); 
        return $this->render('pages/home.html.twig', [
            'current_page' => 'home',
            'resources' => $resources
        ]);
    }
}