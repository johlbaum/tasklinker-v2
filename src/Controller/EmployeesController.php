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
    /**
     * Affichage des employés.
     */
    #[Route('/employes', name: 'app_employee')]
    public function showEmployees(EmployeeRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
        ]);
    }

    /**
     * Mise à jour d'un employé.
     */
    #[Route('/employe/{employeeId}/edition', name: 'app_employee_edit')]
    public function editEmpoyee(int $employeeId, EmployeeRepository $employeeRepository, Request $request, EntityManagerInterface $manager): Response
    {
        $employee = $employeeRepository->find($employeeId);

        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($employee);
            $manager->flush();

            return $this->redirectToRoute('app_employee');
        }

        return $this->render('employee/edit.html.twig', [
            'form' => $form,
            'employee' => $employee
        ]);
    }
}
