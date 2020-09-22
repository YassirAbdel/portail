<?php

namespace App\Controller\Admin;

use App\Entity\Subject;
use App\Form\SubjectType;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/subject")
 */
class AdminSubjectController extends AbstractController
{
    /**
     * @Route("/", name="admin.subject.index", methods={"GET"})
     */
    public function index(SubjectRepository $subjectRepository): Response
    {
        $subjets = $subjectRepository->findAll();
        dump($subjets);
        return $this->render('admin/subject/index.html.twig', [
            'subjects' => $subjets,
        ]);
    }

    /**
     * @Route("/new", name="admin.subject.add", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('admin.subject.index');
        }

        return $this->render('admin/subject/new.html.twig', [
            'subject' => $subject,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.subject.show", methods={"GET"})
     */
    public function show(Subject $subject): Response
    {
        return $this->render('admin/subject/show.html.twig', [
            'subject' => $subject,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.subject.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Subject $subject): Response
    {
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->render('admin/subject/edit.html.twig', [
                'subject' => $subject,
                'form' => $form->createView(),
            ]);
        }

        return $this->render('admin/subject/edit.html.twig', [
            'subject' => $subject,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.subject.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Subject $subject): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subject->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subject);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.subject.index');
    }
}
