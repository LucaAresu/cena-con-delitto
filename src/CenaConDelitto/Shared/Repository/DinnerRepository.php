<?php

namespace CenaConDelitto\Shared\Repository;

use CenaConDelitto\Shared\Entity\Dinner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Dinner>
 *
 * @method Dinner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dinner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dinner[]    findAll()
 * @method Dinner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DinnerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dinner::class);
    }

    public function add(Dinner $dinner): void
    {
        $this->getEntityManager()->persist($dinner);
    }

    public function save(Dinner $dinner): void
    {
        $this->add($dinner);
        $this->getEntityManager()->flush();
    }

    public function remove(Dinner $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function get(Uuid $uuid): null|Dinner
    {
        return $this->findOneBy(['uuid' => $uuid]);
    }

    public function getByName(string $name): null|Dinner
    {
        return $this->findOneBy(['name' => $name]);
    }
}
