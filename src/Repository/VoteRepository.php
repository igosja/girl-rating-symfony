<?php

namespace App\Repository;

use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vote>
 *
 * @method Vote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vote[]    findAll()
 * @method Vote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    public function save(Vote $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Vote $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return float|int|mixed|string
     */
    public function findByFilters(array $criteria = [], array $orderBy = null, int $limit = null, int $offset = null): mixed
    {
        $query = $this->createQueryBuilder('v');

        $parameters = [];
        foreach ($criteria as $key => $value) {
            if ('' === $value) {
                continue;
            }

            if ('id' === $key) {
                $query->andWhere('v.id = :id');
                $parameters[] = new Parameter('id', $value, Types::INTEGER);
            } elseif ('girl_one' === $key) {
                $query->andWhere('v.girl_one = :girl_one');
                $parameters[] = new Parameter('girl_one', $value, Types::INTEGER);
            } elseif ('girl_two' === $key) {
                $query->andWhere('v.girl_two = :girl_two');
                $parameters[] = new Parameter('girl_two', $value, Types::INTEGER);
            } elseif ('girl_winner' === $key) {
                $query->andWhere('v.girl_winner = :girl_winner');
                $parameters[] = new Parameter('girl_winner', $value, Types::INTEGER);
            }
        }

        if ($parameters) {
            $query->setParameters(new ArrayCollection($parameters));
        }

        if ($orderBy) {
            $query->addOrderBy('v.' . $orderBy['sort'], $orderBy['order']);
        }

        return $query
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
