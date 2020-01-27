<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use App\Entity\SuperHero;
use App\SearchRepository\SearchRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SuperHeroController extends AbstractController
{
   
    /**
     * @route("/superheroSearch", name="superhero.search")
     *
     * @param RepositoryManagerInterface $manager
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function searchSuperHero(RepositoryManagerInterface $manager, Request $request)
    {
        $query = $request->query->all();
        $search = isset($query['q']) && !empty($query['q']) ? $query['q'] : null;

        /** @var SuperHeroRepository $repository */
        $repository = $manager->getRepository(SuperHero::class);

        $superheroes = $repository->search($search);

        /** @var SuperHero $superhero */
        foreach ($superheroes as $superhero) {
            $data[] = [
                'name' => $superhero->getName(),
            ];
        }

        return new JsonResponse($data);
    }
}

