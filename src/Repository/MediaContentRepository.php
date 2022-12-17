<?php

namespace App\Repository;

use App\Entity\MediaContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MediaContent>
 *
 * @method MediaContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaContent[]    findAll()
 * @method MediaContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaContent::class);
    }

    public function save(MediaContent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MediaContent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllContent(): ?array
    {
        return [];
    }
}
