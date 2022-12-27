<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VideoMediaContentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoMediaContentRepository::class)]
class VideoMediaContent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'videoMediaContent', cascade: ['persist', 'remove'])]
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
