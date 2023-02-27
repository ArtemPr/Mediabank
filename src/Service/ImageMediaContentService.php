<?php

namespace App\Service;

class ImageMediaContentService implements TypeMediaContentService
{
    const TYPE_ID = 1;

    const DIRECTORY_ID = 'image';

    /**
     * Предел по наибольшей стороне для генерации превью.
     */
    protected const THUMBNAIL_PX_LIMIT = 250;

    /**
     * Генерация миниатюры (первое приближение).
     * @param string $src - откуда
     * @param string $dst - куда
     * @return bool
     */
    public function generateThumbnail(string $src, string $dst): bool
    {
        $srcImageSize = getimagesize($src);

        if (empty($srcImageSize[0]) || empty($srcImageSize[1])) {
            return false;
        }

        $newW = ($srcImageSize[0] > $srcImageSize[1])
            ? self::THUMBNAIL_PX_LIMIT
            : round($srcImageSize[0] * self::THUMBNAIL_PX_LIMIT / $srcImageSize[1], PHP_ROUND_HALF_UP);
        $newH = ($srcImageSize[0] > $srcImageSize[1])
            ? round($srcImageSize[1] * self::THUMBNAIL_PX_LIMIT / $srcImageSize[0], PHP_ROUND_HALF_UP)
            : self::THUMBNAIL_PX_LIMIT;

        $x1 = round(($srcImageSize[0] - $newW) / 2, PHP_ROUND_HALF_UP);
        $y1 = round(($srcImageSize[1] - $newH) / 2, PHP_ROUND_HALF_UP);

        switch (strtolower($srcImageSize['mime']))
        {
            case 'image/png':
                $img = imagecreatefrompng($src);
                $new = imagecreatetruecolor($newW, $newH);
                imagecopy($new, $img, 0, 0, $x1, $y1, $newW, $newH);
                imagepng($new, $dst, 9);
                imagedestroy($new);
                break;

            case 'image/jpg':
            case 'image/jpeg':
                $img = imagecreatefromjpeg($src);
                $new = imagecreatetruecolor($newW, $newH);
                imagecopy($new, $img, 0, 0, $x1, $y1, $newW, $newH);
                imagejpeg($new, $dst);
                imagedestroy($new);
                break;

            case 'image/gif':
                $img = imagecreatefromgif($src);
                $img = imagecreatefromgif($src);
                $new = imagecreatetruecolor($newW, $newH);
                imagefill($new, 0, 0, imagecolorallocate($new, 255, 255, 255));
                imagecopy($new, $img, 0, 0, $x1, $y1, $newW, $newH);
                imagegif($new, $dst, 90);
                break;

            default:
                return false;
        }

        return true;
    }
}