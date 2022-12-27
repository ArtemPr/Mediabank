<?php

namespace App\Repository;

use App\Entity\ImgMediaContent;
use App\Entity\MediaContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImgMediaContent>
 *
 * @method ImgMediaContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImgMediaContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImgMediaContent[]    findAll()
 * @method ImgMediaContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImgMediaContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImgMediaContent::class);
    }

    public function save(ImgMediaContent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ImgMediaContent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param MediaContent $content
     * @return array|null
     */
    public function getContent(MediaContent $content): ?array
    {
        $qb = $this->createQueryBuilder('imgMediaContent')
            ->where('imgMediaContent.media_content = :content')
            ->setParameters(
                [
                    'content' => $content
                ]
            );
        $out = $qb->getQuery()
            ->getResult(
                AbstractQuery::HYDRATE_ARRAY
            );
        return $out[0] ?? null;
    }
}
