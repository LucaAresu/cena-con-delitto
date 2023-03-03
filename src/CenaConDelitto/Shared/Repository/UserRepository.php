<?php

namespace CenaConDelitto\Shared\Repository;

use CenaConDelitto\Shared\Entity\User;
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

    public function get(string $uuid): null|User
    {
        return $this->findOneBy(['uuid' => $uuid]);
    }

    public function getByUsername(string $username): null|User
    {
        return $this->findOneBy(['username' => $username]);
    }
}
