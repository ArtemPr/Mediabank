<?php

namespace App\Entity;

use App\Repository\MediaDirectoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaDirectoryRepository::class)]
class MediaDirectory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $pid = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $order_number = null;

    #[ORM\OneToMany(mappedBy: 'directory', targetEntity: MediaContent::class)]
    private Collection $mediaContents;

    public function __construct()
    {
        $this->mediaContents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPid(): ?int
    {
        return $this->pid;
    }

    public function setPid(int $pid): self
    {
        $this->pid = $pid;

        return $this;
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

    public function getOrderNumber(): ?int
    {
        return $this->order_number;
    }

    public function setOrderNumber(int $order_number): self
    {
        $this->order_number = $order_number;

        return $this;
    }

    /**
     * @return Collection<int, MediaContent>
     */
    public function getMediaContents(): Collection
    {
        return $this->mediaContents;
    }

    public function addMediaContent(MediaContent $mediaContent): self
    {
        if (!$this->mediaContents->contains($mediaContent)) {
            $this->mediaContents->add($mediaContent);
            $mediaContent->setDirectory($this);
        }

        return $this;
    }

    public function removeMediaContent(MediaContent $mediaContent): self
    {
        if ($this->mediaContents->removeElement($mediaContent)) {
            // set the owning side to null (unless already changed)
            if ($mediaContent->getDirectory() === $this) {
                $mediaContent->setDirectory(null);
            }
        }

        return $this;
    }
}
