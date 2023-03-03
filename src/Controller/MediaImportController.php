<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MediaContent;
use App\Service\MediaContentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/media_import', name: 'api_')]
class MediaImportController extends AbstractController
{
    #[Route('/import_response', name: 'media_import_response', methods: ['GET', 'POST'])]
    public function mediaImportResponse(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

        return $this->json([
            '$_GET' => $_GET,
            '$_POST' => $_POST,
            '$_FILES' => $_FILES,
            '$request' => $request,
        ]);
    }

    #[Route('/single/{id}', name: 'media_import_single_post', defaults: ['id' => 0], methods: ['POST'])]
    public function mediaImportSingle(
        EntityManagerInterface $entityManager
    ): Response
    {
//        return $this->json([
//            '$_GET' => $_GET,
//            '$_POST' => $_POST,
//            '$_FILES' => $_FILES,
//        ]);

        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

//        return $this->json([
//            'request' => $request->request->count(),
//            'files' => $request->files->count(),
//            'content' => $request->getContent(),
//            '$_GET' => $_GET,
//            '$_POST' => $_POST,
//            '$_FILES' => $_FILES,
//        ]);

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

            return $this->json(
                [
                    (new MediaContentService)->writeContent(
                        $data,
                        new MediaContent(),
                        $entityManager
                    ) ?->getId()
                ]
            );
        } else {
            return $this->json([
                'success' => false,
                'error' => 'Empty POST data!',
            ]);
        }
    }
}
