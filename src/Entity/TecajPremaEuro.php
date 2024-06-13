<?php

namespace App\Entity;

use App\Repository\TecajPremaEuroRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TecajPremaEuroRepository::class)]
class TecajPremaEuro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $tecaj = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $naDan = null;

    #[ORM\ManyToOne(inversedBy: 'tecajPremaEuros')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Valute $valute = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTecaj(): ?float
    {
        return $this->tecaj;
    }

    public function setTecaj(float $tecaj): static
    {
        $this->tecaj = $tecaj;

        return $this;
    }

    public function getNaDan(): ?\DateTimeInterface
    {
        return $this->naDan;
    }

    public function setNaDan(\DateTimeInterface $naDan): static
    {
        $this->naDan = $naDan;

        return $this;
    }

    public function getValute(): ?Valute
    {
        return $this->valute;
    }

    public function setValute(?Valute $valute): static
    {
        $this->valute = $valute;

        return $this;
    }
}
