<?php

namespace App\Service;

use App\Entity\MediaDirectory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class MediaDirectoryService
{
    public function setData(
        Request $request,
        EntityManagerInterface $entityManager
    )
    {
        if ($request->request->has('name') && $request->request->has('directory')) {
            $data['name'] = $request->get('name');
            $directory = $request->get('directory');
            if (null !== $directory) {
                $data['directory'] = $entityManager->getRepository(MediaDirectory::class)->find($directory) ?? null;
            }
            return $data;
        }
        return null;
    }

    /**
     * @param array $data
     * @param MediaDirectory $directory
     * @param EntityManagerInterface $entityManager
     * @return MediaDirectory|null
     */
    public function writeDirectory(
        array $data,
        MediaDirectory $directory,
        EntityManagerInterface $entityManager
    ): ?MediaDirectory
    {
        extract($data);
        if (null !== $name) {
            $orderNumber = $entityManager->getRepository(MediaDirectory::class)
                ->getMaxOrderNumber($data['pid']) ?? 1;
            ++$orderNumber;
            $directory->setName($name);
            $directory->setPid($data['pid'] ?? 0);
            $directory->setOrderNumber($orderNumber);
            $entityManager->persist($directory);
            $entityManager->flush();
            return $directory;
        } else {
            return null;
        }
    }
}