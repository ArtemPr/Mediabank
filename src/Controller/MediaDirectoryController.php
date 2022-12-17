<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\MediaDirectoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/media_directory')]
class MediaDirectoryController extends AbstractController
{
    #[Route('/dir', name: 'app_media_content_post', methods: ['POST'])]
    public function addContent(Request $request, EntityManagerInterface $entityManager, MediaDirectoryRepository $mediaDirectoryRepository): Response
    {
        return $this->json(['post']);
    }
}