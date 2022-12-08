<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Girl
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=\App\Repository\GirlRepository::class)
 */
class Girl
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private ?int $created_at = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $created_by = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="integer", options={"default":1000})
     */
    private ?int $rating = null;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private ?int $votes = null;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private ?int $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?int
    {
        return $this->created_at;
    }

    public function setCreatedAt(int $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getVotes(): ?int
    {
        return $this->votes;
    }

    public function setVotes(int $votes): self
    {
        $this->votes = $votes;

        return $this;
    }

    public function getUpdatedAt(): ?int
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(int $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return __DIR__ . '/../../public/uploads/' . $this->getId() . '.jpg';
    }

    /**
     * @return string
     */
    public function getFileUrl(): string
    {
        if (!file_exists($this->getFilePath())) {
            return '';
        }

        return '/uploads/' . $this->getId() . '.jpg?v=' . filemtime($this->getFilePath());
    }
}
