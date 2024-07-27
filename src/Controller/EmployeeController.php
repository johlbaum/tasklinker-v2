<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeController extends AbstractController
{
    #[Route('/employee', name: 'app_employee')]
    public function showEmployees(EmployeeRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
        ]);
    }
}
