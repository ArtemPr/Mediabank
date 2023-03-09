<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MediaDirectory;
use App\Repository\MediaContentRepository;
use App\Repository\MediaDirectoryRepository;
use App\Service\MediaDirectoryService;
use App\Service\QueryHelper;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/media_directory', name: 'api_')]
class MediaDirectoryController extends AbstractController
{


    use QueryHelper;

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/{id}', name: 'media_directory_post', methods: ['POST'])]
    public function addDirectory(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $data = (new MediaDirectoryService)->setData(
            $request,
            $entityManager
        );

        return $this->json(
            [
                (new MediaDirectoryService)->writeDirectory(
                    $data,
                    new MediaDirectory(),
                    $entityManager
                ) ?->getId()
            ]
        );
    }

    /**
     * @param MediaDirectoryRepository $mediaDirectoryRepository
     * @param MediaDirectoryService $mediaDirectoryService
     * @param MediaContentRepository $mediaContentRepository
     * @param $id
     * @return Response
     */
    #[Route('/{id}', name: 'media_directory_item_get', methods: ['GET'])]
    public function getDirectory(
        MediaDirectoryRepository $mediaDirectoryRepository,
        MediaDirectoryService $mediaDirectoryService,
        MediaContentRepository $mediaContentRepository,
        $id
    ): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

        if ((int)$id === 0) {
            $mediaDirectoryService->showTree($mediaDirectoryRepository, $mediaContentRepository);

            return $this->json(
                $mediaDirectoryService::$tree
            );
        }


        $param = [
            'id' => $id
        ];

        if (!empty($request->get('search'))) {
            $search = mb_strtolower(urldecode($request->get('search')));
            $param['search'] = $this->makeLikeParam(mb_strtolower($search));
        }

        if ($id == 999999) {
            $param['id'] = 1;
            $qb = $mediaDirectoryRepository->createQueryBuilder('mediaDirectory')
                ->leftJoin('mediaDirectory.mediaContents', 'mediaContents')->addSelect('mediaContents')
                ->where('mediaDirectory.id > :id');
            if (!empty($search)) {
                $qb->andWhere("LOWER(mediaContents.name) LIKE :search ESCAPE '!'");
            }
            $qb->setParameters(
                $param
            );
            $qb->setMaxResults(30);
            $result = $qb->getQuery()
                ->getResult(
                    AbstractQuery::HYDRATE_ARRAY
                );
            $tmp = [
                "id" => 1,
                "pid" => 0,
                "name" => "Общий рубрикатор",
                "order_number" => 3,
                "mediaContents" => []
            ];
            if (!empty($result)) {
                foreach ($result as $val) {
                    if (!empty($val['mediaContents'])) {
                        $tmp['mediaContents'] = array_merge($tmp['mediaContents'], $val['mediaContents']);
                    }
                }
            }
            $result = $tmp;
        } else {
            $qb = $mediaDirectoryRepository->createQueryBuilder('mediaDirectory')
                ->leftJoin('mediaDirectory.mediaContents', 'mediaContents')->addSelect('mediaContents')
                ->where('mediaDirectory.id = :id');
            if (!empty($search)) {
                $qb->andWhere("LOWER(mediaContents.name) LIKE :search ESCAPE '!'");
            }
            $qb->setParameters(
                $param
            );
            $qb->setMaxResults(30);
            $result = $qb->getQuery()
                ->getResult(
                    AbstractQuery::HYDRATE_ARRAY
                );
        }

        return $this->json(
            $result
        );
    }

    /**
     * @param Request $request
     * @param MediaDirectoryRepository $mediaDirectoryRepository
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return Response
     */
    #[Route('/{id}', name: 'media_directory_item_put', methods: ['PATCH'])]
    public function updateDirectory(
        Request $request,
        MediaDirectoryRepository $mediaDirectoryRepository,
        EntityManagerInterface $entityManager,
        $id
    ): Response
    {
        $data = (new MediaDirectoryService)->setData(
            $request,
            $entityManager
        );
        $mediaDirectory = $mediaDirectoryRepository->find($id);
        return $this->json(
            [
                (new MediaDirectoryService())->writeDirectory(
                    $data,
                    $mediaDirectory,
                    $entityManager
                ) ?->getId()
            ]
        );
    }


    /**
     * @param EntityManagerInterface $entityManager
     * @param MediaDirectoryRepository $mediaDirectoryRepository
     * @param $id
     * @return Response
     */
    #[Route('/{id}', name: 'media_directory_delete', methods: ['DELETE'])]
    public function deleteDirectory(
        EntityManagerInterface $entityManager,
        MediaDirectoryRepository $mediaDirectoryRepository,
        $id
    ): Response
    {
        $directory = $mediaDirectoryRepository->find((int)$id);
        if (null !== $directory) {
            $entityManager->remove($directory);
            $entityManager->flush();
            return $this->json(['success']);
        } else {
            return $this->json(['error']);
        }
    }

}