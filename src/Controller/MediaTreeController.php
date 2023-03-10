<?php

namespace App\Controller;

use App\Repository\MediaContentRepository;
use App\Repository\MediaDirectoryRepository;
use App\Service\MediaDirectoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaTreeController extends AbstractController
{
    #[Route('/api/media_tree', name: 'api_tree')]
    public function getTree(
        MediaDirectoryRepository $mediaDirectoryRepository,
        MediaDirectoryService $mediaDirectoryService,
        MediaContentRepository $mediaContentRepository
    ): Response
    {
        $mediaDirectoryService->showTree($mediaDirectoryRepository, $mediaContentRepository);
        return $this->json(
            $mediaDirectoryService::$tree
        );
    }
}