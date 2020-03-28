<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\BoardGame", mappedBy="categories")
     */
    private $boardGames;

    public function __construct()
    {
        $this->boardGames = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|BoardGame[]
     */
    public function getBoardGames(): Collection
    {
        return $this->boardGames;
    }

    public function addBoardGame(BoardGame $boardGame): self
    {
        if (!$this->boardGames->contains($boardGame)) {
            $this->boardGames[] = $boardGame;
            $boardGame->addCategory($this);
        }

        return $this;
    }

    public function removeBoardGame(BoardGame $boardGame): self
    {
        if ($this->boardGames->contains($boardGame)) {
            $this->boardGames->removeElement($boardGame);
            $boardGame->removeCategory($this);
        }

        return $this;
    }
}
