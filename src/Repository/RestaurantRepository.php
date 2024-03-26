<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Restaurant>
 *
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }

    public function save(Restaurant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Restaurant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function randomRestaurant()
    {
        $entityManager = $this->getEntityManager();
        $dql = "
            SELECT r
            FROM App\Entity\Restaurant r
            ORDER BY RANDOM()
        ";

        $query = $entityManager->createQuery($dql)->setMaxResults(1);

        return $query->getResult();
    }

    public function sort(string $search = null, SearchData $searchData): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->select('r, AVG(COALESCE(rt.rating, 0)) as avgRating')
            ->leftJoin('r.ratings', 'rt')
            ->leftJoin('r.city', 'c')
            ->leftJoin('r.food', 'f')
            ->groupBy('r.id');

        if ($search) {
            $queryBuilder->andWhere('ILIKE(r.name, :searchTerm) = TRUE')
                ->setParameter('searchTerm', '%'.$search.'%');
        }

        if (!empty($searchData->city)) {
            $queryBuilder = $queryBuilder
                ->andWhere('c.id IN (:city)')
                ->setParameter('city', $searchData->city);
        }

        if (!empty($searchData->food)) {
            $queryBuilder = $queryBuilder
                ->andWhere('f.id IN (:food)')
                ->setParameter('food', $searchData->food);
        }

        $queryBuilder->addOrderBy('avgRating', 'DESC');

        $restaurants = $queryBuilder->getQuery()->getResult();

        return $restaurants;
    }
}
