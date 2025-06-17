<?php

namespace App\Entity;

use App\Repository\ColabRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColabRepository::class)]
class Colab
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomprenom = null;

    #[ORM\Column(length: 255)]
    private ?string $fonction = null;

    #[ORM\Column(length: 255)]
    private ?string $nomentreprise = null;

    #[ORM\Column(length: 255)]
    private ?string $site = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $tel = null;

    #[ORM\Column(length: 255)]
    private ?string $linkedin = null;

    #[ORM\Column(length: 255)]
    private ?string $pays = null;

    #[ORM\Column(length: 255)]
    private ?string $typetalent = null;

    #[ORM\Column(length: 255)]
    private ?string $secteur = null;


    #[ORM\Column(length: 255)]
    private ?string $typecolab = null;

    #[ORM\Column(length: 255)]
    private ?string $durecolab = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(length: 255)]
    private ?string $ficher = null;

    #[ORM\Column(length: 255)]
    private ?string $politique = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomprenom(): ?string
    {
        return $this->nomprenom;
    }

    public function setNomprenom(string $nomprenom): static
    {
        $this->nomprenom = $nomprenom;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): static
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getNomentreprise(): ?string
    {
        return $this->nomentreprise;
    }

    public function setNomentreprise(string $nomentreprise): static
    {
        $this->nomentreprise = $nomentreprise;

        return $this;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(string $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(string $linkedin): static
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getTypetalent(): ?string
    {
        return $this->typetalent;
    }

    public function setTypetalent(string $typetalent): static
    {
        $this->typetalent = $typetalent;

        return $this;
    }

    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    public function setSecteur(string $secteur): static
    {
        $this->secteur = $secteur;

        return $this;
    }

    

    public function getTypecolab(): ?string
    {
        return $this->typecolab;
    }

    public function setTypecolab(string $typecolab): static
    {
        $this->typecolab = $typecolab;

        return $this;
    }

    public function getDurecolab(): ?string
    {
        return $this->durecolab;
    }

    public function setDurecolab(string $durecolab): static
    {
        $this->durecolab = $durecolab;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getFicher(): ?string
    {
        return $this->ficher;
    }

    public function setFicher(string $ficher): static
    {
        $this->ficher = $ficher;

        return $this;
    }

    public function getPolitique(): ?string
    {
        return $this->politique;
    }

    public function setPolitique(string $politique): static
    {
        $this->politique = $politique;

        return $this;
    }
}
