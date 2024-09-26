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
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
        // On récupère tous les projets non archivés.
        $activeProjects = $this->projectRepository->findActiveProjects();

        // Si l'utilisateur connecté a le rôle d'administrateur, on retourne l'ensemble des projets.
        if ($this->isGranted('ROLE_ADMIN')) {
            $accessibleProjects = $activeProjects;
        } else {
            // Sinon, on retourne uniquement les projets auxquels l'utilisateur connecté est associé.
            $accessibleProjects = array_filter($activeProjects, function ($project) {
                return $project->getEmployees()->contains($this->getUser());
            });
        }

        return $this->render('projects/index.html.twig', [
            'projects' => $accessibleProjects,
        ]);
    }

    /**
     * Création d'un projet : accessible uniquement aux chefs de projet.
     */
    #[IsGranted('ROLE_ADMIN')]
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
     * Mise à jour d'un projet : accessible uniquement aux chefs de projet.
     */
    #[IsGranted('ROLE_ADMIN')]
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
     * Archivage d'un projet : accessible uniquement aux chefs de projet.
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/projet/{projectId}/archiver', name: 'app_project_archive', requirements: ['projectId' => '\d+'], methods: ['POST', 'GET'])]
    public function archiveProject(int $projectId): Response
    {
        $project = $this->entityManagerService->getEntity($this->projectRepository, $projectId);

        $project->setArchive('true');

        $this->entityManagerService->save($project);

        return $this->redirectToRoute('app_home');
    }
}
