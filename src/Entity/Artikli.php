<?php

namespace App\Entity;

use App\Repository\ArtikliRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArtikliRepository::class)]
class Artikli
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $naziv = null;

    #[ORM\Column]
    private ?float $stanjeNaSkladistu = null;

    #[ORM\Column(nullable: true)]
    private ?float $cijena = null;

    #[ORM\Column(nullable: true)]
    private ?float $trazenoStanje = null;

    #[ORM\Column(nullable: true)]
    private ?float $cijenaUNabavi = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $krajnjiRokNabave = null;

    #[ORM\ManyToOne(inversedBy: 'artikli')]
    private ?JediniceMjere $jedinicaMjere = null;

    #[ORM\ManyToOne(inversedBy: 'artikli')]
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

    public function getNaziv(): ?string
    {
        return $this->naziv;
    }

    public function setNaziv(string $naziv): static
    {
        $this->naziv = $naziv;

        return $this;
    }

    public function getStanjeNaSkladistu(): ?float
    {
        return $this->stanjeNaSkladistu;
    }

    public function setStanjeNaSkladistu(float $stanjeNaSkladistu): static
    {
        $this->stanjeNaSkladistu = $stanjeNaSkladistu;

        return $this;
    }

    public function getCijena(): ?float
    {
        return $this->cijena;
    }

    public function setCijena(?float $cijena): static
    {
        $this->cijena = $cijena;

        return $this;
    }

    public function getTrazenoStanje(): ?float
    {
        return $this->trazenoStanje;
    }

    public function setTrazenoStanje(?float $trazenoStanje): static
    {
        $this->trazenoStanje = $trazenoStanje;

        return $this;
    }

    public function getCijenaUNabavi(): ?float
    {
        return $this->cijenaUNabavi;
    }

    public function setCijenaUNabavi(?float $cijenaUNabavi): static
    {
        $this->cijenaUNabavi = $cijenaUNabavi;

        return $this;
    }

    public function getKrajnjiRokNabave(): ?\DateTimeInterface
    {
        return $this->krajnjiRokNabave;
    }

    public function setKrajnjiRokNabave(?\DateTimeInterface $krajnjiRokNabave): static
    {
        $this->krajnjiRokNabave = $krajnjiRokNabave;

        return $this;
    }

    public function getJedinicaMjere(): ?JediniceMjere
    {
        return $this->jedinicaMjere;
    }

    public function setJedinicaMjere(?JediniceMjere $jedinicaMjere): static
    {
        $this->jedinicaMjere = $jedinicaMjere;

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
