<?php

namespace App\Entity;

use App\Repository\JediniceMjereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JediniceMjereRepository::class)]
class JediniceMjere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $naziv = null;

    #[ORM\Column(length: 10)]
    private ?string $kratica = null;

    /**
     * @var Collection<int, Artikli>
     */
    #[ORM\OneToMany(targetEntity: Artikli::class, mappedBy: 'jedinicaMjere')]
    private Collection $artikli;

    public function __construct()
    {
        $this->artikli = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNaziv(): ?string
    {
        return $this->naziv;
    }

    public function setNaziv(string $naziv): static
    {
        $this->naziv = $naziv;

        return $this;
    }

    public function getKratica(): ?string
    {
        return $this->kratica;
    }

    public function setKratica(string $kratica): static
    {
        $this->kratica = $kratica;

        return $this;
    }

    /**
     * @return Collection<int, Artikli>
     */
    public function getArtikli(): Collection
    {
        return $this->artikli;
    }

    public function addArtikli(Artikli $artikli): static
    {
        if (!$this->artikli->contains($artikli)) {
            $this->artikli->add($artikli);
            $artikli->setJedinicaMjere($this);
        }

        return $this;
    }

    public function removeArtikli(Artikli $artikli): static
    {
        if ($this->artikli->removeElement($artikli)) {
            // set the owning side to null (unless already changed)
            if ($artikli->getJedinicaMjere() === $this) {
                $artikli->setJedinicaMjere(null);
            }
        }

        return $this;
    }
}
