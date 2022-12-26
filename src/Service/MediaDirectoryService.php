<?php

namespace App\Service;

use App\Entity\MediaDirectory;
use App\Repository\MediaContentRepository;
use App\Repository\MediaDirectoryRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class MediaDirectoryService
{
    static ?array $tree = [];

    public function setData(
        Request $request,
        EntityManagerInterface $entityManager
    )
    {
        if ($request->request->has('name') && $request->request->has('directory')) {
            $data['name'] = $request->get('name');
            $directory = $request->get('directory');
            if (null !== $directory) {
                $data['directory'] = $entityManager->getRepository(MediaDirectory::class)->find($directory) ?? null;
            }
            return $data;
        }
        return null;
    }

    /**
     * @param array $data
     * @param MediaDirectory $directory
     * @param EntityManagerInterface $entityManager
     * @return MediaDirectory|null
     */
    public function writeDirectory(
        array $data,
        MediaDirectory $directory,
        EntityManagerInterface $entityManager
    ): ?MediaDirectory
    {
        extract($data);
        if (null !== $name) {
            $orderNumber = $entityManager->getRepository(MediaDirectory::class)
                ->getMaxOrderNumber($data['pid']) ?? 1;
            $directory->setName($name);
            $directory->setPid($data['pid'] ?? 0);
            $directory->setOrderNumber(++$orderNumber);
            $entityManager->persist($directory);
            $entityManager->flush();
            return $directory;
        } else {
            return null;
        }
    }

    public function showTree(
        MediaDirectoryRepository $mediaDirectoryRepository,
        MediaContentRepository $mediaContentRepository
    ): void
    {
        $qb = $mediaDirectoryRepository->createQueryBuilder('mediaDirectory')
            ->orderBy('mediaDirectory.order_number','ASC');
        $out = $qb->getQuery()
            ->getResult();

        foreach ($out as $item) {

            $qb_item = $mediaContentRepository->createQueryBuilder('mediaContent')
                ->select('COUNT(mediaContent.id) AS cid')
                ->leftJoin('mediaContent.directory', 'directory')
                ->where('directory.id = :id')
                ->setParameters(
                    [
                        'id' => $item->getId()
                    ]
                );
            $count = $qb_item->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);


            self::$tree[$item->getPid()][] =
                [
                    'id' => $item->getId(),
                    'name' => $item->getName() . ' ('.$count['0']['cid'].')'
                ];
        }
    }

    private function getLevel(
        MediaDirectoryRepository $mediaDirectoryRepository,
        ?int $pid = 0,
        ?int $level = 0
    )
    {
        $level = $level === 1 ? $level : ++$level;
        $qb = $mediaDirectoryRepository->createQueryBuilder('mediaDirectory')
            ->where('mediaDirectory.pid = :pid AND mediaDirectory.level = :level')
            ->setParameters(
                [
                    'pid' => $pid,
                    'level' => $level
                ]
            );

        return $qb->getQuery()
            ->getResult(
                AbstractQuery::HYDRATE_ARRAY
            );
    }
}