<?php

namespace App\Entity;

use App\Repository\ValuteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValuteRepository::class)]
class Valute
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
    #[ORM\OneToMany(targetEntity: Artikli::class, mappedBy: 'valute')]
    private Collection $artikli;

    /**
     * @var Collection<int, TecajPremaEuro>
     */
    #[ORM\OneToMany(targetEntity: TecajPremaEuro::class, mappedBy: 'valute')]
    private Collection $tecajPremaEuros;

    #[ORM\Column(length: 6)]
    private ?string $sifra = null;

    public function __construct()
    {
        $this->artikli = new ArrayCollection();
        $this->tecajPremaEuros = new ArrayCollection();
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
            $artikli->setValute($this);
        }

        return $this;
    }

    public function removeArtikli(Artikli $artikli): static
    {
        if ($this->artikli->removeElement($artikli)) {
            // set the owning side to null (unless already changed)
            if ($artikli->getValute() === $this) {
                $artikli->setValute(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TecajPremaEuro>
     */
    public function getTecajPremaEuros(): Collection
    {
        return $this->tecajPremaEuros;
    }

    public function addTecajPremaEuro(TecajPremaEuro $tecajPremaEuro): static
    {
        if (!$this->tecajPremaEuros->contains($tecajPremaEuro)) {
            $this->tecajPremaEuros->add($tecajPremaEuro);
            $tecajPremaEuro->setValute($this);
        }

        return $this;
    }

    public function removeTecajPremaEuro(TecajPremaEuro $tecajPremaEuro): static
    {
        if ($this->tecajPremaEuros->removeElement($tecajPremaEuro)) {
            // set the owning side to null (unless already changed)
            if ($tecajPremaEuro->getValute() === $this) {
                $tecajPremaEuro->setValute(null);
            }
        }

        return $this;
    }

    public function getSifra(): ?string
    {
        return $this->sifra;
    }

    public function setSifra(string $sifra): static
    {
        $this->sifra = $sifra;

        return $this;
    }
}
