<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\SignUpType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\EntityManagerService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    public function __construct(
        private EntityManagerService $entityManagerService,
    ) {}

    /**
     * Page de bienvenu.
     */
    #[Route('/auth', name: 'app_auth')]
    public function welcomePage(): Response
    {
        return $this->render('auth/welcome.html.twig');
    }

    /**
     * Formulaire d'inscription.
     */
    #[Route('/signup', name: 'app_sign_up')]
    public function signUpPage(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        // On crée un objet Employee.
        $employee = new Employee();

        // On enregistre le statut de l'employé.
        $employee->setStatus('CDI');

        // On enregistre la date d'entrée de l'employé.
        $employee->setEntryDate(new \DateTime());

        // On génère le formulaire.
        $form = $this->createForm(SignUpType::class, $employee);

        // On récupère les données de la requête (les champs du formulaire) et on les associe aux propriétés de l'objet.
        $form->handleRequest($request);

        // On vérifie si le formulaire a été soumis et si les données respectent les contraintes de validations.
        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère le mot de passe en clair.
            $plaintextPassword = $employee->getPassword();

            // On hashe le mot de passe.
            $hashedPassword = $passwordHasher->hashPassword($employee, $plaintextPassword);
            $employee->setPassword($hashedPassword);

            // On sauvegarde l'utilisateur.
            $this->entityManagerService->save($employee);
        }

        return $this->render('auth/signup.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * Formulaire de connexion.
     */
    #[Route(path: '/login', name: 'app_sign_in')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/signin.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
