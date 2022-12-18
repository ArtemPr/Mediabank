<?php

namespace App\Service;

use App\Entity\MediaContent;
use App\Entity\MediaDirectory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class MediaContentService
{
    /**
     * Формирование данных для записи
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return array|null
     */
    // @TODO дописать загрузку файла
    public function setData(
        Request $request,
        EntityManagerInterface $entityManager
    ): ?array
    {
        if ($request->request->has('name') && $request->request->has('directory')) {
            $data['name'] = $request->get('name');
            $data['file_name'] = $request->get('file_name') ?? null;
            $data['file'] = $request->get('file') ?? null;
            $directory = $request->get('directory');
            if (null !== $directory) {
                $data['directory'] = $entityManager->getRepository(MediaDirectory::class)->find($directory) ?? null;
            }
            return $data;
        }
        return null;
    }

    /**
     * Записать данные
     *
     * @param array $data
     * @param MediaContent $content
     * @param EntityManager $entityManager
     * @return MediaContent|null
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function writeContent(
        array $data,
        MediaContent $content,
        EntityManagerInterface $entityManager
    ): ?MediaContent
    {
        extract($data);
        if (null !== $name && null !== $file_name && null !== $file && null !== $directory) {
            $content->setName($name);
            $content->setDirectory($directory);
            $content->setLink($file_name);
            $content->setDateCreate(new \DateTime());
            $content->setDelete(false);
            $content->setUploadedUser(1);
            $content->setType(1);
            $entityManager->persist($content);
            $entityManager->flush();
            return $content;
        } else {
            return null;
        }
    }
}
