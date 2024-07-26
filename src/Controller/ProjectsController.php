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
    public function __construct(
        private EntityManagerInterface $manager,
        private ProjectRepository $projectRepository,
    ) {
    }

    /**
     * Page projets.
     */
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {
        $projects = $this->projectRepository->findActiveProjects();

        return $this->render('projects/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * Création d'un projet.
     */
    #[Route('/project/creation', name: 'app_project_new', methods: ['POST', 'GET'])]
    public function new(Request $request): Response
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($project);
            $this->manager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('projects/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Mise à jour d'un projet.
     */
    #[Route('/projet/{projectId}/edition', name: 'app_project_edit', requirements: ['projectId' => '\d+'], methods: ['POST', 'GET'])]
    public function edit(int $projectId, Request $request): Response
    {
        $project = $this->projectRepository->find($projectId);
        if (!$project) {
            throw $this->createNotFoundException('Le projet n\'existe pas');
        }

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($project);
            $this->manager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('projects/edit.html.twig', [
            'form' => $form,
            'project' => $project
        ]);
    }

    /**
     * Archivage d'un projet.
     */
    #[Route('/projet/{projectId}/archiver', name: 'app_project_archive', requirements: ['projectId' => '\d+'], methods: ['POST'])]
    public function archive(int $projectId): Response
    {
        $project = $this->projectRepository->find($projectId);
        if (!$project) {
            throw $this->createNotFoundException('Le projet n\'existe pas');
        }

        $project->setArchive('true');

        $this->manager->persist($project);
        $this->manager->flush();

        return $this->redirectToRoute('app_home');
    }
}
