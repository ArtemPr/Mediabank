<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MediaContent;
use App\Entity\MediaDirectory;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
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
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function writeContent(
        array $data,
        MediaContent $content,
        EntityManagerInterface $entityManager
    ): ?MediaContent
    {
        extract($data);
        if (null !== $name && null !== $file_name && null !== $file && null !== $directory) {
            $type = 1;
            $content->setName($name);
            $content->setDirectory($directory);
            $content->setLink($file_name);
            $content->setDateCreate(new DateTime());
            $content->setDelete(false);
            $content->setUploadedUser(1);
            $content->setType(1);
            $entityManager->persist($content);
            $entityManager->flush();

            match ($type) {
                1 => $this->writeImageMediaContent((int)$content->getId(), $data),
                2 => $this->writeVideoMediaContent((int)$content->getId(), $data),
                3 => $this->writeTextMediaContent((int)$content->getId(), $data),
            };

            return $content;
        } else {
            return null;
        }
    }

    /**
     * @TODO Дописать
     * @param int $id
     * @param array $data
     * @return array|null
     */
    private function writeImageMediaContent(int $id, array $data): ?array
    {
        return [];
    }

    /**
     * @TODO дописать
     * @param int $id
     * @param array $data
     * @return array|null
     */
    private function writeVideoMediaContent(int $id, array $data): ?array
    {
        return [];
    }

    /**
     * @TODO дописать
     * @param int $id
     * @param array $data
     * @return array|null
     */
    private function writeTextMediaContent(int $id, array $data): ?array
    {
        return [];
    }
}
