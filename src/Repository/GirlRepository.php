<?php

namespace App\Repository;

use App\Entity\Girl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Girl>
 *
 * @method Girl|null find($id, $lockMode = null, $lockVersion = null)
 * @method Girl|null findOneBy(array $criteria, array $orderBy = null)
 * @method Girl[]    findAll()
 * @method Girl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GirlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Girl::class);
    }

    public function save(Girl $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Girl $entity, bool $flush = false): void
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
        $query = $this->createQueryBuilder('g');

        $parameters = [];
        foreach ($criteria as $key => $value) {
            if ('' === $value) {
                continue;
            }

            if ('id' === $key) {
                $query->andWhere('g.id = :id');
                $parameters[] = new Parameter('id', $value, Types::INTEGER);
            } elseif ('name' === $key) {
                $query->andWhere('g.name LIKE :name');
                $parameters[] = new Parameter('name', '%' . $value . '%', Types::STRING);
            }
        }

        if ($parameters) {
            $query->setParameters(new ArrayCollection($parameters));
        }

        if ($orderBy) {
            $query->addOrderBy('g.' . $orderBy['sort'], $orderBy['order']);
        }

        return $query
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
