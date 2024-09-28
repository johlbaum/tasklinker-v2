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
     * Page de bienvenue.
     */
    #[Route('/auth', name: 'app_auth', methods: ['GET'])]
    public function welcomePage(): Response
    {
        return $this->render('auth/welcome.html.twig');
    }

    /**
     * Formulaire d'inscription.
     */
    #[Route('/signup', name: 'app_sign_up', methods: ['GET', 'POST'])]
    public function signUpPage(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        // On crée un objet Employee.
        $employee = new Employee();

        // On enregistre le statut de l'employé.
        $employee->setStatus('CDI');

        // On enregistre la date d'entrée de l'employé à la date du jour.
        $employee->setEntryDate(new \DateTime());

        // On génère le formulaire.
        $form = $this->createForm(SignUpType::class, $employee);

        // On récupère les données du formulaire et les associe aux propriétés de l'objet Employee.
        $form->handleRequest($request);

        // On vérifie si le formulaire a été soumis et si les données respectent les contraintes de validation.
        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère le mot de passe en clair.
            $plaintextPassword = $employee->getPassword();

            // On hashe le mot de passe.
            $hashedPassword = $passwordHasher->hashPassword($employee, $plaintextPassword);
            $employee->setPassword($hashedPassword);

            // On sauvegarde l'utilisateur.
            $this->entityManagerService->save($employee);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('auth/signup.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * Formulaire de connexion.
     */
    #[Route(path: '/login', name: 'app_sign_in', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // On récupère l'erreur d'authentification qui s'est produite lors de la dernière tentative de connexion si elle existe.
        $error = $authenticationUtils->getLastAuthenticationError();

        // On récupère le dernier nom d'utilisateur (ou email) saisi par l'utilisateur lors de sa tentative de connexion.
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/signin.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Gestion de la déconnexion.
     */
    #[Route(path: '/logout', name: 'app_logout', methods: ['POST', 'GET'])]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
