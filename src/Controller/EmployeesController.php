<?php

namespace App\Controller;

use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeesController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private EmployeeRepository $employeeRepository,
    ) {
    }

    /**
     * Affichage des employés.
     */
    #[Route('/employes', name: 'app_employee', methods: ['GET'])]
    public function showEmployees(): Response
    {
        $employees = $this->employeeRepository->findAll();

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
        ]);
    }

    /**
     * Mise à jour d'un employé.
     */
    #[Route('/employe/{employeeId}/edition', name: 'app_employee_edit', requirements: ['employeeId' => '\d+', 'taskId' => '\d+'], methods: ['GET', 'POST'])]
    public function editEmpoyee(int $employeeId, Request $request): Response
    {
        $employee = $this->employeeRepository->find($employeeId);
        if (!$employee) {
            throw $this->createNotFoundException('L\'employé n\'existe pas');
        }

        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($employee);
            $this->manager->flush();

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
    #[Route('/employe/{employeeId}/suppression', name: 'app_employee_delete', requirements: ['employeeId' => '\d+', 'taskId' => '\d+'], methods: ['POST', 'GET'])]
    public function deleteEmployee(int $employeeId): Response
    {
        $employee = $this->employeeRepository->find($employeeId);
        if (!$employee) {
            throw $this->createNotFoundException('L\'employé n\'existe pas');
        }

        $this->manager->remove($employee);
        $this->manager->flush();

        return $this->redirectToRoute('app_employee');
    }
}
