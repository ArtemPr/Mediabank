<?php

namespace App\Repository;

use App\Entity\MediaContent;
use App\Entity\VideoMediaContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VideoMediaContent>
 *
 * @method VideoMediaContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoMediaContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoMediaContent[]    findAll()
 * @method VideoMediaContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoMediaContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoMediaContent::class);
    }

    public function save(VideoMediaContent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VideoMediaContent $entity, bool $flush = false): void
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
        $qb = $this->createQueryBuilder('videoMediaContent')
            ->where('videoMediaContent.media_content = :content')
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
