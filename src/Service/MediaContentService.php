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
    public function setData(
        Request $request,
        EntityManagerInterface $entityManager
    ): ?array
    {
        $data = [];

        /*
         * В этом месте, как оказалось, при любом импорте, приходит только в $_POST и в $_FILES (если последнее необходимо),
         * а в $request этих данных нет. Поэтому здесь и далее используются $_POST и $_FILES (если последнее необходимо).
         *
         * FILTER_SANITIZE_STRING is deprecated as of PHP 8.1.0, use htmlspecialchars() instead.
         */
        $data['name'] = htmlspecialchars($_POST['name']) ?? '';
        $directory = htmlspecialchars($_POST['directory']) ?? '';

        if (!empty($data['name']) && !empty($directory)) {
            $data['file_name'] = htmlspecialchars($_POST['file_name']) ?? '';
            $data['file'] = htmlspecialchars($_POST['file']) ?? '';
            $data['directory'] = $entityManager->getRepository(MediaDirectory::class)->find($directory) ?? null;
            $data['uploaded_by'] = filter_input(INPUT_POST, 'uploaded_by', FILTER_SANITIZE_NUMBER_INT, [
                'options' => [
                    'default' => 0,
                ],
            ]);
            $isMultiimport = filter_input(INPUT_POST, 'is_multiimport', FILTER_SANITIZE_NUMBER_INT, [
                'options' => [
                    'default' => 0,
                ],
            ]);

            if (!empty($isMultiimport)) {
                $data['is_multiimport'] = intval($request->request->get('is_multiimport'));
            }

            // Загрузка файла
            // Если не мультиимпорт, то надо сначала к нам таки загрузить файл
            if (empty($isMultiimport) && !empty($_FILES['uploaded_file']) && UPLOAD_ERR_OK == $_FILES['uploaded_file']['error']) {
                $data['uploaded_file'] = $_FILES['uploaded_file'];

                $data['uploaded_file_exists'] = file_exists($_FILES['uploaded_file']['tmp_name']);
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
        //
        // Далее в методе встретится snake_case, т.к. используется extract из массива!
        //
        if (null !== $name && null !== $file_name && null !== $file && null !== $directory) {
            $type = 1;
            $content->setName($name);
            $content->setDirectory($directory);
            $content->setLink($file_name);
            $content->setDateCreate(new DateTime());
            $content->setDelete(false);
            $content->setUploadedUser($uploaded_by);
            $content->setType(1);

            // Первое приближение же
            $this->mediaDir = $_SERVER['DOCUMENT_ROOT'].'/mediabank';

            $entityManager->persist($content);
            $entityManager->flush();

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
            $copyFrom = self::MULTI_IMPORT_DIR.'/'.$data['file_name'];
        } else {
            $copyFrom = $data['uploaded_file']['tmp_name'];
        }

        // Check file exists
        if (!file_exists($copyFrom)) {
            return false;
        }

        // Detect same file
        if (is_file($copyTo.'/'.$data['file_name'])) {
            $newFilename = uniqid().'.'.$data['file_name'];
        }

        // Copy file
        if (false === copy($copyFrom, $copyTo.'/'.$newFilename)) {
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
