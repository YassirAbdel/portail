<?php

namespace App\Controller\Admin;

use App\Entity\Work;
use App\Form\WorkType;
use App\Repository\WorkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/work")
 */
class AdminWorkController extends AbstractController
{
    /**
     * @Route("/", name="admin.work.index", methods={"GET"})
     */
    public function index(WorkRepository $workRepository): Response
    {
        return $this->render('admin/work/index.html.twig', [
            'works' => $workRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.work.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $work = new Work();
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($work);
            $entityManager->flush();

            return $this->redirectToRoute('admin.work.index');
        }

        return $this->render('admin/work/new.html.twig', [
            'work' => $work,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.work.show", methods={"GET"})
     */
    public function show(Work $work): Response
    {
        return $this->render('admin/work/show.html.twig', [
            'work' => $work,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.work.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Work $work): Response
    {
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.work.index', [
                'id' => $work->getId(),
            ]);
        }

        return $this->render('admin/work/edit.html.twig', [
            'work' => $work,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.work.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Work $work): Response
    {
        if ($this->isCsrfTokenValid('delete'.$work->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($work);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.work.index');
    }
}
