<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MediaDirectory;
use App\Repository\MediaDirectoryRepository;
use App\Service\MediaDirectoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/media_directory', name: 'api_')]
class MediaDirectoryController extends AbstractController
{
    /**
     * @TODO Дописать функционал
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
     * @param $id
     * @return Response
     */
    #[Route('/{id}', name: 'media_directory_item_get', methods: ['GET'])]
    public function getDirectory(
        MediaDirectoryRepository $mediaDirectoryRepository,
        $id
    ): Response
    {
        return $this->json(
            $mediaDirectoryRepository->find($id)
        );
    }

    /**
     * @TODO Добавить функционал
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