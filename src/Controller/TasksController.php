<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\EntityManagerService;
use App\Service\AvatarService;


class TasksController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private TaskRepository $taskRepository,
        private ProjectRepository $projectRepository,
        private EntityManagerService $entityManagerService,
        private AvatarService $avatarService
    ) {
    }

    /**
     * Affichage de toutes les tâches d'un projet.
     */
    #[Route('/projet/{projectId}/taches', name: 'app_tasks_show', requirements: ['projectId' => '\d+'], methods: ['GET'])]
    public function showTasks(int $projectId): Response
    {
        // On récupère le projet et les employés associés.
        $project = $this->entityManagerService->getEntity($this->projectRepository, $projectId);
        $projectEmployees = $project->getEmployees()->toArray();

        // On génère les avatars des employés associés au projet.
        $this->avatarService->setAvatarsForProjectEmployees($projectEmployees);

        // On récupère les tâches du projet.
        $tasksProject = $project->getTasks()->toArray();

        // On génère les avatars des employés associés aux tâches.
        $this->avatarService->setAvatarsForTaskEmployees($tasksProject);

        return $this->render('tasks/index.html.twig', [
            'project' => $project,
            'tasksProject' => $tasksProject,
            'projectEmployees' => $projectEmployees,
        ]);
    }

    /**
     * Création d'une tâche.
     */
    #[Route('/projet/{projectId}/tache/creation', name: 'app_task_create', requirements: ['projectId' => '\d+'], methods: ['GET', 'POST'])]
    public function createTask(int $projectId, Request $request): Response
    {
        // On récupère le projet auquel la tâche va être associée.
        $project = $this->entityManagerService->getEntity($this->projectRepository, $projectId);

        // On crée une nouvelle tâche et on renseigne le projet auquel elle est associée.
        $task = new Task();
        $task->setProject($project);

        // On crée le formulaire et on ajoute les employés associés au projet en option du formulaire.
        // Objectif : Restreindre la liste des employés sélectionnables pour une tâche aux seuls employés 
        // participant au projet auquel la tâche est rattachée.
        $form = $this->createForm(TaskType::class, $task, [
            'projectEmployees' => $project->getEmployees()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManagerService->save($task);

            return $this->redirectToRoute('app_tasks_show', ['projectId' => $projectId]);
        }

        return $this->render('tasks/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Mise à jour d'une tâche.
     */
    #[Route('/projet/{projectId}/tache/{taskId}/edition', name: 'app_task_edit', requirements: ['projectId' => '\d+', 'taskId' => '\d+'], methods: ['GET', 'POST'])]
    public function editTask(int $taskId, int $projectId, Request $request): Response
    {
        // On récupère le projet auquel la tâche est associée.
        $project = $this->entityManagerService->getEntity($this->projectRepository, $projectId);

        // On récupère la tâche à mettre à jour.
        $task = $this->entityManagerService->getEntity($this->taskRepository, $taskId);

        // On crée le formulaire et on ajoute les employés associés au projet en option du formulaire.
        $form = $this->createForm(TaskType::class, $task, [
            'projectEmployees' => $project->getEmployees()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManagerService->save($task);

            return $this->redirectToRoute('app_tasks_show', ['projectId' => $projectId]);
        }

        return $this->render('tasks/edit_delete.html.twig', [
            'form' => $form,
            'taskId' => $taskId,
            'projectId' => $projectId
        ]);
    }

    /**
     * Suppression d'une tâche.
     */
    #[Route('/projet/{projectId}/tache/{taskId}/suppression', name: 'app_task_delete', requirements: ['projectId' => '\d+', 'taskId' => '\d+'], methods: ['POST'])]
    public function deleteTask(int $projectId, int $taskId): Response
    {
        $task = $this->entityManagerService->getEntity($this->taskRepository, $taskId);

        $this->entityManagerService->remove($task);

        return $this->redirectToRoute('app_tasks_show', ['projectId' => $projectId]);
    }
}
