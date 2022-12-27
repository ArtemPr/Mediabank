<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MediaContent;
use App\Repository\MediaContentRepository;
use App\Service\MediaContentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/media_content', name: 'api_')]
class MediaContentController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param MediaContentRepository $mediaContentRepository
     * @return Response
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    #[Route('/{id}', name: 'media_content_post', methods: ['POST'])]
    public function addContent(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $data = (new MediaContentService)->setData(
            $request,
            $entityManager
        );

        return $this->json(
            [
                (new MediaContentService)->writeContent(
                    $data,
                    new MediaContent(),
                    $entityManager
                ) ?->getId()
            ]
        );
    }

    /**
     * @param MediaContentRepository $mediaContentRepository
     * @param $id
     * @return Response
     */
    #[Route('/{id}', name: 'media_content_item_get', methods: ['GET'])]
    public function getContent(
        MediaContentRepository $mediaContentRepository,
        SerializerInterface $serializer,
        $id
    ): Response
    {
        $media = $mediaContentRepository->getContent((int)$id);

        return $this->json(
            $media
        );
    }

    /**
     * @param Request $request
     * @param MediaContentRepository $mediaContentRepository
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return Response
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    #[Route('/{id}', name: 'media_content_item_put', methods: ['PATCH'])]
    public function updateContent(
        Request $request,
        MediaContentRepository $mediaContentRepository,
        EntityManagerInterface $entityManager,
        $id
    ): Response
    {
        $data = (new MediaContentService)->setData(
            $request,
            $entityManager
        );
        $mediaContent = $mediaContentRepository->find($id);
        return $this->json(
            [
                (new MediaContentService)->writeContent(
                    $data,
                    $mediaContent,
                    $entityManager
                ) ?->getId()
            ]
        );
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param MediaContentRepository $mediaContentRepository
     * @param $id
     * @return Response
     */
    #[Route('/{id}', name: 'media_content_delete', methods: ['DELETE'])]
    public function deleteContent(
        EntityManagerInterface $entityManager,
        MediaContentRepository $mediaContentRepository,
        $id
    ): Response
    {
        $content = $mediaContentRepository->find($id);
        if (null !== $content) {
            $entityManager->remove($content);
            $entityManager->flush();
            return $this->json(['success']);
        } else {
            return $this->json(['error']);
        }
    }
}
