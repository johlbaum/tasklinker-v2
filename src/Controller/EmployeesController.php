<?php

namespace App\Controller;

use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\EntityManagerService;
use App\Service\AvatarService;

class EmployeesController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private EmployeeRepository $employeeRepository,
        private EntityManagerService $entityManagerService,
        private AvatarService $avatarService
    ) {}

    /**
     * Affichage des employés.
     */
    #[Route('/employes', name: 'app_employee', methods: ['GET'])]
    public function showEmployees(): Response
    {
        $employees = $this->employeeRepository->findAll();

        // On génère les avatars des employés.
        $this->avatarService->setAvatarsForAllEmployees($employees);

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
        ]);
    }

    /**
     * Mise à jour d'un employé.
     */
    #[Route('/employe/{employeeId}/edition', name: 'app_employee_edit', requirements: ['employeeId' => '\d+'], methods: ['GET', 'POST'])]
    public function editEmpoyee(int $employeeId, Request $request): Response
    {
        $employee = $this->entityManagerService->getEntity($this->employeeRepository, $employeeId);

        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManagerService->save($employee);

            return $this->redirectToRoute('app_employee');
        }

        return $this->render('employee/edit.html.twig', [
            'form' => $form,
            'employee' => $employee
        ]);
    }

    /**
     * Suppression d'un employé.
     */
    #[Route('/employe/{employeeId}/suppression', name: 'app_employee_delete', requirements: ['employeeId' => '\d+'], methods: ['POST', 'GET'])]
    public function deleteEmployee(int $employeeId): Response
    {
        $employee = $this->entityManagerService->getEntity($this->employeeRepository, $employeeId);

        $this->entityManagerService->remove($employee);

        return $this->redirectToRoute('app_employee');
    }
}
