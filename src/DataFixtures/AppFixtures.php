<?php

namespace App\DataFixtures;

use App\Entity\Status;
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
        EmployeeFactory::createMany(3);
        ProjectFactory::createMany(2);
        StatusFactory::createMany(3);
        TaskFactory::createMany(8);

        $manager->flush();
    }
}