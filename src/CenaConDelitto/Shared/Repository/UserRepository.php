<?php

namespace CenaConDelitto\Shared\Repository;

use CenaConDelitto\Shared\Entity\User;
use CenaConDelitto\Shared\Exception\EntityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function get(string $uuid): User
    {
        /** @var User|null $result */
        $result = $this->createQueryBuilder('u')
            ->andWhere('u.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$result) {
            throw EntityNotFoundException::crea(self::class, $uuid);
        }

        return $result;
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function getByUsername(string $username): User|null
    {
        /* @phpstan-ignore-next-line */
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
