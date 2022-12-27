<?php

namespace App\Repository;

use App\Entity\MediaContent;
use App\Entity\TextMediaContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TextMediaContent>
 *
 * @method TextMediaContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextMediaContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextMediaContent[]    findAll()
 * @method TextMediaContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextMediaContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextMediaContent::class);
    }

    public function save(TextMediaContent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TextMediaContent $entity, bool $flush = false): void
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
        $qb = $this->createQueryBuilder('textMediaContent')
            ->where('textMediaContent.media_content\ = :content')
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
