<?php

namespace App\Entity;

use App\Repository\ImgMediaContentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImgMediaContentRepository::class)]
class ImgMediaContent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'imgMediaContent', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?MediaContent $media_content = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMediaContent(): ?MediaContent
    {
        return $this->media_content;
    }

    public function setMediaContent(MediaContent $media_content): self
    {
        $this->media_content = $media_content;

        return $this;
    }
}
