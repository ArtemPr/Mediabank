<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MediaContent;
use App\Service\MediaContentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/media_import', name: 'api_')]
class MediaImportController extends AbstractController
{
    #[Route('/single/{id}', name: 'media_import_single_post', defaults: ['id' => 0], methods: ['POST'])]
    public function mediaImportSingle(
        EntityManagerInterface $entityManager
    ): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

        if (!empty($request->request)) {
            $data = (new MediaContentService)->setData(
                $request,
                $entityManager
            );

            if (empty($data)) {
                return $this->json([
                    'success' => false,
                    'error' => 'Empty processed data!',
                ]);
            }

            return $this->json([
                'success' => true,
                'id' => (new MediaContentService)->writeContent(
                    $data,
                    new MediaContent(),
                    $entityManager
                ) ?->getId()
            ]);
        } else {
            return $this->json([
                'success' => false,
                'error' => 'Empty POST data!',
            ]);
        }
    }
}
