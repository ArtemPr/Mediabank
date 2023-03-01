<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ImgMediaContent;
use App\Entity\MediaContent;
use App\Entity\MediaDirectory;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\HttpFoundation\Request;

class MediaContentService
{
    /**
     * Каталог - источник для массового импорта
     */
    protected const MULTI_IMPORT_DIR = '/mnt/sprut';

    protected $mediaDir = '';

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
//        return [
//            'request' => $request->request,
//            'files' => $request->files,
//            'post' => $_POST,
//        ];

        if ($request->request->has('name') && $request->request->has('directory')) {
            $data['name'] = $request->get('name');
            $data['file_name'] = $request->get('file_name') ?? null;
            $data['file'] = $request->get('file') ?? null;
            $directory = $request->get('directory');
            if (null !== $directory) {
                $data['directory'] = $entityManager->getRepository(MediaDirectory::class)->find($directory) ?? null;
            }

            if ($request->request->has('is_multiimport')) {
                $data['is_multiimport'] = intval($request->request->get('is_multiimport'));
            }

            // Загрузка файла
            // Если не мультиимпорт, то надо сначала к нам таки загрузить файл
            if (!$request->request->has('is_multiimport') || 1 != $request->request->get('is_multiimport')) {
                // поле: uploaded_file
                if ($request->files->has('uploaded_file')) {
                    $uploadedContent = $request->files->get('uploaded_file');

                    dd([
                        '$uploadedContent' => $uploadedContent ?? '-',
                    ]);

                    $uploadedImage = null;
                }
            }

            return $data;
        }

        return [];
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

            // Первое приближение же
            $this->mediaDir = $_SERVER['DOCUMENT_ROOT'].'/mediabank';

            $writeResult = match ($type) {
                1 => $this->writeImageMediaContent((int)$content->getId(), $data, $entityManager),
                2 => $this->writeVideoMediaContent((int)$content->getId(), $data, $entityManager),
                3 => $this->writeTextMediaContent((int)$content->getId(), $data, $entityManager),
            };

            if (empty($writeResult)) {
                return null;
            }

            return $content;
        } else {
            return null;
        }
    }

    /**
     * @TODO Отрефакторить
     * @param int $id
     * @param array $data
     * @return array|null
     */
    private function writeImageMediaContent(int $id, array $data, EntityManagerInterface $entityManager): bool
    {
        $copyFrom = '';
        $newFilename = $data['file_name'];
        $copyTo = $this->mediaDir.'/hight';

        if (!empty($data['is_multiimport'])) {
            $copyFrom = self::MULTI_IMPORT_DIR;
        }

        // Check file exists
        if (!file_exists($copyFrom.'/'.$data['file_name'])) {
            return false;
        }

        // Detect same file
        if (is_file($copyTo.'/'.$data['file_name'])) {
            $newFilename = uniqid().'.'.$data['file_name'];
        }

        // Copy file
        if (false === copy($copyFrom.'/'.$data['file_name'], $copyTo.'/'.$newFilename)) {
            return false;
        }

        // Generate image thumbnail
        $thumbnail = (new ImageMediaContentService())->generateThumbnail(
            $this->mediaDir.'/hight'.'/'.$newFilename,
            $this->mediaDir.'/low'.'/'.$newFilename
        );

        if (false === $thumbnail) {
            copy($this->mediaDir.'/hight'.'/'.$newFilename, $this->mediaDir.'/low'.'/'.$newFilename);
        }

        $srcImageSize = getimagesize($this->mediaDir.'/hight'.'/'.$newFilename);
        $srcType = explode('/', $srcImageSize['mime']);
        $mediaContent = $entityManager
            ->getRepository(MediaContent::class)
            ->find($id);

        $mediaContent->setLink($newFilename);

        $imgMediaContent = new ImgMediaContent();
        $imgMediaContent->setMediaContent($mediaContent);
        $imgMediaContent->setWidth($srcImageSize[0]);
        $imgMediaContent->setHeight($srcImageSize[1]);
        $imgMediaContent->setType($srcType[1]);

        $entityManager->persist($imgMediaContent);
        $entityManager->flush();

        return true;
    }

    /**
     * @TODO дописать
     * @param int $id
     * @param array $data
     * @return array|null
     */
    private function writeVideoMediaContent(int $id, array $data, EntityManagerInterface $entityManager): ?array
    {
        return [];
    }

    /**
     * @TODO дописать
     * @param int $id
     * @param array $data
     * @return array|null
     */
    private function writeTextMediaContent(int $id, array $data, EntityManagerInterface $entityManager): ?array
    {
        return [];
    }

    /**
     * @TODO -
     * @param \App\Service\TypeMediaContentService $typeMediaContentService
     * @return array
     */
    private function directoryOperations(
        TypeMediaContentService $typeMediaContentService
    ): array
    {
        return [
            'type_id' => $typeMediaContentService::TYPE_ID,
            'directory_id' => $typeMediaContentService::DIRECTORY_ID
        ];
    }
}
