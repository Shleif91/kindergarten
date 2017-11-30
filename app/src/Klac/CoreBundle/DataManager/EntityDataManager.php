<?php

namespace Klac\CoreBundle\DataManager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

abstract class EntityDataManager
{
    const ENTITY_NAME = '';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * EntityService constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->repository = $this->em->getRepository(static::ENTITY_NAME);
    }

    /**
     * @param $entity
     */
    public function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * @param $entity
     */
    public function delete($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}