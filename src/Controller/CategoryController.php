<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController 
{
    /**
     * @Route("/collection/{slug}-{id}" , name="category.show", requirements={"slug" : "^[a-z0-9]+(?:-[a-z0-9]+)*$", "id" : "\d+"},  methods="GET|POST")
     * @return Response
     * @param Category category
     * @param string slug
     */

    public function show(Category $category, String $slug) 
    {
        $caterory_slug = $category->getSlug();
        $category_id = $category->getId();

        if($caterory_slug !== $slug) {
            return $this->redirectToRoute('category.show', [
                'id' => $category_id,
                'slug' => $caterory_slug
            ], 301);
        }
        
        return $this->render('pages/collection.html.twig', [
            'collection' => $category
        ]);
    }

}