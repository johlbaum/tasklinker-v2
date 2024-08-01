<?php

namespace App\Service;

class AvatarService
{
    /**
     * Génère un avatar composé de la première lettre du prénom et de la première lettre du nom de l'employé.
     */
    private function generateAvatar(string $firstName, string $lastName): string
    {
        return strtoupper($firstName[0] . $lastName[0]);
    }

    /**
     * Attribue des avatars pour les employés associés à un projet.
     */
    public function setAvatarsForProjectEmployees(array $projectEmployees): void
    {
        foreach ($projectEmployees as $projectEmployee) {
            $avatar = $this->generateAvatar($projectEmployee->getFirstName(), $projectEmployee->getLastName());
            $projectEmployee->setAvatar($avatar);
        }
    }

    /**
     * Attribue des avatars pour les employés associés aux tâches.
     */
    public function setAvatarsForTaskEmployees(array $tasks): void
    {
        foreach ($tasks as $task) {
            if ($task->getEmployee() !== null) {
                $avatar = $this->generateAvatar(
                    $task->getEmployee()->getFirstName(),
                    $task->getEmployee()->getLastName()
                );
                $task->getEmployee()->setAvatar($avatar);
            }
        }
    }

    /**
     * Attribue des avatars pour tous les employés.
     */
    public function setAvatarsForAllEmployees(array $employees): void
    {
        foreach ($employees as $employee) {
            $avatar = $this->generateAvatar($employee->getFirstName(), $employee->getLastName());
            $employee->setAvatar($avatar);
        }
    }
}
