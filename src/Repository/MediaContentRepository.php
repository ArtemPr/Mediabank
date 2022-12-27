<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ImgMediaContent;
use App\Entity\MediaContent;
use App\Entity\TextMediaContent;
use App\Entity\VideoMediaContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
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

    /**
     * @param int $id
     * @return array|null
     */
    public function getContent(int $id): ?array
    {
        $qb = $this->createQueryBuilder('mediaContent')
            ->where('mediaContent.id = :id')
            ->leftJoin('mediaContent.directory', 'directory')->addSelect('directory')
            ->setParameters([
                'id' => $id
            ])
        ;

        $content = $qb->getQuery()
            ->getResult(
                AbstractQuery::HYDRATE_ARRAY
            );

        $content = $content[0] ?? null;

        if (null !== $content) {
            $content['info'] = match ($content['type']) {
                1 => $this->getEntityManager()->getRepository(ImgMediaContent::class)->getContent($this->find($id)),
                2 => $this->getEntityManager()->getRepository(VideoMediaContent::class)->getContent($this->find($id)),
                3 => $this->getEntityManager()->getRepository(TextMediaContent::class)->getContent($this->find($id)),
            };
        }
        return $content;
    }
}
