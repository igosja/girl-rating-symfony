<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Vote
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=\App\Repository\VoteRepository::class)
 */
class Vote
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
     * @ORM\ManyToOne(targetEntity=Girl::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Girl $girl_one = null;

    /**
     * @ORM\ManyToOne(targetEntity=Girl::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Girl $girl_two = null;

    /**
     * @ORM\ManyToOne(targetEntity=Girl::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Girl $girl_winner = null;

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

    public function getGirlOne(): ?Girl
    {
        return $this->girl_one;
    }

    public function setGirlOne(Girl $girl_one): self
    {
        $this->girl_one = $girl_one;

        return $this;
    }

    public function getGirlTwo(): ?Girl
    {
        return $this->girl_two;
    }

    public function setGirlTwo(Girl $girl_two): self
    {
        $this->girl_two = $girl_two;

        return $this;
    }

    public function getGirlWinner(): ?Girl
    {
        return $this->girl_winner;
    }

    public function setGirlWinner(Girl $girl_winner): self
    {
        $this->girl_winner = $girl_winner;

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
}
