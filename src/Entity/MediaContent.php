<?php

namespace App\Entity;

use App\Repository\MediaContentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaContentRepository::class)]
class MediaContent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_create = null;

    #[ORM\Column]
    private ?bool $delete = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?MediaDirectory $directory = null;

    #[ORM\Column]
    private ?int $uploaded_user = null;

    #[ORM\Column]
    private ?int $type = null;

    #[ORM\Column(length: 255)]
    private ?string $link = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_update = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_approval = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $approval_user = null;

    #[ORM\OneToOne(mappedBy: 'media_content', cascade: ['persist', 'remove'])]
    private ?TextMediaContent $textMediaContent = null;

    #[ORM\OneToOne(mappedBy: 'media_content', cascade: ['persist', 'remove'])]
    private ?VideoMediaContent $videoMediaContent = null;

    #[ORM\OneToOne(mappedBy: 'media_content', cascade: ['persist', 'remove'])]
    private ?ImgMediaContent $imgVideoContent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->date_create;
    }

    public function setDateCreate(\DateTimeInterface $date_create): self
    {
        $this->date_create = $date_create;

        return $this;
    }

    public function isDelete(): ?bool
    {
        return $this->delete;
    }

    public function setDelete(bool $delete): self
    {
        $this->delete = $delete;

        return $this;
    }

    public function getDirectory(): ?MediaDirectory
    {
        return $this->directory;
    }

    public function setDirectory(?MediaDirectory $directory): self
    {
        $this->directory = $directory;

        return $this;
    }

    public function getUploadedUser(): ?int
    {
        return $this->uploaded_user;
    }

    public function setUploadedUser(int $uploaded_user): self
    {
        $this->uploaded_user = $uploaded_user;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getDateUpdate(): ?\DateTimeInterface
    {
        return $this->date_update;
    }

    public function setDateUpdate(\DateTimeInterface $date_update): self
    {
        $this->date_update = $date_update;

        return $this;
    }

    public function getDateApproval(): ?\DateTimeInterface
    {
        return $this->date_approval;
    }

    public function setDateApproval(\DateTimeInterface $date_approval): self
    {
        $this->date_approval = $date_approval;

        return $this;
    }

    public function getApprovalUser(): ?int
    {
        return $this->approval_user;
    }

    public function setApprovalUser(int $approval_user): self
    {
        $this->approval_user = $approval_user;

        return $this;
    }

    public function getTextMediaContent(): ?TextMediaContent
    {
        return $this->textMediaContent;
    }

    public function setTextMediaContent(TextMediaContent $textMediaContent): self
    {
        // set the owning side of the relation if necessary
        if ($textMediaContent->getMediaContent() !== $this) {
            $textMediaContent->setMediaContent($this);
        }

        $this->textMediaContent = $textMediaContent;

        return $this;
    }

    public function getVideoMediaContent(): ?VideoMediaContent
    {
        return $this->videoMediaContent;
    }

    public function setVideoMediaContent(VideoMediaContent $videoMediaContent): self
    {
        // set the owning side of the relation if necessary
        if ($videoMediaContent->getMediaContent() !== $this) {
            $videoMediaContent->setMediaContent($this);
        }

        $this->videoMediaContent = $videoMediaContent;

        return $this;
    }

    public function getImgVideoContent(): ?ImgMediaContent
    {
        return $this->imgVideoContent;
    }

    public function setImgVideoContent(ImgMediaContent $imgVideoContent): self
    {
        // set the owning side of the relation if necessary
        if ($imgVideoContent->getMediaContent() !== $this) {
            $imgVideoContent->setMediaContent($this);
        }

        $this->imgVideoContent = $imgVideoContent;

        return $this;
    }
}
