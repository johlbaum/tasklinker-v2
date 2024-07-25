<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    /**
     * Page projets
     */
    #[Route('/', name: 'app_home')]
    public function index(ProjectRepository $repository): Response
    {
        // On récupère les projets.
        $projects = $repository->findAll();
        if (!$projects) {
            throw $this->createNotFoundException('Less projets n\'existent pas');
        }

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }
}
