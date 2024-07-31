<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntityManagerService
{
    public function __construct(
        private EntityManagerInterface $manager
    ) {
    }

    public function save(object $entity): void
    {
        $this->manager->persist($entity);
        $this->manager->flush();
    }

    public function remove(object $entity): void
    {
        $this->manager->remove($entity);
        $this->manager->flush();
    }

    public function getEntity(ObjectRepository $repository, int $id): object
    {
        $entity = $repository->find($id);
        if (!$entity) {
            throw new NotFoundHttpException('Entity not found');
        }

        return $entity;
    }
}
