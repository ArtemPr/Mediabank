<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MediaContent;
use App\Entity\MediaDirectory;
use App\Repository\MediaContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/media_content', name: 'api_')]
class MediaContentController extends AbstractController
{
    #[Route('/{id}', name: 'media_content_post', methods: ['POST'])]
    public function addContent(Request $request, EntityManagerInterface $entityManager, MediaContentRepository $mediaContentRepository): Response
    {
        $output = [];

        $name = $request->get('name');
        $file_name = $request->get('file_name');
        $file = $request->get('file');
        $directory = $request->get('directory');

        if (null !== $name && null !== $file_name && null !== $file && null !== $directory) {

            $directory = $entityManager->getRepository(MediaDirectory::class)->find((int)$directory);

            $content = new MediaContent();
            $content->setName($name);
            $content->setDirectory($directory);
            $content->setLink($file_name);
            $content->setDateCreate(new \DateTime());
            $content->setDelete(false);
            $content->setUploadedUser(1);
            $content->setType(1);

            $entityManager->persist($content);
            $entityManager->flush();

        } else {
            return $this->json(
                [
                    'errors' => 'Name, File and Directory is required parameter'
                ], 500
            );
        }

        return $this->json($output);
    }

    #[Route('/{id}', name: 'media_content_item_get', methods: ['GET'])]
    public function getContent($id): Response
    {
        return $this->json(['get']);
    }

    #[Route('/{id}', name: 'media_content_item_put', methods: ['PATCH'])]
    public function updateContent(MediaContentRepository $mediaContentRepository, $id): Response
    {
        return $this->json(['put']);
    }

    #[Route('/{id}', name: 'media_content_delete', methods: ['DELETE'])]
    public function deleteDontent(MediaContentRepository $mediaContentRepository, $id): Response
    {
        return $this->json(['delete']);
    }
}