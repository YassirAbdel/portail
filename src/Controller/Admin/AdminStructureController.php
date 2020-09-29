<?php

namespace App\Controller\Admin;

use App\Entity\Structure;
use App\Form\StructureType;
use App\Repository\StructureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/structure")
 */
class AdminStructureController extends AbstractController
{
    /**
     * @Route("/", name="admin.structure.index", methods={"GET"})
     */
    public function index(StructureRepository $structureRepository): Response
    {
        return $this->render('admin/structure/index.html.twig', [
            'structures' => $structureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.structure.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $structure = new Structure();
        $form = $this->createForm(StructureType::class, $structure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($structure);
            $entityManager->flush();

            return $this->redirectToRoute('admin.structure.index');
        }

        return $this->render('admin/structure/new.html.twig', [
            'structure' => $structure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.structure.show", methods={"GET"})
     */
    public function show(Structure $structure): Response
    {
        return $this->render('admin/structure/show.html.twig', [
            'structure' => $structure,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.structure.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Structure $structure): Response
    {
        $form = $this->createForm(StructureType::class, $structure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.structure.index', [
                'id' => $structure->getId(),
            ]);
        }

        return $this->render('admin/structure/edit.html.twig', [
            'structure' => $structure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.structure.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Structure $structure): Response
    {
        if ($this->isCsrfTokenValid('delete'.$structure->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($structure);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.structure.index');
    }
}
