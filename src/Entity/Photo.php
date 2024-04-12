<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo
{
    use TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    private ?array $meta_info = null;

    // #[ORM\Column(options: ["default" => '2024-04-09'])]
    // private ?\DateTimeImmutable $createdAt = null;

    // #[ORM\Column(nullable: true)]
    // private ?\DateTimeImmutable $modifiedAt = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'photos')]
    private Collection $tags;


    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    /**
     * @ORM\PrePersist
     */
    public function generateSlugOnPrePersist(): void
    {
        if (empty($this->slug)) {
            $slugger = new AsciiSlugger();
            $this->slug = $slugger->slug($this->title);
        }
    }

    public function __construct(string $title)
    {
        $this->tags = new ArrayCollection();
        $this->slug = (new AsciiSlugger())->slug($title);
        $this->title = $title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getMetaInfo(): ?array
    {
        return $this->meta_info;
    }

    public function setMetaInfo(?array $meta_info): static
    {
        $this->meta_info = $meta_info;

        return $this;
    }

    // public function getCreatedAt(): ?\DateTimeImmutable
    // {
    //     return $this->createdAt;
    // }

    // public function setCreatedAt(\DateTimeImmutable $createdAt): static
    // {
    //     $this->createdAt = $createdAt;

    //     return $this;
    // }

    // public function getModifiedAt(): ?\DateTimeImmutable
    // {
    //     return $this->modifiedAt;
    // }

    // public function setModifiedAt(?\DateTimeImmutable $modifiedAt): static
    // {
    //     $this->modifiedAt = $modifiedAt;

    //     return $this;
    // }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
