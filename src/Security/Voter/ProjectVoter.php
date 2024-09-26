<?php

namespace App\Security\Voter;

use App\Entity\Employee;
use App\Entity\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjectVoter extends Voter
{
    /**
     *  On définit si le Voteur doit voter ou non sur cette demande d'autorisation.
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return 'project.is_creator' === $attribute && $subject instanceof Project;
    }

    /**
     * On décrit les conditions métiers qui conditionnent l'autorisation.
     * Cette méthode est appelée uniquement si la méthode 'supports' retourne 'true'.
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        // On récupère l'utilisateur connecté à partir du token.
        $user = $token->getUser();

        // On vérifie que l'utilisateur est bien connecté et est une instance de UserInterface.
        if (!$user instanceof UserInterface) {
            return false;
        }

        // On vérifie que l'utilisateur est bien une instance d'Employee.
        if (!$user instanceof Employee) {
            return false;
        }

        // Si l'utilisateur est associé au projet ou qu'il a le rôle d'administrateur, il a l'autorisation.
        if ($subject->getEmployees()->contains($user) || $user->isAdmin()) {
            return true;
        }

        // Si aucune des conditions n'est remplie, l'utilisateur n'a pas l'autorisation.
        return false;
    }
}
