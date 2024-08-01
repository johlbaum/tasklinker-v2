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
        $employees = EmployeeFactory::createMany(3);

        // Crée des projets avec des employés choisis aléatoirement.
        ProjectFactory::createMany(2, function () use ($employees) {
            return [
                'employees' => EmployeeFactory::randomRange(1, count($employees)),
            ];
        });

        StatusFactory::createOne(['label' => 'To Do']);
        StatusFactory::createOne(['label' => 'Doing']);
        StatusFactory::createOne(['label' => 'Done']);
        TaskFactory::createMany(8);

        $manager->flush();
    }
}
