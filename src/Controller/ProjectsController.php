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
use App\Service\EntityManagerService;

class ProjectsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private ProjectRepository $projectRepository,
        private EntityManagerService $entityManagerService
    ) {}

    /**
     * Page projets.
     */
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function showProjects(): Response
    {
        $projects = $this->projectRepository->findActiveProjects();

        return $this->render('projects/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * Création d'un projet.
     */
    #[Route('/projet/creation', name: 'app_project_new', methods: ['POST', 'GET'])]
    public function createProject(Request $request): Response
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManagerService->save($project);

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
    public function editProject(int $projectId, Request $request): Response
    {
        $project = $this->entityManagerService->getEntity($this->projectRepository, $projectId);

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManagerService->save($project);

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
    #[Route('/projet/{projectId}/archiver', name: 'app_project_archive', requirements: ['projectId' => '\d+'], methods: ['POST', 'GET'])]
    public function archiveProject(int $projectId): Response
    {
        $project = $this->entityManagerService->getEntity($this->projectRepository, $projectId);

        $project->setArchive('true');

        $this->entityManagerService->save($project);

        return $this->redirectToRoute('app_home');
    }
}
