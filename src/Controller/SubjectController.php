<?php

namespace App\Controller;

use App\Entity\Subject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubjectController extends AbstractController
{
  /**
   * @Route("/sous-collection/{slug}-{id}", name="subject.show", requirements={"slug" : "^[a-z0-9]+(?:-[a-z0-9]+)*$", "id" : "\d+"}, methods="GET|POST")
   */

     public function show(Subject $subject, String $slug)
     {
        $subject_id = $subject->getId();
        $subject_slug = $subject->getSlug();

        if($subject_slug !== $slug) {
            return $this->redirectToRoute('sunject.show', [
                'id' => $subject_id,
                'slug' => $subject_slug
            ], 301);
        }
        dump($subject);

        return $this->render('pages/subject.html.twig', [
            'subject' => $subject
        ]);

     }
}
