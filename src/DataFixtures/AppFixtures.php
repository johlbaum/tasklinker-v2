<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Task;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use \DateTime;
use \DateInterval;

class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Création des statuts
        $todo = new Status();
        $todo->setLabel('To Do');
        $manager->persist($todo);

        $doing = new Status();
        $doing->setLabel('Doing');
        $manager->persist($doing);

        $done = new Status();
        $done->setLabel('Done');
        $manager->persist($done);

        // Création des employés
        $employe1 = new Employee();
        $employe1->setLastName('Verdier')
            ->setFirstName('Thomas')
            ->setEmail('thomas.verdier@hotmail.fr')
            ->setStatus('CDI')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($employe1, 'thomas'))
            ->setEntryDate(new DateTime('2019-06-14'));
        $manager->persist($employe1);

        $employe2 = new Employee();
        $employe2->setLastName('Tournon')
            ->setFirstName('Maxime')
            ->setEmail('maxime.tournon@hotmail.fr')
            ->setStatus('CDD')
            ->setPassword($this->hasher->hashPassword($employe2, 'maxime'))
            ->setEntryDate(new DateTime('2022-09-01'));
        $manager->persist($employe2);

        $employe3 = new Employee();
        $employe3->setLastName('Motard')
            ->setFirstName('Nathalie')
            ->setEmail('nathalie.motard@hotmail.fr')
            ->setPassword($this->hasher->hashPassword($employe3, 'nathalie'))
            ->setStatus('Freelance')
            ->setEntryDate(new DateTime('2021-12-20'));
        $manager->persist($employe3);

        // Création des projets
        $projet1 = new Project();
        $projet1
            ->setName('WebAgency')
            ->setArchive(false)
            ->addEmployee($employe1)
            ->addEmployee($employe2);
        $manager->persist($projet1);

        $projet2 = new Project();
        $projet2
            ->setName('E-Commerce Plus')
            ->setArchive(true)
            ->addEmployee($employe2)
            ->addEmployee($employe3);
        $manager->persist($projet2);

        $projet3 = new Project();
        $projet3
            ->setName('Portfolio Creator')
            ->setArchive(false)
            ->addEmployee($employe1)
            ->addEmployee($employe3);
        $manager->persist($projet3);

        // Création des tâches
        $tache0 = new Task();
        $tache0->setTitle('Création du header responsive')
            ->setDescription('Développer le header en utilisant des techniques responsive pour les différentes tailles d\'écran.')
            ->setStatus($done)
            ->setEmployee($employe2)
            ->setProject($projet1)
            ->setDeadline((new DateTime())->sub(new DateInterval('P7D')));
        $manager->persist($tache0);

        $tache1 = new Task();
        $tache1->setTitle('Mise en place du système de routage')
            ->setDescription('Implémenter un système de routage dynamique pour gérer les différentes pages du site.')
            ->setStatus($done)
            ->setEmployee($employe1)
            ->setProject($projet1);
        $manager->persist($tache1);

        $tache2 = new Task();
        $tache2->setTitle('Intégration de l\'API de paiement')
            ->setDescription('Intégrer et configurer l\'API Stripe pour permettre les paiements en ligne.')
            ->setStatus($doing)
            ->setEmployee($employe2)
            ->setDeadline((new DateTime())->add(new DateInterval('P4D')))
            ->setProject($projet1);
        $manager->persist($tache2);

        $tache3 = new Task();
        $tache3->setTitle('Gestion des utilisateurs et authentification')
            ->setDescription('Mettre en place un système de gestion des utilisateurs avec inscription et authentification.')
            ->setStatus($doing)
            ->setEmployee($employe1)
            ->setDeadline((new DateTime())->sub(new DateInterval('P28D')))
            ->setProject($projet2);
        $manager->persist($tache3);

        $tache4 = new Task();
        $tache4->setTitle('Déploiement de la plateforme sur un serveur cloud')
            ->setDescription('Préparer et déployer l\'application sur AWS avec configuration des services.')
            ->setStatus($todo)
            ->setEmployee($employe1)
            ->setDeadline((new DateTime())->sub(new DateInterval('P13D')))
            ->setProject($projet3);
        $manager->persist($tache4);

        $tache5 = new Task();
        $tache5->setTitle('Création des wireframes')
            ->setDescription('Concevoir les wireframes des principales pages du site sur Figma.')
            ->setStatus($doing)
            ->setEmployee($employe3)
            ->setDeadline((new DateTime())->sub(new DateInterval('P18D')))
            ->setProject($projet3);
        $manager->persist($tache5);

        $tache6 = new Task();
        $tache6->setTitle('Intégration du contenu SEO')
            ->setDescription('Intégrer du contenu optimisé pour le SEO, y compris les balises meta, alt et title.')
            ->setStatus($todo)
            ->setEmployee($employe1)
            ->setDeadline((new DateTime())->sub(new DateInterval('P13D')))
            ->setProject($projet1);
        $manager->persist($tache6);

        $tache7 = new Task();
        $tache7->setTitle('Optimisation des performances web')
            ->setDescription('Optimiser les temps de chargement en compressant les images et en réduisant les scripts JS.')
            ->setStatus($todo)
            ->setEmployee($employe1)
            ->setDeadline((new DateTime())->sub(new DateInterval('P35D')))
            ->setProject($projet3);
        $manager->persist($tache7);

        $manager->flush();
    }
}
