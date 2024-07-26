<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * Création d'une tâche.
     */
    #[Route('/projet/{projectId}/tache/creation', name: 'app_task_create', methods: ['GET', 'POST'])]
    public function new(int $projectId, EntityManagerInterface $manager, Request $request, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->find($projectId);

        // On crée une nouvelle tâche et on renseigne le projet auquel elle est associée.
        $task = new Task();
        $task->setProject($project);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('app_tasks_show', ['projectId' => $projectId]);
        }

        return $this->render('tasks/new.html.twig', [
            'form' => $form,
        ]);
    }
}
