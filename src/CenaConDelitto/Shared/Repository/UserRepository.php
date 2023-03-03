<?php

namespace CenaConDelitto\Shared\Repository;

use CenaConDelitto\Shared\Entity\User;
use CenaConDelitto\Shared\Exception\EntityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $user): void
    {
        $this->getEntityManager()->persist($user);
    }

    public function save(User $user): void
    {
        $this->add($user);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function get(string $uuid): null|User
    {
        return $this->getByAttribute('uuid', $uuid);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getByUsername(string $username): null|User
    {
        return $this->getByAttribute('username', $username);
    }

    /**
     * @throws NonUniqueResultException
     */
    private function getByAttribute(string $attribute, string $search): null|User
    {
        /* @phpstan-ignore-next-line */
        return $this->createQueryBuilder('u')
            ->andWhere(sprintf('u.%s = :search', $attribute))
            ->setParameter('search', $search)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
