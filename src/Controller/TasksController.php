<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TasksController extends AbstractController
{
    /**
     * Affichage de toutes les tâches d'un projet.
     */
    #[Route('/projet/{projectId}/taches', name: 'app_tasks_show', requirements: ['projectId' => '\d+'], methods: ['GET'])]
    public function index(int $projectId, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->find($projectId);

        $tasksProject = $project->getTasks();

        return $this->render('tasks/index.html.twig', [
            'project' => $project,
            'tasksProject' => $tasksProject
        ]);
    }
}
