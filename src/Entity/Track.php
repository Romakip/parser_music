<?php

namespace App\Entity;

use App\Repository\TrekRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrekRepository::class)
 */
class Track
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="treks")
     */
    private $genre;

    /**
     * @ORM\ManyToMany(targetEntity=Executor::class, inversedBy="treks")
     */
    private $executors;

    /**
     * @ORM\ManyToOne(targetEntity=MusicAlbum::class, inversedBy="treks")
     */
    private $music_album;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $year;

    public function __construct()
    {
        $this->executors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection<int, Executor>
     */
    public function getExecutors(): Collection
    {
        return $this->executors;
    }

    public function addExecutor(Executor $executor): self
    {
        if (!$this->executors->contains($executor)) {
            $this->executors[] = $executor;
        }

        return $this;
    }

    public function removeExecutor(Executor $executor): self
    {
        $this->executors->removeElement($executor);

        return $this;
    }

    public function getMusicAlbum(): ?MusicAlbum
    {
        return $this->music_album;
    }

    public function setMusicAlbum(?MusicAlbum $music_album): self
    {
        $this->music_album = $music_album;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }
}
