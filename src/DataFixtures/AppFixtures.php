<?php

namespace App\DataFixtures;

use App\Factory\EmployeeFactory;
use App\Factory\ProjectFactory;
use App\Factory\StatusFactory;
use App\Factory\TaskFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création des employés.
        EmployeeFactory::createMany(3);

        // Création des projets.
        ProjectFactory::createOne([
            'name' => 'Site web Innovate',
        ]);

        ProjectFactory::createOne([
            'name' => 'Application NextGen',
        ]);

        // Création des statuts.
        $toDo = StatusFactory::createOne(['label' => 'To Do']);
        $doing = StatusFactory::createOne(['label' => 'Doing']);
        $done = StatusFactory::createOne(['label' => 'Done']);

        // Création des tâches.
        TaskFactory::createOne([
            'title' => 'Développer l\'interface utilisateur',
            'description' => 'Concevoir et coder les pages de l\'interface utilisateur.',
            'deadline' => (new \DateTime())->add(new \DateInterval('P14D')),
            'status' => $toDo
        ]);

        TaskFactory::createOne([
            'title' => 'Configurer le serveur de base de données',
            'description' => 'Configurer et sécuriser le serveur de base de données.',
            'deadline' => (new \DateTime())->add(new \DateInterval('P7D')),
            'status' => $toDo
        ]);

        TaskFactory::createOne([
            'title' => 'Créer les API RESTful',
            'description' => 'Développer des API pour la communication entre services.',
            'deadline' => (new \DateTime())->add(new \DateInterval('P21D')),
            'status' => $toDo
        ]);

        TaskFactory::createOne([
            'title' => 'Mettre en place l\'authentification utilisateur',
            'description' => 'Mettre en place un système d\'authentification sécurisé.',
            'deadline' => (new \DateTime())->add(new \DateInterval('P7D')),
            'status' => $doing
        ]);

        TaskFactory::createOne([
            'title' => 'Optimiser la performance du site',
            'description' => 'Optimiser les temps de chargement et les requêtes.',
            'deadline' => (new \DateTime())->add(new \DateInterval('P5D')),
            'status' => $doing
        ]);

        TaskFactory::createOne([
            'title' => 'Rédiger la documentation technique',
            'description' => 'Documenter le code et les processus de développement.',
            'deadline' => (new \DateTime())->add(new \DateInterval('P3D')),
            'status' => $doing
        ]);

        TaskFactory::createOne([
            'title' => 'Intégrer les tests automatisés',
            'description' => 'Ajouter des tests automatisés pour assurer la qualité.',
            'deadline' => (new \DateTime())->sub(new \DateInterval('P4D')),
            'status' => $done
        ]);

        TaskFactory::createOne([
            'title' => 'Déployer l\'application en production',
            'description' => 'Publier l\'application sur le serveur de production.',
            'deadline' => (new \DateTime())->sub(new \DateInterval('P10D')),
            'status' => $done
        ]);

        $manager->flush();
    }
}
