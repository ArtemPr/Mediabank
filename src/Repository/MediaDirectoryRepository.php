<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MediaDirectory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MediaDirectory>
 *
 * @method MediaDirectory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaDirectory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaDirectory[]    findAll()
 * @method MediaDirectory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaDirectoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaDirectory::class);
    }

    public function save(MediaDirectory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MediaDirectory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param $pid
     * @return int|null
     */
    public function getMaxOrderNumber($pid): ?int
    {
        $qb = $this->createQueryBuilder('mediaDirectory')
            ->select('MAX(mediaDirectory.order_number) AS number')
            ->where('mediaDirectory.pid = :pid')
            ->setParameters(
                [
                    'pid' => $pid
                ]
            );
        $query = $qb->getQuery();
        $result = $query->execute(
            hydrationMode: AbstractQuery::HYDRATE_ARRAY
        );

        if (null !== $result) {
            return $result['0']['number'];
        }

        return null;
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getDirectory(int $id): ?array
    {
        $qb = $this->createQueryBuilder('mediaDirectory')
            ->where('mediaDirectory.id = :id')
            ->setParameters(
                [
                    'id' => $id
                ]
            );

        return $qb->getQuery()
            ->getResult(
                AbstractQuery::HYDRATE_ARRAY
            );
    }
}
