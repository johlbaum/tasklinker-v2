<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectsController extends AbstractController
{
    /**
     * Page projets
     */
    #[Route('/', name: 'app_home')]
    public function index(ProjectRepository $repository): Response
    {
        $projects = $repository->findAll();
        if (!$projects) {
            throw $this->createNotFoundException('Less projets n\'existent pas');
        }

        return $this->render('projects/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * Création d'un projet
     */
    #[Route('/project/creation', name: 'app_project_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($project);
            $manager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('projects/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
